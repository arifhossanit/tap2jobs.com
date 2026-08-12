@extends('candidate.layouts.app')
@section('title')
    Candidate FAQ | {{ __('web.register_menu.candidate') }} {{ __('web.web_home.helpful_resources') }}
@endsection

@section('page_css')
<style>
.candidate-faq-hero {
    background: linear-gradient(135deg, #d81b60 0%, #c2185b 50%, #ad1457 100%);
    padding: 60px 0 90px;
    color: #ffffff;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.candidate-faq-hero::after {
    content: '';
    position: absolute;
    bottom: -30px;
    left: 0;
    width: 100%;
    height: 60px;
    background: #f8f9fa;
    border-radius: 50% 50% 0 0 / 100% 100% 0 0;
}
.candidate-faq-hero h1 {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #ffffff;
}
.candidate-faq-search-box {
    max-width: 580px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}
.candidate-faq-search-box .input-group {
    background: #ffffff;
    border-radius: 8px;
    padding: 4px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}
.candidate-faq-search-box input {
    border: none;
    outline: none;
    padding: 12px 18px;
    font-size: 15px;
    border-radius: 8px 0 0 8px;
}
.candidate-faq-search-box input:focus {
    box-shadow: none;
}
.candidate-faq-search-box button {
    background: #00e676;
    border: none;
    color: #004d40;
    font-weight: bold;
    padding: 0 24px;
    border-radius: 6px;
    transition: all 0.2s ease-in-out;
}
.candidate-faq-search-box button:hover {
    background: #00c853;
    color: #ffffff;
}

.candidate-faq-container {
    max-width: 1080px;
    margin: -50px auto 60px;
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    padding: 40px 35px;
    position: relative;
    z-index: 3;
}

.candidate-faq-title {
    color: #8e24aa;
    font-weight: 700;
    font-size: 22px;
    text-align: center;
    margin-bottom: 35px;
}

/* Feature Cards Grid */
.feature-card-item {
    background: #ffffff;
    border: 1px solid #f3e5f5;
    border-radius: 12px;
    padding: 22px 12px;
    text-align: center;
    cursor: pointer;
    transition: all 0.25s ease-in-out;
    box-shadow: 0 2px 8px rgba(142, 36, 170, 0.04);
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.feature-card-item:hover, .feature-card-item.active {
    border-color: #ab47bc;
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(171, 71, 188, 0.18);
    background: #fcf4fd;
}
.feature-card-item i {
    font-size: 32px;
    color: #ab47bc;
    margin-bottom: 12px;
    transition: transform 0.2s ease;
}
.feature-card-item:hover i {
    transform: scale(1.1);
}
.feature-card-item span {
    font-size: 13px;
    font-weight: 600;
    color: #6a1b9a;
    line-height: 1.3;
}

/* Box Panels */
.faq-bordered-box {
    border: 1.5px solid #e1bee7;
    border-radius: 12px;
    padding: 28px;
    background: #fdfafe;
    margin-top: 35px;
}
.faq-bordered-box h5 {
    color: #4a148c;
    font-weight: 700;
    font-size: 16px;
    text-align: center;
    margin-bottom: 22px;
}
.faq-link-item {
    color: #0277bd;
    font-size: 13.5px;
    text-decoration: underline;
    display: block;
    margin-bottom: 14px;
    transition: color 0.2s ease;
    cursor: pointer;
}
.faq-link-item:hover {
    color: #ad1457;
}

/* Promo Banner Cards */
.candidate-promo-card {
    background: #f3fbf6;
    border: 1px solid #c8e6c9;
    border-radius: 12px;
    padding: 24px;
    height: 100%;
}
.candidate-promo-card.pro-card {
    background: #fbf5fc;
    border-color: #e1bee7;
}
.candidate-promo-card h4 {
    font-size: 18px;
    font-weight: 700;
    color: #2e7d32;
    margin-bottom: 10px;
}
.candidate-promo-card.pro-card h4 {
    color: #7b1fa2;
}
.candidate-promo-card p {
    font-size: 13px;
    color: #555;
    margin: 0;
    line-height: 1.5;
}

/* Accordion Modal & Answers */
.faq-answer-card {
    background: #ffffff;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    margin-bottom: 15px;
    overflow: hidden;
}
.faq-answer-header {
    background: #f8f9fa;
    padding: 16px 20px;
    font-weight: 600;
    color: #333;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.faq-answer-body {
    padding: 20px;
    color: #555;
    font-size: 14px;
    line-height: 1.6;
    border-top: 1px solid #eeeeee;
}
</style>
@endsection

@section('content')
<main class="candidate-faq-page">
    <!-- Hero Banner -->
    <section class="candidate-faq-hero">
        <div class="">
            <h1>Frequently Asked Questions</h1>
            <div class="candidate-faq-search-box">
                <div class="input-group">
                    <input type="text" id="faqSearchInput" class="form-control" placeholder="Type question here...">
                    <button class="btn" type="button" id="faqSearchBtn">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Container -->
    <div class="container">
        <div class="candidate-faq-container">
            <h2 class="candidate-faq-title">Choose Feature</h2>

            <!-- 16 Feature Grid -->
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 g-3 mb-4">
                <div class="col">
                    <div class="feature-card-item" data-category="about">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Know about Tap2Jobs</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Search</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="apply">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Apply</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="profile">
                        <i class="fa-solid fa-address-card"></i>
                        <span>Tap2Jobs Profile</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="invitation">
                        <i class="fa-solid fa-envelope-open-text"></i>
                        <span>Invitation</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="settings">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Account Settings</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="videocv">
                        <i class="fa-solid fa-file-video"></i>
                        <span>Video CV</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="videointerview">
                        <i class="fa-solid fa-display"></i>
                        <span>Video Interview</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="onlinetest">
                        <i class="fa-solid fa-laptop-code"></i>
                        <span>Online test</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="pro">
                        <i class="fa-solid fa-award"></i>
                        <span>Tap2Jobs pro</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="points">
                        <i class="fa-solid fa-coins"></i>
                        <span>My Points</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="liveinterview">
                        <i class="fa-solid fa-users-rectangle"></i>
                        <span>Live Interview</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="sms">
                        <i class="fa-solid fa-comment-sms"></i>
                        <span>SMS Job Alert</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="messages">
                        <i class="fa-solid fa-comments"></i>
                        <span>Messages</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="notifications">
                        <i class="fa-solid fa-bell"></i>
                        <span>Notifications</span>
                    </div>
                </div>
                <div class="col">
                    <div class="feature-card-item" data-category="others">
                        <i class="fa-solid fa-grip"></i>
                        <span>Others</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Answer Display Container -->
            <div id="faqAnswerDisplaySection" class="mt-4 d-none">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 id="faqSelectedCategoryTitle" class="fw-bold text-primary mb-0">Questions & Answers</h4>
                    <button id="faqResetFilterBtn" class="btn btn-sm btn-outline-secondary">Show All</button>
                </div>
                <div id="faqAccordionContainer" class="accordion mb-4">
                    <!-- Dynamic Items Injected via JS -->
                </div>
            </div>

            <!-- Suggested for you Box -->
            <div class="faq-bordered-box">
                <h5>Suggested for you</h5>
                <div class="row">
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="How to add Experience to your Resume?">How to add Experience to your Resume?</a>
                        <a class="faq-link-item" data-question="How do I update my Area of Expertise?">How do I update my Area of Expertise?</a>
                        <a class="faq-link-item" data-question="If I lost my User ID/Password how can I retrieve those?">If I lost my User ID/Password how can I retrieve those?</a>
                    </div>
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="What is SMS Job Alert?">What is SMS Job Alert?</a>
                        <a class="faq-link-item" data-question="What is Video CV?">What is Video CV?</a>
                        <a class="faq-link-item" data-question="What is Customized CV?">What is Customized CV?</a>
                    </div>
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="What is Application Boosting?">What is Application Boosting?</a>
                        <a class="faq-link-item" data-question="How Matching Percentage works?">How Matching Percentage works?</a>
                        <a class="faq-link-item" data-question="What is Application Insight?">What is Application Insight?</a>
                    </div>
                </div>
            </div>

            <!-- Popular searches Box -->
            <div class="faq-bordered-box">
                <h5>Popular searches</h5>
                <div class="row">
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="How to create account?">How to create account?</a>
                        <a class="faq-link-item" data-question="What is Keyword search?">What is Keyword search?</a>
                        <a class="faq-link-item" data-question="Can I change my User ID?">Can I change my User ID?</a>
                    </div>
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="What is Personality Test by Voice?">What is Personality Test by Voice?</a>
                        <a class="faq-link-item" data-question="What is Tap2Jobs Pro?">What is Tap2Jobs Pro?</a>
                        <a class="faq-link-item" data-question="Where can I get information about interview invitation?">Where can I get information about interview invitation?</a>
                    </div>
                    <div class="col-md-4">
                        <a class="faq-link-item" data-question="How to participate in Online Test?">How to participate in Online Test?</a>
                        <a class="faq-link-item" data-question="How will I get informed if an employer views my resume?">How will I get informed if an employer views my resume?</a>
                        <a class="faq-link-item" data-question="How can I apply to a job?">How can I apply to a job?</a>
                    </div>
                </div>
            </div>

            <!-- Bottom Promos -->
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="candidate-promo-card pro-card">
                        <h4>Subscribe to Tap2Jobs Pro</h4>
                        <p>Tap2Jobs Pro gives you exclusive opportunities and features to help you find a job and increase your chances of getting more interview calls.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="candidate-promo-card">
                        <h4>Post job for personal hiring</h4>
                        <p>Publish job for personal needs, receive qualified applicants, review their CVs, and hire the ideal candidate effortlessly.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page_scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const faqData = [
        {
            category: 'about',
            question: 'How to create account?',
            answer: 'Click on the Register button at the top of the website, choose Candidate, fill in your Name, Phone, Email, Password, and click Create Account.'
        },
        {
            category: 'about',
            question: 'Can I change my User ID?',
            answer: 'Yes, you can update your email or username anytime from your Profile Settings panel.'
        },
        {
            category: 'search',
            question: 'What is Keyword search?',
            answer: 'Keyword search lets you type job titles, skills, or company names to find relevant job openings matching your interest.'
        },
        {
            category: 'apply',
            question: 'How can I apply to a job?',
            answer: 'Browse any job listing, click on the Apply button, select your resume option, and submit your application to the employer.'
        },
        {
            category: 'profile',
            question: 'How to add Experience to your Resume?',
            answer: 'Go to your Profile Dashboard, click Edit Experience section, enter your company name, designation, start and end dates, and save.'
        },
        {
            category: 'profile',
            question: 'How do I update my Area of Expertise?',
            answer: 'In your Profile settings, navigate to Skills & Expertise and add or update your functional areas.'
        },
        {
            category: 'invitation',
            question: 'Where can I get information about interview invitation?',
            answer: 'All interview invitations will appear in your Candidate Dashboard under the Invitations tab, and you will also receive email/SMS notifications.'
        },
        {
            category: 'settings',
            question: 'If I lost my User ID/Password how can I retrieve those?',
            answer: 'Click Forgot Password on the login page, enter your registered email address, and follow the link sent to your inbox to reset your password.'
        },
        {
            category: 'videocv',
            question: 'What is Video CV?',
            answer: 'A Video CV allows you to upload a short 1-minute video introducing your skills and experience to employers.'
        },
        {
            category: 'videointerview',
            question: 'What is Video Interview?',
            answer: 'Employers can invite you for an online video interview directly through our platform.'
        },
        {
            category: 'onlinetest',
            question: 'How to participate in Online Test?',
            answer: 'If an employer requires an online skill test, a link will be enabled in your Candidate Dashboard under the Online Tests section.'
        },
        {
            category: 'pro',
            question: 'What is Tap2Jobs Pro?',
            answer: 'Tap2Jobs Pro is a premium membership offering boosted application visibility, priority matching, and enhanced insights.'
        },
        {
            category: 'points',
            question: 'What is My Points?',
            answer: 'My Points are reward points earned by completing your profile, which can be redeemed for premium features.'
        },
        {
            category: 'sms',
            question: 'What is SMS Job Alert?',
            answer: 'SMS Job Alerts notify you instantly on your mobile phone whenever new jobs matching your preference are published.'
        },
        {
            category: 'messages',
            question: 'How will I get informed if an employer views my resume?',
            answer: 'You will receive an in-app notification and message whenever an employer views or shortlists your resume.'
        },
        {
            category: 'notifications',
            question: 'What is Application Insight?',
            answer: 'Application Insight gives you real-time analytics on how many candidates applied and where your application stands.'
        }
    ];

    const displaySection = document.getElementById('faqAnswerDisplaySection');
    const accordionContainer = document.getElementById('faqAccordionContainer');
    const categoryTitle = document.getElementById('faqSelectedCategoryTitle');
    const searchInput = document.getElementById('faqSearchInput');
    const searchBtn = document.getElementById('faqSearchBtn');
    const resetBtn = document.getElementById('faqResetFilterBtn');
    const featureCards = document.querySelectorAll('.feature-card-item');
    const linkItems = document.querySelectorAll('.faq-link-item');

    function renderFaqs(items, titleText) {
        if (!items || items.length === 0) {
            accordionContainer.innerHTML = '<div class="alert alert-info">No matching questions found. Try searching another keyword.</div>';
        } else {
            let html = '';
            items.forEach((item, index) => {
                const itemId = 'faqItem_' + index;
                html += `
                    <div class="accordion-item faq-answer-card">
                        <h2 class="accordion-header">
                            <button class="accordion-button ${index !== 0 ? 'collapsed' : ''}" type="button" data-bs-toggle="collapse" data-bs-target="#${itemId}">
                                ${item.question}
                            </button>
                        </h2>
                        <div id="${itemId}" class="accordion-collapse collapse ${index === 0 ? 'show' : ''}" data-bs-parent="#faqAccordionContainer">
                            <div class="accordion-body faq-answer-body">
                                ${item.answer}
                            </div>
                        </div>
                    </div>
                `;
            });
            accordionContainer.innerHTML = html;
        }
        categoryTitle.textContent = titleText || 'Questions & Answers';
        displaySection.classList.remove('d-none');
        displaySection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    featureCards.forEach(card => {
        card.addEventListener('click', function () {
            featureCards.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const cat = this.getAttribute('data-category');
            const catName = this.querySelector('span').textContent;
            const filtered = faqData.filter(f => f.category === cat);
            renderFaqs(filtered.length > 0 ? filtered : faqData, catName + ' - FAQs');
        });
    });

    linkItems.forEach(link => {
        link.addEventListener('click', function () {
            const qText = this.getAttribute('data-question');
            const found = faqData.filter(f => f.question.toLowerCase().includes(qText.toLowerCase()));
            renderFaqs(found.length > 0 ? found : [{ question: qText, answer: 'Detailed instructions are available in your Candidate Profile dashboard.' }], qText);
        });
    });

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();
        if (!query) return;
        const results = faqData.filter(f => f.question.toLowerCase().includes(query) || f.answer.toLowerCase().includes(query));
        renderFaqs(results, 'Search Results for: "' + query + '"');
    }

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keyup', function (e) {
        if (e.key === 'Enter') performSearch();
    });

    resetBtn.addEventListener('click', function () {
        featureCards.forEach(c => c.classList.remove('active'));
        searchInput.value = '';
        renderFaqs(faqData, 'All Candidate FAQs');
    });
});
</script>
@endsection
