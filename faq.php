<?php
$pageTitle = 'FAQ';
require_once 'includes/functions.php';
include 'includes/header.php';
?>

<section class="hero-section" style="padding: 3rem 0 2rem;">
    <div class="container">
        <div class="section-header mb-0">
            <h1 class="section-title">Frequently Asked Questions</h1>
            <p class="section-subtitle">Find answers to common questions about SmartLearn's platform, courses, payments, and more.</p>
        </div>
        <div class="search-bar mx-auto" style="max-width: 500px;">
            <i class="fas fa-search ms-3 text-muted"></i>
            <input type="text" id="faqSearch" class="form-control border-0 shadow-none" placeholder="Search FAQs..." onkeyup="filterFAQs(this.value)">
        </div>
    </div>
</section>

<section class="section-padding pt-0">
    <div class="container">
        <ul class="nav nav-pills justify-content-center mb-4 gap-2" id="faqTabs">
            <li class="nav-item"><button class="nav-link active" data-filter="all" onclick="switchFaqTab(this, 'all')">All</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="account" onclick="switchFaqTab(this, 'account')">Account</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="courses" onclick="switchFaqTab(this, 'courses')">Courses</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="payments" onclick="switchFaqTab(this, 'payments')">Payments</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="certificates" onclick="switchFaqTab(this, 'certificates')">Certificates</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="ai" onclick="switchFaqTab(this, 'ai')">AI Features</button></li>
            <li class="nav-item"><button class="nav-link" data-filter="technical" onclick="switchFaqTab(this, 'technical')">Technical</button></li>
        </ul>

        <div class="faq-section">
            <div class="accordion" id="faqAccordion">
                <!-- Account -->
                <div class="faq-item" data-category="account">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                How do I create a SmartLearn account?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Click the "Get Started Free" or "Register" button on the homepage. Fill in your name, email address, and create a password. You can also sign up using your Google or Facebook account for faster registration. Once registered, you'll have immediate access to all free courses and platform features.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                Can I delete my account?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes, you can delete your account anytime from your profile settings. Go to Account Settings &rarr; Delete Account. Please note that this action is permanent and will remove all your progress, certificates, and course access. We recommend downloading your certificates before deleting.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="account">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                How do I reset my password?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">On the login page, click "Forgot Password?" and enter your registered email address. We'll send you a password reset link. Follow the link to create a new password. For security, the reset link expires after 1 hour. If you don't receive the email, check your spam folder.</div>
                        </div>
                    </div>
                </div>

                <!-- Courses -->
                <div class="faq-item" data-category="courses">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                How do I enroll in a course?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Browse our course catalog and click on any course that interests you. On the course details page, click the "Enroll Now" button. Free courses are immediately accessible. For paid courses, you'll be guided through the payment process. After successful enrollment, the course will appear in your dashboard.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="courses">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                Are the courses self-paced?
                            </button>
                        </h2>
                        <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes, all SmartLearn courses are self-paced. You can learn at your own speed, on your own schedule. There are no deadlines or fixed class times. Once enrolled, you have lifetime access to the course materials, so you can revisit them anytime you need a refresher.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="courses">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                Do you offer courses for beginners?
                            </button>
                        </h2>
                        <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Absolutely! We offer courses for all skill levels: Beginner, Intermediate, and Advanced. Each course is clearly labeled with its level. Beginners can start with our foundational courses that assume no prior knowledge. Our AI learning assistant can also help bridge any knowledge gaps you may have.</div>
                        </div>
                    </div>
                </div>

                <!-- Payments -->
                <div class="faq-item" data-category="payments">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">
                                What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">We accept all major credit and debit cards (Visa, MasterCard, American Express, Discover), PayPal, and Apple Pay. In select regions, we also support local payment methods. All transactions are securely processed using industry-standard encryption. We never store your payment details.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="payments">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">
                                What is your refund policy?
                            </button>
                        </h2>
                        <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">We offer a 30-day money-back guarantee on all paid courses. If you're not satisfied with a course, you can request a refund within 30 days of purchase, no questions asked. To request a refund, go to your purchase history in account settings and click "Request Refund." Refunds are processed within 5-7 business days.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="payments">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9">
                                Are there any free courses available?
                            </button>
                        </h2>
                        <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes! SmartLearn offers a growing library of free courses across various categories. You can filter by "Free" in our course catalog to see all available free courses. Free courses include full access to video lessons, quizzes, and our AI learning assistant, but may not include certificates of completion.</div>
                        </div>
                    </div>
                </div>

                <!-- Certificates -->
                <div class="faq-item" data-category="certificates">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10">
                                How do I earn a certificate?
                            </button>
                        </h2>
                        <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">To earn a certificate of completion, you must complete all lessons, quizzes, and any required assignments in a paid course. Once you've achieved at least 80% overall progress, the certificate will become available for download from your dashboard. Certificates include a unique verification ID that employers can use to verify authenticity.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="certificates">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11">
                                Are certificates recognized by employers?
                            </button>
                        </h2>
                        <div id="faq11" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Yes, SmartLearn certificates are recognized by many employers and industry partners. Each certificate includes a unique verification code that employers can use to confirm its authenticity on our website. While certificates demonstrate your commitment and knowledge, we recommend checking with your specific employer about their recognition policies.</div>
                        </div>
                    </div>
                </div>

                <!-- AI Features -->
                <div class="faq-item" data-category="ai">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12">
                                How does the AI learning assistant work?
                            </button>
                        </h2>
                        <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">Our AI learning assistant is available 24/7 as a chatbot on the platform. You can ask it questions about course content, request explanations of concepts, get help with exercises, or receive personalized study recommendations. The AI is trained on our course content and can provide instant, contextual responses to help you learn more effectively.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="ai">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq13">
                                What is the AI study planner?
                            </button>
                        </h2>
                        <div id="faq13" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">The AI study planner creates a personalized study schedule based on your learning goals, available time, and preferred pace. It analyzes your course content, deadlines, and learning patterns to create an optimized study plan. You can adjust your preferences anytime, and the AI will recalculate the optimal schedule for you.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="ai">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14">
                                How does SmartLearn recommend courses?
                            </button>
                        </h2>
                        <div id="faq14" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">When you first join, our AI recommends courses based on your stated interests, goals, and skill level. As you learn, the system tracks your progress, quiz performance, and course completion patterns to refine its recommendations. The more you use SmartLearn, the more accurate and personalized your course recommendations become.</div>
                        </div>
                    </div>
                </div>

                <!-- Technical -->
                <div class="faq-item" data-category="technical">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15">
                                What devices and browsers are supported?
                            </button>
                        </h2>
                        <div id="faq15" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">SmartLearn works on all modern devices including desktops, laptops, tablets, and smartphones. We support the latest versions of Chrome, Firefox, Safari, and Edge. For the best experience, we recommend using a desktop or tablet with a stable internet connection and keeping your browser updated to the latest version.</div>
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-category="technical">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq16">
                                Having trouble loading videos or course content?
                            </button>
                        </h2>
                        <div id="faq16" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">If videos aren't loading, try these steps: 1) Check your internet connection, 2) Clear your browser cache and cookies, 3) Disable browser extensions that might block content, 4) Try a different browser, 5) Update your browser to the latest version. If problems persist, contact our support team with your browser details and error messages.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function switchFaqTab(btn, category) {
    document.querySelectorAll('#faqTabs .nav-link').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.faq-item').forEach(item => {
        item.style.display = (category === 'all' || item.dataset.category === category) ? '' : 'none';
    });
}

function filterFAQs(value) {
    const q = value.toLowerCase().trim();
    document.querySelectorAll('.faq-item').forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(q) ? '' : 'none';
    });
}
</script>

<?php include 'includes/footer.php'; ?>
