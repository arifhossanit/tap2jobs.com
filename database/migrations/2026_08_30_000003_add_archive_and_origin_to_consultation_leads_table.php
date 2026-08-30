<?php

use App\Models\ConsultationLead;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_leads', function (Blueprint $table) {
            if (! Schema::hasColumn('consultation_leads', 'lead_from')) {
                $table->string('lead_from', 40)->default(ConsultationLead::LEAD_FROM_CONSULTATION_FORM)->after('admin_notes');
            }

            if (! Schema::hasColumn('consultation_leads', 'employer_id')) {
                $table->unsignedInteger('employer_id')->nullable()->after('lead_from');
            }

            if (! Schema::hasColumn('consultation_leads', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        $this->backfillEmployerLeads();
    }

    public function down(): void
    {
        Schema::table('consultation_leads', function (Blueprint $table) {
            if (Schema::hasColumn('consultation_leads', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            if (Schema::hasColumn('consultation_leads', 'employer_id')) {
                $table->dropColumn('employer_id');
            }

            if (Schema::hasColumn('consultation_leads', 'lead_from')) {
                $table->dropColumn('lead_from');
            }
        });
    }

    private function backfillEmployerLeads(): void
    {
        $leadSource = getSettingValue('application_name') ?: config('app.name');
        $existingEmployerIds = DB::table('consultation_leads')
            ->where('lead_from', ConsultationLead::LEAD_FROM_EMPLOYER)
            ->whereNotNull('employer_id')
            ->pluck('employer_id')
            ->all();

        DB::table('companies')
            ->join('users', 'companies.user_id', '=', 'users.id')
            ->leftJoin('company_sizes', 'companies.company_size_id', '=', 'company_sizes.id')
            ->whereNotIn('companies.id', $existingEmployerIds)
            ->select([
                'companies.id',
                'companies.company_name',
                'companies.company_size_id',
                'companies.contact_person_designation',
                'companies.website',
                'companies.created_at',
                'companies.updated_at',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.phone',
                'company_sizes.company_category_id',
            ])
            ->orderBy('companies.id')
            ->chunkById(100, function ($companies) use ($leadSource) {
                $now = now();
                $rows = [];

                foreach ($companies as $company) {
                    $name = trim($company->first_name.' '.($company->last_name ?? ''));

                    $rows[] = [
                        'name' => $name !== '' ? $name : $company->company_name,
                        'email' => $company->email ?: 'N/A',
                        'phone' => $company->phone ?: 'N/A',
                        'company_name' => $company->company_name,
                        'designation' => $company->contact_person_designation,
                        'company_website' => $company->website,
                        'company_size_id' => $company->company_size_id,
                        'company_category_id' => $company->company_category_id,
                        'consultation_type' => '',
                        'source_page' => $leadSource,
                        'status' => ConsultationLead::STATUS_NEW,
                        'lead_from' => ConsultationLead::LEAD_FROM_EMPLOYER,
                        'employer_id' => $company->id,
                        'created_at' => $company->created_at ?? $now,
                        'updated_at' => $company->updated_at ?? $now,
                    ];
                }

                DB::table('consultation_leads')->insert($rows);
            }, 'companies.id', 'id');
    }
};
