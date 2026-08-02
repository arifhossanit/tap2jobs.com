<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('required_degree_levels', function (Blueprint $table) {
            if (! Schema::hasColumn('required_degree_levels', 'code')) {
                $table->string('code', 60)->nullable()->unique()->after('name');
            }
            if (! Schema::hasColumn('required_degree_levels', 'show_board')) {
                $table->boolean('show_board')->default(false)->after('code');
            }
            if (! Schema::hasColumn('required_degree_levels', 'show_major')) {
                $table->boolean('show_major')->default(false)->after('show_board');
            }
            if (! Schema::hasColumn('required_degree_levels', 'show_summary_checkbox')) {
                $table->boolean('show_summary_checkbox')->default(false)->after('show_major');
            }
            if (! Schema::hasColumn('required_degree_levels', 'sort_order')) {
                $table->unsignedSmallInteger('sort_order')->default(0)->after('show_summary_checkbox');
            }
        });

        if (! Schema::hasTable('education_degree_titles')) {
            Schema::create('education_degree_titles', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('required_degree_level_id');
                $table->string('name', 170);
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['required_degree_level_id', 'name'], 'education_degree_titles_level_name_unique');
                $table->foreign('required_degree_level_id')->references('id')->on('required_degree_levels')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }

        if (! Schema::hasTable('education_major_groups')) {
            Schema::create('education_major_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('required_degree_level_id')->nullable();
                $table->string('name', 170);
                $table->boolean('is_custom')->default(false);
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['required_degree_level_id', 'name'], 'education_major_groups_level_name_unique');
                $table->foreign('required_degree_level_id')->references('id')->on('required_degree_levels')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
            });
        }

        if (! Schema::hasTable('education_boards')) {
            Schema::create('education_boards', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 120)->unique();
                $table->unsignedSmallInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        $this->seedLookupData();
    }

    public function down(): void
    {
        Schema::dropIfExists('education_major_groups');
        Schema::dropIfExists('education_degree_titles');
        Schema::dropIfExists('education_boards');

        if (! Schema::hasTable('required_degree_levels')) {
            return;
        }

        if (
            ! empty(DB::select('SHOW INDEX FROM required_degree_levels WHERE Key_name = ?', ['required_degree_levels_code_unique']))
        ) {
            Schema::table('required_degree_levels', function (Blueprint $table) {
                $table->dropUnique('required_degree_levels_code_unique');
            });
        }

        Schema::table('required_degree_levels', function (Blueprint $table) {
            foreach (['sort_order', 'show_summary_checkbox', 'show_major', 'show_board', 'code'] as $column) {
                if (Schema::hasColumn('required_degree_levels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function seedLookupData(): void
    {
        $now = now();
        $levels = [
            ['code' => 'psc', 'name' => 'PSC/5 pass', 'show_board' => true, 'show_major' => false, 'show_summary_checkbox' => false],
            ['code' => 'jsc', 'name' => 'JSC/JDC/8 pass', 'show_board' => true, 'show_major' => false, 'show_summary_checkbox' => false],
            ['code' => 'secondary', 'name' => 'Secondary', 'show_board' => true, 'show_major' => true, 'show_summary_checkbox' => false],
            ['code' => 'higher_secondary', 'name' => 'Higher Secondary', 'show_board' => true, 'show_major' => true, 'show_summary_checkbox' => false],
            ['code' => 'diploma', 'name' => 'Diploma', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true],
            ['code' => 'bachelor', 'name' => 'Bachelor/Honors', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true],
            ['code' => 'masters', 'name' => 'Masters', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => true],
            ['code' => 'phd', 'name' => 'PhD (Doctor of Philosophy)', 'show_board' => false, 'show_major' => true, 'show_summary_checkbox' => false],
        ];

        foreach ($levels as $index => $level) {
            $existingLevelId = DB::table('required_degree_levels')
                ->where('code', $level['code'])
                ->orWhere('name', $level['name'])
                ->value('id');

            if ($existingLevelId) {
                DB::table('required_degree_levels')->where('id', $existingLevelId)->update([
                    'name' => $level['name'],
                    'code' => $level['code'],
                    'show_board' => $level['show_board'],
                    'show_major' => $level['show_major'],
                    'show_summary_checkbox' => $level['show_summary_checkbox'],
                    'sort_order' => $index + 1,
                    'is_default' => 1,
                    'updated_at' => $now,
                ]);
                continue;
            }

            DB::table('required_degree_levels')->insert([
                'name' => $level['name'],
                'code' => $level['code'],
                'show_board' => $level['show_board'],
                'show_major' => $level['show_major'],
                'show_summary_checkbox' => $level['show_summary_checkbox'],
                'sort_order' => $index + 1,
                'is_default' => 1,
                'updated_at' => $now,
                'created_at' => $now,
            ]);
        }

        $levelIds = DB::table('required_degree_levels')->whereIn('code', array_column($levels, 'code'))->pluck('id', 'code');
        $titles = [
            'psc' => ['PSC', 'Ebtedayee (Madrasah)', '5 Pass', 'Others'],
            'jsc' => ['JSC', 'JDC (Madrasah)', '8 Pass', 'Others'],
            'secondary' => ['SSC', 'O Level', 'Dakhil (Madrasah)', 'SSC (Vocational)', 'Others'],
            'higher_secondary' => ['HSC', 'A Level', 'Alim (Madrasah)', 'HSC (Vocational)', 'Others'],
            'diploma' => ['Diploma in Engineering', 'Diploma in Medical Technology', 'Diploma in Nursing', 'Diploma in Commerce', 'Diploma in Business Studies', 'Others'],
            'bachelor' => ['Bachelor/Honors', 'Bachelor of Arts (B.A.)', 'Bachelor of Science (B.Sc.)', 'Bachelor of Business Administration (BBA)', 'Bachelor of Commerce (B.Com)', 'Bachelor of Social Science (BSS)', 'MBBS', 'LLB', 'Others'],
            'masters' => ['Masters', 'Master of Arts (M.A.)', 'Master of Science (M.Sc.)', 'Master of Business Administration (MBA)', 'Master of Commerce (M.Com)', 'Master of Social Science (MSS)', 'LLM', 'Others'],
            'phd' => ['PhD', 'Doctor of Philosophy (PhD)', 'MPhil', 'Others'],
        ];
        $majors = [
            'secondary' => ['General', 'Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
            'higher_secondary' => ['General','Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
            'diploma' => [
                'Architecture', 'Automobile Engineering', 'Business Studies', 'Chemical Engineering', 'Civil Engineering',
                'Commerce', 'Computer Science', 'Computer Science & Engineering', 'Construction Engineering',
                'Data Telecommunication & Networking', 'Electrical Engineering', 'Electrical & Electronic Engineering',
                'Electronics Engineering', 'Environmental Engineering', 'Food Engineering', 'Garments Design & Pattern Making',
                'Graphic Design', 'Hospitality Management', 'Industrial Engineering', 'Instrumentation & Process Control',
                'Marine Engineering', 'Mechanical Engineering', 'Mechatronics Engineering', 'Medical Technology',
                'Mining Engineering', 'Nursing', 'Power Engineering', 'Printing Technology', 'Refrigeration & Air Conditioning',
                'Shipbuilding Technology', 'Surveying Technology', 'Telecommunication Engineering', 'Textile Engineering',
                'Tourism & Hospitality', 'Others',
            ],
            'bachelor' => [
                'Accounting', 'Agribusiness', 'Agriculture', 'Anthropology', 'Applied Chemistry', 'Applied Mathematics',
                'Architecture', 'Bangla', 'Banking & Insurance', 'Biochemistry', 'Biomedical Engineering', 'Biotechnology',
                'Botany', 'Business Administration', 'Chemical Engineering', 'Chemistry', 'Civil Engineering',
                'Communication Disorders', 'Computer Science', 'Computer Science & Engineering', 'Criminology',
                'Data Science', 'Dental Surgery', 'Development Studies', 'Disaster Management', 'Economics',
                'Education', 'Electrical & Electronic Engineering', 'English', 'Environmental Science',
                'Environmental Science & Management', 'Fashion Design', 'Finance', 'Fisheries', 'Food Engineering',
                'Food & Nutrition', 'Forestry', 'Genetic Engineering & Biotechnology', 'Geography & Environment',
                'Graphic Design', 'History', 'Human Resource Management', 'Industrial & Production Engineering',
                'Information Science & Library Management', 'Information Technology', 'International Business',
                'International Relations', 'Islamic Studies', 'Journalism & Mass Communication', 'Law', 'Management',
                'Management Information Systems', 'Marketing', 'Mathematics', 'Mechanical Engineering', 'Medicine',
                'Microbiology', 'Naval Architecture & Marine Engineering', 'Nursing', 'Nutrition & Food Science',
                'Oceanography', 'Peace & Conflict Studies', 'Pharmacy', 'Physics', 'Political Science',
                'Population Science', 'Psychology', 'Public Administration', 'Public Health', 'Social Welfare',
                'Sociology', 'Software Engineering', 'Soil Science', 'Statistics', 'Textile Engineering',
                'Theatre & Performance Studies', 'Tourism & Hospitality Management', 'Urban & Regional Planning',
                'Veterinary Science', 'Zoology', 'Others',
            ],
            'masters' => [
                'Accounting', 'Agribusiness', 'Agriculture', 'Anthropology', 'Applied Chemistry', 'Applied Mathematics',
                'Architecture', 'Bangla', 'Banking & Insurance', 'Biochemistry', 'Biomedical Engineering', 'Biotechnology',
                'Botany', 'Business Administration', 'Chemical Engineering', 'Chemistry', 'Civil Engineering',
                'Computer Science', 'Computer Science & Engineering', 'Data Science', 'Development Studies',
                'Disaster Management', 'Economics', 'Education', 'Electrical & Electronic Engineering', 'English',
                'Environmental Science', 'Finance', 'Fisheries', 'Food Engineering', 'Food & Nutrition',
                'Genetic Engineering & Biotechnology', 'Geography & Environment', 'Governance Studies', 'History',
                'Human Resource Management', 'Industrial & Production Engineering', 'Information Technology',
                'International Business', 'International Relations', 'Islamic Studies', 'Journalism & Mass Communication',
                'Law', 'Management', 'Management Information Systems', 'Marketing', 'Mathematics',
                'Mechanical Engineering', 'Microbiology', 'Nursing', 'Nutrition & Food Science', 'Pharmacy',
                'Physics', 'Political Science', 'Population Science', 'Project Management', 'Psychology',
                'Public Administration', 'Public Health', 'Social Welfare', 'Sociology', 'Software Engineering',
                'Statistics', 'Supply Chain Management', 'Textile Engineering', 'Tourism & Hospitality Management',
                'Urban & Regional Planning', 'Zoology', 'Others',
            ],
            'phd' => [
                'Accounting', 'Agriculture', 'Anthropology', 'Applied Chemistry', 'Applied Mathematics',
                'Architecture', 'Biochemistry', 'Biomedical Engineering', 'Biotechnology', 'Botany',
                'Business Administration', 'Chemical Engineering', 'Chemistry', 'Civil Engineering',
                'Computer Science', 'Computer Science & Engineering', 'Data Science', 'Development Studies',
                'Economics', 'Education', 'Electrical & Electronic Engineering', 'English',
                'Environmental Science', 'Finance', 'Fisheries', 'Food Engineering',
                'Genetic Engineering & Biotechnology', 'Geography & Environment', 'History',
                'Industrial & Production Engineering', 'Information Technology', 'International Relations',
                'Law', 'Management', 'Marketing', 'Mathematics', 'Mechanical Engineering', 'Medical Science',
                'Microbiology', 'Pharmacy', 'Physics', 'Political Science', 'Psychology', 'Public Administration',
                'Public Health', 'Social Science', 'Sociology', 'Software Engineering', 'Statistics',
                'Textile Engineering', 'Urban & Regional Planning', 'Zoology', 'Others',
            ],
        ];
        $boards = ['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh', 'Cumilla', 'Dinajpur', 'Jessore', 'Madrasah', 'Technical', 'BOU', 'Others'];

        foreach ($titles as $code => $items) {
            foreach ($items as $index => $name) {
                DB::table('education_degree_titles')->updateOrInsert(
                    ['required_degree_level_id' => $levelIds[$code], 'name' => $name],
                    ['sort_order' => $index + 1, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                );
            }
        }

        foreach ($majors as $code => $items) {
            foreach ($items as $index => $name) {
                DB::table('education_major_groups')->updateOrInsert(
                    ['required_degree_level_id' => $levelIds[$code], 'name' => $name],
                    ['is_custom' => false, 'sort_order' => $index + 1, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now]
                );
            }
        }

        foreach ($boards as $index => $name) {
            DB::table('education_boards')->updateOrInsert(
                ['name' => $name],
                ['sort_order' => $index + 1, 'is_active' => true, 'updated_at' => $now, 'created_at' => $now]
            );
        }
    }
};
