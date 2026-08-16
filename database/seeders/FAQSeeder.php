<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FAQCategory;
use App\Models\FAQ;

class FAQSeeder extends Seeder
{
    public function run(): void
    {
        FAQ::query()->delete();
        FAQCategory::query()->delete();

        $categories = array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Know about Tap2Jobs',
    'slug' => 'general-questions',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-gear fa-fw',
    'sort_order' => 2,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 1,
        'faq_category_id' => 1,
        'title' => 'How do I create a candidate account?',
        'description' => 'Click Candidate Register, fill in your name, email, phone number, and password, then submit the form to create your account.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 2,
        'faq_category_id' => 1,
        'title' => 'Can I update my login email or phone number?',
        'description' => 'Yes. After logging in, open your profile settings and update your email or phone number from the account information section.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 3,
        'faq_category_id' => 1,
        'title' => 'What should I do if I forget my password?',
        'description' => 'Use the Forgot Password link on the login page and follow the reset instructions sent to your registered email address.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Search',
    'slug' => 'job-search',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-magnifying-glass fa-fw',
    'sort_order' => 11,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 4,
        'faq_category_id' => 2,
        'title' => 'How do I search for jobs?',
        'description' => 'Use the search page to filter jobs by keyword, location, category, job type, career level, and other available filters.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 5,
        'faq_category_id' => 2,
        'title' => 'What is keyword search?',
        'description' => 'Keyword search helps you find jobs by matching words from job titles, skills, company names, or job descriptions.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 6,
        'faq_category_id' => 2,
        'title' => 'How do I save a job for later?',
        'description' => 'Open a job post and click the favorite or bookmark button. Saved jobs will appear in your favorite jobs list.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'Apply',
    'slug' => 'apply-jobs',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-file-export fa-fw',
    'sort_order' => 12,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 7,
        'faq_category_id' => 3,
        'title' => 'How can I apply to a job?',
        'description' => 'Open the job details page, click Apply, choose your CV or resume option, and submit your application.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 8,
        'faq_category_id' => 3,
        'title' => 'Can I track my job applications?',
        'description' => 'Yes. Your candidate dashboard shows applied jobs, application status, interview schedules, and related updates.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 9,
        'faq_category_id' => 3,
        'title' => 'Can I apply again after withdrawing an application?',
        'description' => 'If the job is still open and the employer allows applications, you can submit a new application from the job details page.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'Tap2Jobs Profile',
    'slug' => 'candidate-profile',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-address-card fa-fw',
    'sort_order' => 13,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 10,
        'faq_category_id' => 4,
        'title' => 'How do I complete my candidate profile?',
        'description' => 'Go to your profile dashboard and add your personal details, education, experience, skills, career information, and resume details.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 11,
        'faq_category_id' => 4,
        'title' => 'How do I add experience to my resume?',
        'description' => 'Open the experience section in your candidate profile, add your company, designation, dates, responsibilities, and save the record.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 12,
        'faq_category_id' => 4,
        'title' => 'How do I update my area of expertise?',
        'description' => 'Edit your profile skills or expertise section and select the areas that best match your work experience and career interest.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  4 => 
  array (
    'id' => 5,
    'name' => 'Invitation',
    'slug' => 'interview',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-users fa-fw',
    'sort_order' => 14,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 13,
        'faq_category_id' => 5,
        'title' => 'Where can I see interview invitations?',
        'description' => 'Interview invitations appear in your candidate dashboard. You may also receive email or platform notifications.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 14,
        'faq_category_id' => 5,
        'title' => 'How do I book an interview schedule?',
        'description' => 'If an employer provides available slots, open the invitation or applied job details and choose a suitable time slot.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 15,
        'faq_category_id' => 5,
        'title' => 'What happens after an employer shortlists me?',
        'description' => 'You will receive a notification, and the employer may invite you for an interview, online test, or next hiring step.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  5 => 
  array (
    'id' => 6,
    'name' => 'Account Setting',
    'slug' => 'candidate-tools',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-gear',
    'sort_order' => 15,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 16,
        'faq_category_id' => 6,
        'title' => 'What is Video CV?',
        'description' => 'Video CV lets you introduce yourself to employers with a short video presentation alongside your regular resume.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 17,
        'faq_category_id' => 6,
        'title' => 'How do I participate in an online test?',
        'description' => 'When an employer assigns an online test, open the test link from your dashboard and complete it within the given time.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 18,
        'faq_category_id' => 6,
        'title' => 'Where can I see messages from employers?',
        'description' => 'Employer messages and important updates are available in your candidate dashboard notifications or messages area.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  6 => 
  array (
    'id' => 7,
    'name' => 'Video CV',
    'slug' => 'tap2jobs-pro',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-video',
    'sort_order' => 16,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  7 => 
  array (
    'id' => 8,
    'name' => 'Support',
    'slug' => 'support',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-headset fa-fw',
    'sort_order' => 18,
    'is_active' => true,
    'created_at' => '2026-08-13T09:14:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 22,
        'faq_category_id' => 8,
        'title' => 'How do I contact support?',
        'description' => 'Use the Contact Us page or available support channels to send your issue with your registered email or phone number.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      1 => 
      array (
        'id' => 23,
        'faq_category_id' => 8,
        'title' => 'How do I report a problem with my account?',
        'description' => 'Contact support with the issue details, screenshots if available, and your candidate account information.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
      2 => 
      array (
        'id' => 24,
        'faq_category_id' => 8,
        'title' => 'Can I delete my candidate account?',
        'description' => 'Account deletion requests should be sent to support. The team will review and guide you through the process.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:14:28.000000Z',
        'updated_at' => '2026-08-13T09:14:28.000000Z',
      ),
    ),
  ),
  8 => 
  array (
    'id' => 9,
    'name' => 'Know about Bdjobs',
    'slug' => 'employer-general-questions',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-gear fa-fw',
    'sort_order' => 17,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 25,
        'faq_category_id' => 9,
        'title' => 'How do I create an employer account?',
        'description' => 'Click Employer Register, fill in your company and recruiter information, then submit the form to create your employer account.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 26,
        'faq_category_id' => 9,
        'title' => 'Can I update my company information?',
        'description' => 'Yes. After logging in, open your company profile and update company details, contact information, address, industry, and branding information.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 27,
        'faq_category_id' => 9,
        'title' => 'What should I do if I forget my employer password?',
        'description' => 'Use the Forgot Password link from the employer login page and follow the reset instructions sent to your registered email address.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  9 => 
  array (
    'id' => 10,
    'name' => 'Job Posting',
    'slug' => 'employer-post-jobs',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-briefcase fa-fw',
    'sort_order' => 26,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 28,
        'faq_category_id' => 10,
        'title' => 'How do I post a job?',
        'description' => 'Log in to your employer panel, open the jobs section, click Post Job, fill in the job details, and submit it for publishing or review.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 29,
        'faq_category_id' => 10,
        'title' => 'Can I save a job as draft?',
        'description' => 'Yes. If the draft flow is enabled, you can save incomplete job posts and complete them later from your employer dashboard.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 30,
        'faq_category_id' => 10,
        'title' => 'Can I edit a published job?',
        'description' => 'You can edit job details from the employer jobs list. Some changes may require admin review depending on platform settings.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  10 => 
  array (
    'id' => 11,
    'name' => 'Applicant Process',
    'slug' => 'employer-applicants',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-users fa-fw',
    'sort_order' => 28,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 31,
        'faq_category_id' => 11,
        'title' => 'Where can I see applicants?',
        'description' => 'Open your employer dashboard or job applications area to see candidates who applied to your posted jobs.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 32,
        'faq_category_id' => 11,
        'title' => 'How do I shortlist a candidate?',
        'description' => 'Open an application, review the candidate profile or CV, and move the candidate to the appropriate hiring stage.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 33,
        'faq_category_id' => 11,
        'title' => 'Can I download candidate resumes?',
        'description' => 'If resume access is enabled for your account and job, you can view or download candidate resumes from the application details.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  11 => 
  array (
    'id' => 12,
    'name' => 'Candidate Search',
    'slug' => 'employer-candidate-search',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-magnifying-glass fa-fw',
    'sort_order' => 29,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 34,
        'faq_category_id' => 12,
        'title' => 'How do I search candidates?',
        'description' => 'Use candidate search filters such as keyword, location, skills, experience, career level, and other available profile criteria.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 35,
        'faq_category_id' => 12,
        'title' => 'Can I save favorite candidates?',
        'description' => 'Yes. You can save candidates to your favorite list so you can review or contact them later.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 36,
        'faq_category_id' => 12,
        'title' => 'Why are some candidate details hidden?',
        'description' => 'Some details may be limited based on privacy settings, subscription rules, or platform access permissions.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  12 => 
  array (
    'id' => 13,
    'name' => 'Interview',
    'slug' => 'employer-interview',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-calendar-check fa-fw',
    'sort_order' => 30,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 37,
        'faq_category_id' => 13,
        'title' => 'How do I invite a candidate for interview?',
        'description' => 'Open the candidate application, choose the interview or schedule option, add the details, and send the invitation.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 38,
        'faq_category_id' => 13,
        'title' => 'Can I create interview time slots?',
        'description' => 'Yes. You can create available schedule slots for candidates when the interview scheduling feature is enabled.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 39,
        'faq_category_id' => 13,
        'title' => 'How will candidates receive interview updates?',
        'description' => 'Candidates receive interview updates through their dashboard and may also receive email or platform notifications.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  13 => 
  array (
    'id' => 14,
    'name' => 'Company Profile',
    'slug' => 'employer-company-profile',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-building fa-fw',
    'sort_order' => 31,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 40,
        'faq_category_id' => 14,
        'title' => 'Why should I complete my company profile?',
        'description' => 'A complete company profile builds trust with candidates and helps them understand your organization before applying.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 41,
        'faq_category_id' => 14,
        'title' => 'Can I upload a company logo?',
        'description' => 'Yes. Open company profile settings and upload or replace your logo and company images from the branding area.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 42,
        'faq_category_id' => 14,
        'title' => 'How do I update company address and contact info?',
        'description' => 'Edit the company profile fields and save the updated billing, office, and recruiter contact information.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  14 => 
  array (
    'id' => 15,
    'name' => 'Credit System',
    'slug' => 'employer-billing',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-credit-card fa-fw',
    'sort_order' => 32,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 43,
        'faq_category_id' => 15,
        'title' => 'Where can I see invoices?',
        'description' => 'Invoices and transaction details are available in your employer billing or transactions section.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 44,
        'faq_category_id' => 15,
        'title' => 'How do I buy a plan?',
        'description' => 'Choose a suitable subscription or job posting plan, select a payment method, and complete checkout from the employer panel.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 45,
        'faq_category_id' => 15,
        'title' => 'Can I use manual payment?',
        'description' => 'If manual payment is enabled, select the manual payment option and follow the instructions shown during checkout.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  15 => 
  array (
    'id' => 16,
    'name' => 'Support',
    'slug' => 'employer-support',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-headset fa-fw',
    'sort_order' => 33,
    'is_active' => true,
    'created_at' => '2026-08-13T09:41:20.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
      0 => 
      array (
        'id' => 46,
        'faq_category_id' => 16,
        'title' => 'How do I contact employer support?',
        'description' => 'Use the Contact Us page or available support channel and include your company name, registered email, and issue details.',
        'sort_order' => 1,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      1 => 
      array (
        'id' => 47,
        'faq_category_id' => 16,
        'title' => 'How do I report a technical issue?',
        'description' => 'Send support the issue details, screenshots if available, your browser/device information, and the page where the issue happened.',
        'sort_order' => 2,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
      2 => 
      array (
        'id' => 48,
        'faq_category_id' => 16,
        'title' => 'Can support help me improve a job post?',
        'description' => 'Support can guide you on required fields and posting rules, but final job content should reflect your actual hiring needs.',
        'sort_order' => 3,
        'created_at' => '2026-08-13T09:41:20.000000Z',
        'updated_at' => '2026-08-13T09:41:20.000000Z',
      ),
    ),
  ),
  16 => 
  array (
    'id' => 18,
    'name' => 'Video Interview',
    'slug' => 'video-interview',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-podcast',
    'sort_order' => 10,
    'is_active' => true,
    'created_at' => '2026-08-16T04:56:59.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  17 => 
  array (
    'id' => 19,
    'name' => 'Online Test',
    'slug' => 'online-test',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-clipboard-list',
    'sort_order' => 6,
    'is_active' => true,
    'created_at' => '2026-08-16T04:59:46.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  18 => 
  array (
    'id' => 20,
    'name' => 'My Points',
    'slug' => 'my-points',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-coins',
    'sort_order' => 5,
    'is_active' => true,
    'created_at' => '2026-08-16T05:02:22.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  19 => 
  array (
    'id' => 21,
    'name' => 'Live Interview',
    'slug' => 'live-interview',
    'audience' => 'candidate',
    'icon' => 'fa-brands fa-twitch',
    'sort_order' => 3,
    'is_active' => true,
    'created_at' => '2026-08-16T05:05:21.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  20 => 
  array (
    'id' => 22,
    'name' => 'SMS Job Alert',
    'slug' => 'sms-job-alert',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-comment-sms',
    'sort_order' => 8,
    'is_active' => true,
    'created_at' => '2026-08-16T05:07:19.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  21 => 
  array (
    'id' => 23,
    'name' => 'Message',
    'slug' => 'message',
    'audience' => 'candidate',
    'icon' => 'fa-regular fa-message',
    'sort_order' => 4,
    'is_active' => true,
    'created_at' => '2026-08-16T05:09:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  22 => 
  array (
    'id' => 24,
    'name' => 'Notification',
    'slug' => 'notification',
    'audience' => 'candidate',
    'icon' => 'fa-regular fa-bell',
    'sort_order' => 9,
    'is_active' => true,
    'created_at' => '2026-08-16T05:11:18.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  23 => 
  array (
    'id' => 25,
    'name' => 'Others',
    'slug' => 'others',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-border-none',
    'sort_order' => 7,
    'is_active' => true,
    'created_at' => '2026-08-16T05:17:18.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  24 => 
  array (
    'id' => 26,
    'name' => 'Job Management',
    'slug' => 'job-management',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-briefcase',
    'sort_order' => 27,
    'is_active' => true,
    'created_at' => '2026-08-16T05:22:27.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  25 => 
  array (
    'id' => 27,
    'name' => 'Credit System',
    'slug' => 'credit-system',
    'audience' => 'candidate',
    'icon' => 'fa-solid fa-credit-card',
    'sort_order' => 1,
    'is_active' => true,
    'created_at' => '2026-08-16T05:29:40.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  26 => 
  array (
    'id' => 30,
    'name' => 'AI Assessment',
    'slug' => 'ai-assessment-1786858370',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-robot',
    'sort_order' => 19,
    'is_active' => true,
    'created_at' => '2026-08-16T05:32:50.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  27 => 
  array (
    'id' => 31,
    'name' => 'Video Resume',
    'slug' => 'video-resume',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-file-video',
    'sort_order' => 25,
    'is_active' => true,
    'created_at' => '2026-08-16T05:35:52.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  28 => 
  array (
    'id' => 32,
    'name' => 'Live Interview',
    'slug' => 'live-interview-1786858568',
    'audience' => 'employer',
    'icon' => 'fa-brands fa-twitch',
    'sort_order' => 21,
    'is_active' => true,
    'created_at' => '2026-08-16T05:36:08.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  29 => 
  array (
    'id' => 33,
    'name' => 'Video Interview',
    'slug' => 'video-interview-1786858619',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-podcast',
    'sort_order' => 24,
    'is_active' => true,
    'created_at' => '2026-08-16T05:36:59.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  30 => 
  array (
    'id' => 34,
    'name' => 'CV Bank',
    'slug' => 'cv-bank',
    'audience' => 'employer',
    'icon' => 'fa-regular fa-file-lines',
    'sort_order' => 20,
    'is_active' => true,
    'created_at' => '2026-08-16T05:38:37.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  31 => 
  array (
    'id' => 35,
    'name' => 'Technical Issue',
    'slug' => 'technical-issue',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-screwdriver-wrench',
    'sort_order' => 23,
    'is_active' => true,
    'created_at' => '2026-08-16T05:39:28.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
  32 => 
  array (
    'id' => 36,
    'name' => 'Service Packages',
    'slug' => 'service-packages',
    'audience' => 'employer',
    'icon' => 'fa-solid fa-box-open',
    'sort_order' => 22,
    'is_active' => true,
    'created_at' => '2026-08-16T05:40:36.000000Z',
    'updated_at' => '2026-08-16T05:40:53.000000Z',
    'faqs' => 
    array (
    ),
  ),
);

        foreach ($categories as $category) {
            $cat = FAQCategory::create([
                'id' => $category['id'],
                'name' => $category['name'],
                'slug' => $category['slug'],
                'audience' => $category['audience'],
                'icon' => $category['icon'],
                'sort_order' => $category['sort_order'],
                'is_active' => $category['is_active'],
            ]);
            foreach ($category['faqs'] as $faq) {
                FAQ::create([
                    'faq_category_id' => $cat->id,
                    'title' => $faq['title'],
                    'description' => $faq['description'],
                    'sort_order' => $faq['sort_order'],
                ]);
            }
        }
    }
}
