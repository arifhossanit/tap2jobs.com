<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CandidateExtraCurricularSaveTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('candidate_extra_curriculars', function (Blueprint $table) {
            $table->id();
            $table->integer('candidate_id');
            $table->text('description');
            $table->timestamps();
        });
        $this->withoutMiddleware();
        $this->actingAs((new User)->forceFill(['id' => 10, 'owner_id' => 20]));
    }

    public function test_save_route_persists_activity_for_logged_in_candidate(): void
    {
        $response = $this->postJson(route('candidate-profile.extracurricular-activities.store'), [
            'description' => 'Volunteer at the college debate club.',
            'candidate_id' => 999,
        ]);
        $response->assertOk()->assertJsonPath('success', true)
            ->assertJsonStructure(['data' => ['id', 'description', 'update_url', 'delete_url']]);
        $this->assertDatabaseHas('candidate_extra_curriculars', [
            'candidate_id' => 20, 'description' => 'Volunteer at the college debate club.',
        ]);
        $this->assertDatabaseMissing('candidate_extra_curriculars', ['candidate_id' => 999]);
    }

    public function test_empty_description_is_rejected(): void
    {
        $this->postJson(route('candidate-profile.extracurricular-activities.store'), [
            'description' => '<p><br></p>',
        ])->assertUnprocessable()->assertJsonPath('message', 'Extracurricular Activities field is required.');
        $this->assertDatabaseCount('candidate_extra_curriculars', 0);
    }
}