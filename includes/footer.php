<footer class="footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5>Smart<span style="color: #3b82f6;">Learn</span></h5>
                <p>Empowering learners worldwide with AI-powered education. Learn from expert instructors, track your progress, and achieve your goals.</p>
                <div class="social-links mt-3">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6">
                <h5>Quick Links</h5>
                <a href="index.php">Home</a>
                <a href="about.php">About Us</a>
                <a href="courses.php">Courses</a>
                <a href="contact.php">Contact</a>
                <a href="faq.php">FAQ</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>Popular Categories</h5>
                <a href="courses.php?category=web-development">Web Development</a>
                <a href="courses.php?category=data-science">Data Science</a>
                <a href="courses.php?category=mobile-development">Mobile Development</a>
                <a href="courses.php?category=devops-cloud">DevOps & Cloud</a>
                <a href="courses.php?category=design-creative">Design & Creative</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5>Contact Info</h5>
                <p><i class="fas fa-map-marker-alt me-2"></i>123 Learning Street, Education City</p>
                <p><i class="fas fa-phone me-2"></i>+1 (555) 123-4567</p>
                <p><i class="fas fa-envelope me-2"></i>support@smartlearn.com</p>
                <p><i class="fas fa-clock me-2"></i>Mon - Fri: 9:00 AM - 6:00 PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> SmartLearn. All rights reserved. | Powered by AI</p>
        </div>
    </div>
</footer>

<!-- AI Chatbot Widget -->
<button class="chatbot-toggle" id="chatbotToggle" title="Ask AI Assistant">
    <i class="fas fa-robot"></i>
</button>

<div class="chatbot-widget" id="chatbotWidget">
    <div class="chatbot-header">
        <h6><i class="fas fa-robot me-2"></i>AI Learning Assistant</h6>
        <button class="close-btn" id="chatbotClose">&times;</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages"></div>
    <div class="chatbot-input">
        <input type="text" id="chatbotInput" placeholder="Ask me anything..." autocomplete="off">
        <button id="chatbotSend"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
<?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
