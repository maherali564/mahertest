(function() {
    // Mobile nav toggle
    var toggle = document.querySelector('.nav-toggle');
    var nav = document.getElementById('nav');
    if (toggle && nav) {
        toggle.addEventListener('click', function() {
            var expanded = toggle.getAttribute('aria-expanded') === 'true' ? false : true;
            toggle.setAttribute('aria-expanded', expanded);
            nav.classList.toggle('open');
        });
    }

    // Dropdown toggle on mobile
    var dropdownItems = document.querySelectorAll('.nav__item--dropdown > .nav__link');
    dropdownItems.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            }
        });
    });

    var subdropdownItems = document.querySelectorAll('.nav__dropdown-item > .nav__dropdown-link');
    subdropdownItems.forEach(function(link) {
        link.addEventListener('click', function(e) {
            if (window.innerWidth <= 768) {
                e.preventDefault();
                this.parentElement.classList.toggle('open');
            }
        });
    });

    // Animated counters
    var counters = document.querySelectorAll('[data-count]');
    function animateCounters() {
        counters.forEach(function(counter) {
            var target = parseInt(counter.getAttribute('data-count'));
            var prefix = counter.getAttribute('data-prefix') || '';
            var current = 0;
            var increment = Math.ceil(target / 60);
            var timer = setInterval(function() {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                counter.textContent = prefix + current.toLocaleString();
            }, 25);
        });
    }

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                animateCounters();
                observer.disconnect();
            }
        });
    }, { threshold: 0.3 });
    if (counters.length > 0) {
        observer.observe(counters[0].closest('.stats') || document.body);
    }

    // Amount presets
    var presets = document.querySelectorAll('.amount-preset');
    var amountInput = document.getElementById('donationAmount');
    presets.forEach(function(btn) {
        btn.addEventListener('click', function() {
            presets.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            if (amountInput) {
                amountInput.value = btn.getAttribute('data-amount');
            }
        });
    });

    // Recurring toggle
    var recurringToggle = document.getElementById('recurringToggle');
    var recurringOptions = document.getElementById('recurringOptions');
    if (recurringToggle && recurringOptions) {
        recurringToggle.addEventListener('change', function() {
            recurringOptions.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Scroll to top
    var scrollBtn = document.getElementById('scrollToTop');
    if (scrollBtn) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 400) {
                scrollBtn.classList.add('visible');
            } else {
                scrollBtn.classList.remove('visible');
            }
        });
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Dark mode
    var darkToggle = document.getElementById('darkModeToggle');
    if (darkToggle) {
        var saved = localStorage.getItem('theme');
        if (saved === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
            darkToggle.innerHTML = '<i class="fas fa-sun"></i>';
        }
        darkToggle.addEventListener('click', function() {
            var html = document.documentElement;
            var isDark = html.getAttribute('data-theme') === 'dark';
            if (isDark) {
                html.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                darkToggle.innerHTML = '<i class="fas fa-moon"></i>';
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                darkToggle.innerHTML = '<i class="fas fa-sun"></i>';
            }
        });
    }
    // Language dropdown
    var langDropdown = document.querySelector('.lang-dropdown');
    if (langDropdown) {
        var btn = langDropdown.querySelector('.lang-dropdown__btn');
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var open = langDropdown.classList.toggle('open');
            btn.setAttribute('aria-expanded', open);
        });
        document.addEventListener('click', function() {
            langDropdown.classList.remove('open');
            btn.setAttribute('aria-expanded', 'false');
        });
        langDropdown.querySelector('.lang-dropdown__menu').addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
})();
