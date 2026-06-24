// ===== SmartLearn Main JavaScript =====

// DOM Ready
document.addEventListener('DOMContentLoaded', function() {
    initNavbarScroll();
    initSmoothScroll();
    initCourseFilters();
    initQuizTimer();
    initChatbot();
    initDashboardCharts();
    initToastNotifications();
    initPasswordToggle();
    initTooltips();
});

// ===== Navbar Scroll Effect =====
function initNavbarScroll() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
}

// ===== Smooth Scroll =====
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
}

// ===== Course Filters =====
function initCourseFilters() {
    const filterForm = document.getElementById('courseFilterForm');
    if (!filterForm) return;

    const inputs = filterForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', filterCourses);
        input.addEventListener('keyup', debounce(filterCourses, 500));
    });
}

function filterCourses() {
    const searchTerm = document.getElementById('searchInput')?.value?.toLowerCase() || '';
    const category = document.getElementById('categoryFilter')?.value || '';
    const level = document.getElementById('levelFilter')?.value || '';
    const sortBy = document.getElementById('sortFilter')?.value || '';
    const priceRange = document.getElementById('priceFilter')?.value || '';

    const cards = document.querySelectorAll('.course-card');
    cards.forEach(card => {
        const title = card.querySelector('.card-title')?.textContent?.toLowerCase() || '';
        const cat = card.dataset.category || '';
        const lvl = card.dataset.level || '';
        const price = parseFloat(card.dataset.price) || 0;

        let show = true;
        if (searchTerm && !title.includes(searchTerm)) show = false;
        if (category && cat !== category) show = false;
        if (level && lvl !== level) show = false;

        if (priceRange === 'free' && price > 0) show = false;
        if (priceRange === 'paid' && price === 0) show = false;
        if (priceRange === 'under50' && price > 50) show = false;
        if (priceRange === '50to100' && (price < 50 || price > 100)) show = false;
        if (priceRange === 'over100' && price < 100) show = false;

        card.closest('.col-lg-4, .col-md-6, .col')?.style.setProperty('display', show ? '' : 'none');
    });
}

// ===== Quiz Timer =====
function initQuizTimer() {
    const timerEl = document.getElementById('quizTimer');
    if (!timerEl) return;

    let totalSeconds = parseInt(timerEl.dataset.time) || 600;
    const display = document.getElementById('timerDisplay');
    if (!display) return;

    function updateTimer() {
        if (totalSeconds <= 0) {
            document.getElementById('quizForm')?.submit();
            return;
        }
        totalSeconds--;
        const mins = Math.floor(totalSeconds / 60);
        const secs = totalSeconds % 60;
        display.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

        if (totalSeconds <= 60) {
            display.style.color = '#ef4444';
        }
    }

    setInterval(updateTimer, 1000);
}

// ===== Chatbot =====
function initChatbot() {
    const toggleBtn = document.getElementById('chatbotToggle');
    const widget = document.getElementById('chatbotWidget');
    const closeBtn = document.getElementById('chatbotClose');
    const sendBtn = document.getElementById('chatbotSend');
    const input = document.getElementById('chatbotInput');
    const messages = document.getElementById('chatbotMessages');

    if (!toggleBtn || !widget) return;

    toggleBtn.addEventListener('click', () => {
        widget.classList.toggle('active');
        toggleBtn.style.display = widget.classList.contains('active') ? 'none' : 'flex';
        if (widget.classList.contains('active') && messages) {
            addBotMessage("Hi! I'm SmartLearn AI Assistant. How can I help you today? You can ask me about courses, career paths, study tips, or anything about learning!");
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            widget.classList.remove('active');
            toggleBtn.style.display = 'flex';
        });
    }

    if (sendBtn && input && messages) {
        function sendMessage() {
            const text = input.value.trim();
            if (!text) return;

            addUserMessage(text);
            input.value = '';

            setTimeout(() => {
                const response = getAIResponse(text);
                addBotMessage(response);
            }, 800);
        }

        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
    }
}

function addUserMessage(text) {
    const messages = document.getElementById('chatbotMessages');
    if (!messages) return;

    const div = document.createElement('div');
    div.className = 'chatbot-message user';
    div.innerHTML = `<div class="msg-content">${escapeHtml(text)}</div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

function addBotMessage(text) {
    const messages = document.getElementById('chatbotMessages');
    if (!messages) return;

    const div = document.createElement('div');
    div.className = 'chatbot-message bot';
    div.innerHTML = `<div class="msg-content">${escapeHtml(text)}</div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}

function getAIResponse(input) {
    const q = input.toLowerCase();

    if (q.includes('course') && (q.includes('recommend') || q.includes('suggest') || q.includes('what'))) {
        return "Based on your interests, I recommend checking out our Web Development or Data Science courses! They're our most popular categories. Would you like me to help you find courses in a specific field?";
    }
    if (q.includes('web') || q.includes('develop')) {
        return "Great choice! Our Web Development track covers HTML, CSS, JavaScript, PHP, React, and more. Start with 'Complete Web Development Bootcamp' - it's perfect for beginners!";
    }
    if (q.includes('data science') || q.includes('machine learning') || q.includes('python')) {
        return "Data Science is booming! Our Python for Data Science course is a great starting point. It covers pandas, numpy, matplotlib, and machine learning fundamentals.";
    }
    if (q.includes('career') || q.includes('job') || q.includes('guidance')) {
        return "Here are some high-demand tech careers: Full-Stack Developer, Data Scientist, Cloud Architect, DevOps Engineer, and AI/ML Engineer. Which field interests you? I can recommend a learning path!";
    }
    if (q.includes('study') || q.includes('plan') || q.includes('schedule')) {
        return "For effective learning, I recommend studying 1-2 hours daily. Break your sessions into: 25 min focused study, 5 min break. Complete quizzes after each module. Consistent practice is key!";
    }
    if (q.includes('price') || q.includes('cost') || q.includes('free')) {
        return "SmartLearn offers both free and paid courses. Free courses give you access to basic content, while premium courses include full video lessons, quizzes, certificates, and instructor support!";
    }
    if (q.includes('certificate') || q.includes('certification')) {
        return "Yes! Upon completing a course with a passing grade on the final quiz, you'll receive a verified certificate. You can download it from your dashboard or share it on LinkedIn.";
    }
    if (q.includes('hello') || q.includes('hi') || q.includes('hey')) {
        return "Hello! Welcome to SmartLearn! I'm your AI Learning Assistant. How can I help you today?";
    }
    if (q.includes('thank')) {
        return "You're welcome! If you have any more questions, feel free to ask. Happy learning! 🎓";
    }

    return "I'm not sure I understand. You can ask me about courses, career guidance, study planning, certificates, or anything about the SmartLearn platform!";
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// ===== Dashboard Charts (Simple Canvas-based) =====
function initDashboardCharts() {
    // Simple progress circles
    document.querySelectorAll('.progress-ring').forEach(el => {
        const percent = parseInt(el.dataset.percent) || 0;
        const circle = el.querySelector('.ring-circle');
        if (circle) {
            const radius = circle.r.baseVal.value;
            const circumference = 2 * Math.PI * radius;
            const offset = circumference - (percent / 100) * circumference;
            circle.style.strokeDasharray = `${circumference} ${circumference}`;
            circle.style.strokeDashoffset = offset;
        }
    });
}

// ===== Toast Notifications =====
function initToastNotifications() {
    window.showToast = function(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const icons = {
            success: 'fa-check-circle',
            error: 'fa-times-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };

        const toast = document.createElement('div');
        toast.className = `toast-custom ${type}`;
        toast.innerHTML = `
            <i class="fas ${icons[type] || icons.info}"></i>
            <span>${escapeHtml(message)}</span>
            <button class="btn-close ms-auto" onclick="this.parentElement.remove()"></button>
        `;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    };
}

// ===== Password Toggle =====
function initPasswordToggle() {
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fas fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fas fa-eye';
            }
        });
    });
}

// ===== Tooltips =====
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
}

// ===== Utility: Debounce =====
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// ===== Enroll Course =====
function enrollCourse(courseId) {
    showToast('Enrolling in course...', 'info');
    setTimeout(() => {
        showToast('Successfully enrolled! Start learning now.', 'success');
    }, 1500);
}

// ===== Add to Wishlist =====
function toggleWishlist(courseId, btn) {
    const icon = btn.querySelector('i');
    if (icon.classList.contains('fas')) {
        icon.classList.remove('fas');
        icon.classList.add('far');
        showToast('Removed from wishlist', 'info');
    } else {
        icon.classList.remove('far');
        icon.classList.add('fas');
        showToast('Added to wishlist!', 'success');
    }
}

// ===== Rating Stars =====
function initRatingStars() {
    document.querySelectorAll('.rating-input .star').forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.dataset.value);
            const parent = this.closest('.rating-input');
            parent.querySelectorAll('.star').forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('fas');
                    s.classList.remove('far');
                } else {
                    s.classList.remove('fas');
                    s.classList.add('far');
                }
            });
            parent.dataset.rating = rating;
        });
    });
}
