<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('required_degree_levels') || ! Schema::hasTable('education_major_groups')) {
            return;
        }

        $now = now();
        $levelIds = DB::table('required_degree_levels')
            ->whereIn('code', ['secondary', 'higher_secondary', 'diploma', 'bachelor', 'masters', 'phd'])
            ->pluck('id', 'code');

        $majors = [
            'secondary' => ['General', 'Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
            'higher_secondary' => ['General', 'Science', 'Business Studies', 'Humanities', 'Vocational', 'Others'],
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

        foreach ($majors as $code => $items) {
            if (! isset($levelIds[$code])) {
                continue;
            }

            foreach ($items as $index => $name) {
                DB::table('education_major_groups')->updateOrInsert(
                    ['required_degree_level_id' => $levelIds[$code], 'name' => $name],
                    [
                        'is_custom' => false,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                        'updated_at' => $now,
                        'created_at' => $now,
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        //
    }
};
