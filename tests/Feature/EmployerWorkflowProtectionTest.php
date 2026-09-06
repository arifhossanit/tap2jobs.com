<?php

namespace Tests\Feature;

use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobController;
use App\Livewire\JobApplicationTable;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobApplicationSchedule;
use App\Models\User;
use App\Repositories\JobApplicationRepository;
use App\Repositories\JobRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class EmployerWorkflowProtectionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('status')->default(1);
            $table->boolean('is_suspended')->default(false);
            $table->date('job_expiry_date')->nullable();
            $table->timestamps();
        });
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->integer('job_id');
            $table->integer('status')->default(1);
            $table->integer('job_stage_id')->nullable();
            $table->timestamps();
        });
        Schema::create('job_stages', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('name');
        });
        Schema::create('job_application_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('job_application_id');
            $table->date('date');
            $table->string('time');
            $table->text('notes')->nullable();
            $table->integer('status')->default(0);
            $table->integer('batch')->default(1);
            $table->integer('stage_id')->nullable();
            $table->text('employer_cancel_slot_notes')->nullable();
            $table->timestamps();
        });
        DB::table('jobs')->insert([
            ['id' => 1, 'company_id' => 10, 'status' => 1],
            ['id' => 2, 'company_id' => 20, 'status' => 1],
        ]);
        DB::table('job_applications')->insert([
            ['id' => 1, 'job_id' => 1, 'status' => 1],
            ['id' => 2, 'job_id' => 2, 'status' => 1],
        ]);
        DB::table('job_stages')->insert(['id' => 20, 'company_id' => 20, 'name' => 'Other employer']);
        Auth::setUser((new User)->forceFill(['id' => 100, 'owner_id' => 10]));
        // These tests exercise ownership/status, not the job's display relations.
        Job::addGlobalScope('test-no-display-relations', fn ($query) => $query->setEagerLoads([]));
    }

    protected function tearDown(): void
    {
        Job::clearBootedModels();
        parent::tearDown();
    }

    private function controller(): JobApplicationController
    {
        return new JobApplicationController(Mockery::mock(JobApplicationRepository::class));
    }

    private function slot(int $application = 1, string $date = '2030-01-01', string $time = '10:00'): JobApplicationSchedule
    {
        return JobApplicationSchedule::create([
            'job_application_id' => $application, 'date' => $date, 'time' => $time,
            'batch' => 1, 'status' => 0,
        ]);
    }

    public static function foreignActions(): array
    {
        return array_map(fn ($action) => [$action], ['delete', 'status', 'stage', 'history', 'check', 'create', 'batch', 'update', 'cancel']);
    }

    /** @dataProvider foreignActions */
    public function test_other_employer_records_are_rejected(string $action): void
    {
        $controller = $this->controller();
        $slot = $this->slot(2);
        $request = Request::create('/', 'POST', [
            'jobId' => 2, 'job_application_id' => 2, 'jobApplicationId' => 2,
            'slotId' => $slot->id, 'cancelSlotNote' => ['Unavailable'],
        ]);
        $this->expectException(ModelNotFoundException::class);
        match ($action) {
            'delete' => $controller->destroy(JobApplication::find(2), $request),
            'status' => $controller->changeJobApplicationStatus(2, 2, $request),
            'stage' => $controller->changeJobStage($request),
            'history' => $controller->getScheduleHistory($request),
            'check' => $controller->checkStage(2),
            'create' => $controller->interviewSlotStore(2, $request),
            'batch' => $controller->batchSlotStore($request),
            'update' => $controller->updateSlot($request, 2, $slot),
            'cancel' => $controller->cancelSelectedSlot($request),
        };
    }

    public function test_foreign_stage_is_rejected_for_own_application(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $this->controller()->changeJobStage(Request::create('/', 'POST', ['job_application_id' => 1, 'job_stage' => 20]));
    }

    public function test_livewire_query_excludes_other_employer_even_if_job_id_changes(): void
    {
        $table = new JobApplicationTable;
        $table->jobId = 1;
        $this->assertSame(1, $table->builder()->count());
        $table->jobId = 2;
        $this->assertSame(0, $table->builder()->count());
    }

    public function test_distinct_date_time_pairs_are_allowed(): void
    {
        $this->slot();
        $response = $this->controller()->interviewSlotStore(1, Request::create('/', 'POST', [
            'job_application_id' => 1,
            'date' => [1 => '2030-01-01', 2 => '2030-01-02'],
            'time' => [1 => '11:00', 2 => '10:00'],
        ]));
        $this->assertSame(200, $response->status());
        $this->assertSame(3, JobApplicationSchedule::count());
        $this->assertSame(0, DB::transactionLevel());
    }

    public function test_duplicate_batch_saves_no_partial_slots(): void
    {
        $this->slot();
        $response = $this->controller()->interviewSlotStore(1, Request::create('/', 'POST', [
            'job_application_id' => 1,
            'date' => [1 => '2030-01-02', 2 => '2030-01-01'],
            'time' => [1 => '11:00', 2 => '10:00'],
        ]));
        $this->assertSame(422, $response->status());
        $this->assertSame(1, JobApplicationSchedule::count());
        $this->assertSame(0, DB::transactionLevel());
    }

    public function test_date_only_edit_cannot_duplicate_another_slot(): void
    {
        $this->slot();
        $slot = $this->slot(1, '2030-01-02');
        $response = $this->controller()->updateSlot(Request::create('/', 'POST', [
            'date' => '2030-01-01', 'time' => '10:00',
        ]), 1, $slot);
        $this->assertSame(422, $response->status());
        $this->assertSame('2030-01-02', $slot->fresh()->date);
    }

    public static function blockedJobStatuses(): array
    {
        return [[0], [5], [4]];
    }

    /** @dataProvider blockedJobStatuses */
    public function test_unapproved_job_cannot_change_status(int $currentStatus): void
    {
        DB::table('jobs')->where('id', 1)->update(['status' => $currentStatus]);
        $controller = new JobController(Mockery::mock(JobRepository::class));
        foreach ([1, 2, 3] as $target) {
            $response = $controller->changeJobStatus(1, $target);
            $this->assertSame(422, $response->status());
            $this->assertSame($currentStatus, (int) DB::table('jobs')->where('id', 1)->value('status'));
        }
    }

    public function test_live_job_can_be_paused(): void
    {
        $controller = new JobController(Mockery::mock(JobRepository::class));
        $this->assertSame(200, $controller->changeJobStatus(1, 3)->status());
        $this->assertSame(3, (int) DB::table('jobs')->where('id', 1)->value('status'));
    }

    public function test_invalid_application_status_is_rejected(): void
    {
        $this->expectException(ValidationException::class);
        $this->controller()->changeJobApplicationStatus(1, 99, Request::create('/', 'POST', ['jobId' => 1]));
    }

    public function test_own_slot_can_be_created_edited_and_cancelled(): void
    {
        $controller = $this->controller();
        $response = $controller->batchSlotStore(Request::create('/', 'POST', [
            'job_application_id' => 1, 'date' => '2030-02-01', 'time' => '09:00', 'batch' => 1,
        ]));
        $this->assertSame(200, $response->status());
        $slot = JobApplicationSchedule::first();
        $response = $controller->updateSlot(Request::create('/', 'POST', [
            'date' => '2030-02-02', 'time' => '11:00', 'notes' => 'Updated',
        ]), 1, $slot);
        $this->assertSame(200, $response->status());
        $this->assertSame('2030-02-02', $slot->fresh()->date);
        $response = $controller->cancelSelectedSlot(Request::create('/', 'POST', [
            'slotId' => $slot->id, 'cancelSlotNote' => ['Unavailable'],
        ]));
        $this->assertSame(200, $response->status());
        $this->assertSame(JobApplicationSchedule::STATUS_REJECTED, $slot->fresh()->status);
    }

    public function test_duplicate_in_request_is_rejected_without_writes(): void
    {
        $response = $this->controller()->interviewSlotStore(1, Request::create('/', 'POST', [
            'job_application_id' => 1,
            'date' => [1 => '2030-01-01', 2 => '2030-01-01'],
            'time' => [1 => '10:00', 2 => '10:00'],
        ]));
        $this->assertSame(422, $response->status());
        $this->assertSame(0, JobApplicationSchedule::count());
    }

    public function test_duplicate_single_slot_closes_transaction(): void
    {
        $this->slot();
        $response = $this->controller()->batchSlotStore(Request::create('/', 'POST', [
            'job_application_id' => 1, 'date' => '2030-01-01', 'time' => '10:00', 'batch' => 1,
        ]));
        $this->assertSame(422, $response->status());
        $this->assertSame(0, DB::transactionLevel());
        $this->assertSame(1, JobApplicationSchedule::count());
    }
    public function test_status_routes_require_post(): void
    {
        foreach (['change-job-status', 'change-job-application-status'] as $name) {
            $this->assertSame(['POST'], app('router')->getRoutes()->getByName($name)->methods());
        }
    }
}