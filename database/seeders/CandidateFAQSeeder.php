<?php

namespace Database\Seeders;

use App\Models\FAQ;
use App\Models\FAQCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CandidateFAQSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Questions',
                'icon' => 'fa-solid fa-gear fa-fw',
                'faqs' => [
                    ['How do I create a candidate account?', 'Click Candidate Register, fill in your name, email, phone number, and password, then submit the form to create your account.'],
                    ['Can I update my login email or phone number?', 'Yes. After logging in, open your profile settings and update your email or phone number from the account information section.'],
                    ['What should I do if I forget my password?', 'Use the Forgot Password link on the login page and follow the reset instructions sent to your registered email address.'],
                ],
            ],
            [
                'name' => 'Job Search',
                'icon' => 'fa-solid fa-magnifying-glass fa-fw',
                'faqs' => [
                    ['How do I search for jobs?', 'Use the search page to filter jobs by keyword, location, category, job type, career level, and other available filters.'],
                    ['What is keyword search?', 'Keyword search helps you find jobs by matching words from job titles, skills, company names, or job descriptions.'],
                    ['How do I save a job for later?', 'Open a job post and click the favorite or bookmark button. Saved jobs will appear in your favorite jobs list.'],
                ],
            ],
            [
                'name' => 'Apply Jobs',
                'icon' => 'fa-solid fa-file-export fa-fw',
                'faqs' => [
                    ['How can I apply to a job?', 'Open the job details page, click Apply, choose your CV or resume option, and submit your application.'],
                    ['Can I track my job applications?', 'Yes. Your candidate dashboard shows applied jobs, application status, interview schedules, and related updates.'],
                    ['Can I apply again after withdrawing an application?', 'If the job is still open and the employer allows applications, you can submit a new application from the job details page.'],
                ],
            ],
            [
                'name' => 'Candidate Profile',
                'icon' => 'fa-solid fa-address-card fa-fw',
                'faqs' => [
                    ['How do I complete my candidate profile?', 'Go to your profile dashboard and add your personal details, education, experience, skills, career information, and resume details.'],
                    ['How do I add experience to my resume?', 'Open the experience section in your candidate profile, add your company, designation, dates, responsibilities, and save the record.'],
                    ['How do I update my area of expertise?', 'Edit your profile skills or expertise section and select the areas that best match your work experience and career interest.'],
                ],
            ],
            [
                'name' => 'Interview',
                'icon' => 'fa-solid fa-users fa-fw',
                'faqs' => [
                    ['Where can I see interview invitations?', 'Interview invitations appear in your candidate dashboard. You may also receive email or platform notifications.'],
                    ['How do I book an interview schedule?', 'If an employer provides available slots, open the invitation or applied job details and choose a suitable time slot.'],
                    ['What happens after an employer shortlists me?', 'You will receive a notification, and the employer may invite you for an interview, online test, or next hiring step.'],
                ],
            ],
            [
                'name' => 'Candidate Tools',
                'icon' => 'fa-solid fa-screwdriver-wrench fa-fw',
                'faqs' => [
                    ['What is Video CV?', 'Video CV lets you introduce yourself to employers with a short video presentation alongside your regular resume.'],
                    ['How do I participate in an online test?', 'When an employer assigns an online test, open the test link from your dashboard and complete it within the given time.'],
                    ['Where can I see messages from employers?', 'Employer messages and important updates are available in your candidate dashboard notifications or messages area.'],
                ],
            ],
            [
                'name' => 'Tap2Jobs Pro',
                'icon' => 'fa-solid fa-award fa-fw',
                'faqs' => [
                    ['What is Tap2Jobs Pro?', 'Tap2Jobs Pro is a premium feature set designed to improve candidate visibility and provide enhanced job search benefits.'],
                    ['What is application boosting?', 'Application boosting helps your application get stronger visibility to employers where the feature is available.'],
                    ['What is application insight?', 'Application insight gives useful information about your application activity and employer interaction when supported by the platform.'],
                ],
            ],
            [
                'name' => 'Support',
                'icon' => 'fa-solid fa-headset fa-fw',
                'faqs' => [
                    ['How do I contact support?', 'Use the Contact Us page or available support channels to send your issue with your registered email or phone number.'],
                    ['How do I report a problem with my account?', 'Contact support with the issue details, screenshots if available, and your candidate account information.'],
                    ['Can I delete my candidate account?', 'Account deletion requests should be sent to support. The team will review and guide you through the process.'],
                ],
            ],
        ];

        foreach ($categories as $categoryIndex => $categoryData) {
            $category = FAQCategory::updateOrCreate(
                [
                    'slug' => Str::slug($categoryData['name']),
                    'audience' => 'candidate',
                ],
                [
                    'name' => $categoryData['name'],
                    'icon' => $categoryData['icon'],
                    'sort_order' => $categoryIndex + 1,
                ]
            );

            foreach ($categoryData['faqs'] as $faqIndex => [$title, $description]) {
                FAQ::updateOrCreate(
                    ['title' => $title],
                    [
                        'faq_category_id' => $category->id,
                        'description' => $description,
                        'sort_order' => $faqIndex + 1,
                    ]
                );
            }
        }
    }
}
