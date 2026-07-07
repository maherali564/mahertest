class EmergencyCampaign {
    constructor(campaignId, locale) {
        this.campaignId = campaignId;
        this.locale = locale || 'ar';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    }

    init() {
        this.initParallax();
        this.initCountdown();
        this.initImpactCountUp();
        this.initWebSocket();
        this.initQuickAmounts();
        this.initForm();
        this.initFaqAccordion();
        this.initGallery();
        this.initLightbox();
        this.initSmoothScroll();
    }

    initParallax() {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const heroBg = document.querySelector('.ec-hero-bg');
            if (heroBg) {
                heroBg.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
        }, { passive: true });
    }

    initCountdown() {
        const container = document.querySelector('.ec-countdown-container');
        if (!container) return;
        const endsAt = container.dataset.endsAt;
        if (!endsAt) return;

        const update = () => {
            const now = new Date().getTime();
            const distance = new Date(endsAt).getTime() - now;

            if (distance < 0) {
                container.innerHTML = '<p style="color:white;font-size:1.2rem">انتهت الحملة</p>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            this.updateFlipCard('countdown-days', days);
            this.updateFlipCard('countdown-hours', hours);
            this.updateFlipCard('countdown-minutes', minutes);
            this.updateFlipCard('countdown-seconds', seconds);
        };

        update();
        setInterval(update, 1000);
    }

    updateFlipCard(id, value) {
        const el = document.getElementById(id);
        if (!el) return;
        const numEl = el.querySelector('.ec-countdown-number');
        if (!numEl) return;
        const currentValue = numEl.innerText;
        const newValue = String(value).padStart(2, '0');

        if (currentValue !== newValue) {
            numEl.innerText = newValue;
            el.classList.add('flip');
            setTimeout(() => el.classList.remove('flip'), 500);
        }
    }

    initImpactCountUp() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.animateCountUp(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('.ec-impact-number').forEach(el => observer.observe(el));
    }

    animateCountUp(el) {
        const target = parseInt(el.dataset.target);
        if (isNaN(target)) return;
        const duration = 2000;
        const start = performance.now();

        const update = (now) => {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.innerText = Math.floor(target * eased).toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        };

        requestAnimationFrame(update);
    }

    initWebSocket() {
        if (typeof Echo === 'undefined') return;

        Echo.channel('emergency-campaign.' + this.campaignId)
            .listen('EmergencyDonationReceived', (data) => {
                this.updateProgress(data.progress_percent, data.new_total);
                this.addDonorToWall(data.donation, data.donor_count);
                this.updateDonorCount(data.donor_count);
            });
    }

    updateProgress(percent, total) {
        const fill = document.querySelector('.ec-progress-fill-glass');
        const pct = document.getElementById('progress-percent');
        const collected = document.getElementById('collected-amount');
        const container = document.querySelector('.ec-glass-progress-container');
        const currency = container ? container.dataset.currency : 'USD';
        if (fill) fill.style.width = percent + '%';
        if (pct) pct.innerText = percent + '%';
        if (collected) collected.innerText = Number(total).toLocaleString() + ' ' + currency;
    }

    addDonorToWall(donation, count) {
        const wall = document.getElementById('donor-wall-list');
        if (!wall) return;

        const emptyMsg = wall.querySelector('p');
        if (emptyMsg && emptyMsg.innerText.includes('تبرعات')) emptyMsg.remove();

        const item = document.createElement('div');
        item.className = 'ec-donor-item new-donation';
        const avatarBg = this.getAvatarColor(donation.donor_name);
        item.innerHTML = `
            <div class="ec-donor-avatar" style="background:${avatarBg}">
                ${donation.donor_name.charAt(0)}
            </div>
            <div class="ec-donor-info">
                <strong>${this.escapeHtml(donation.donor_name)}</strong>
                ${donation.message ? `<p class="ec-donor-message">"${this.escapeHtml(donation.message)}"</p>` : ''}
                <small>الآن</small>
            </div>
            <div class="ec-donor-amount">${Number(donation.amount).toLocaleString()} ${donation.currency || 'USD'}</div>
        `;
        wall.prepend(item);

        if (wall.children.length > 50) wall.lastElementChild.remove();
        setTimeout(() => item.classList.remove('new-donation'), 3000);
    }

    getAvatarColor(name) {
        const colors = ['#C62828','#1565C0','#2E7D32','#6A1B9A','#E65100','#00838F','#AD1457','#4527A0'];
        let hash = 0;
        for (let i = 0; i < (name || '').length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    updateDonorCount(count) {
        const badge = document.getElementById('donor-count-badge');
        if (badge) {
            badge.innerHTML = `<span class="ec-live-dot"></span> ${count} متبرع`;
        }
    }

    initQuickAmounts() {
        document.querySelectorAll('.ec-amount-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const input = document.getElementById('donation-amount');
                if (input) input.value = btn.dataset.amount;
                document.querySelectorAll('.ec-amount-btn').forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
            });
        });

        document.querySelectorAll('.ec-currency-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.ec-currency-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('donation-currency').value = btn.dataset.currency;
            });
        });
    }

    initForm() {
        const form = document.getElementById('emergency-donation-form');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('donate-btn');
            const originalText = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التبرع...';

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();

                if (result.success) {
                    if (result.checkout_url) {
                        this.showToast('جاري تحويلك إلى بوابة الدفع الآمن...', 'success');
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحويل...';
                        window.location.href = result.checkout_url;
                        return;
                    }

                    form.reset();
                    document.querySelectorAll('.ec-amount-btn').forEach(b => b.classList.remove('selected'));
                    document.querySelectorAll('.ec-currency-btn').forEach(b => b.classList.remove('active'));
                    document.querySelector('.ec-currency-btn[data-currency="USD"]').classList.add('active');
                    document.getElementById('donation-currency').value = 'USD';
                    this.updateProgress(result.progress_percent, result.new_total);
                    this.updateDonorCount(result.donor_count);
                    this.showToast('تم التبرع بنجاح! جزاك الله خيراً 🎉', 'success');
                    if (result.donation) {
                        this.addDonorToWall(result.donation);
                        if (typeof window.addDonor === 'function') {
                            window.addDonor(result.donation);
                        }
                    }
                } else {
                    this.showToast(result.message || 'حدث خطأ', 'error');
                }
            } catch (error) {
                this.showToast('حدث خطأ. حاول مرة أخرى', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    }

    initFaqAccordion() {
        document.querySelectorAll('.ec-faq-question').forEach(btn => {
            btn.addEventListener('click', () => {
                const expanded = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', !expanded);
                const answer = btn.nextElementSibling;
                if (answer) {
                    answer.classList.toggle('open');
                }
            });
        });
    }

    initGallery() {
        document.querySelectorAll('.ec-gallery-item').forEach(item => {
            item.addEventListener('click', () => {
                const img = item.querySelector('img');
                if (!img) return;
                this.openLightbox(img.src);
            });
        });
    }

    initLightbox() {
        const closeBtn = document.querySelector('.ec-lightbox-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.closeLightbox());
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') this.closeLightbox();
        });
    }

    openLightbox(src) {
        const lightbox = document.getElementById('ec-lightbox');
        if (!lightbox) return;
        const img = lightbox.querySelector('img');
        if (img) img.src = src;
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    closeLightbox() {
        const lightbox = document.getElementById('ec-lightbox');
        if (!lightbox) return;
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    }

    initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const target = document.querySelector(anchor.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    showToast(message, type) {
        const existing = document.querySelector('.ec-toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `ec-toast ${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);

        requestAnimationFrame(() => {
            toast.classList.add('show');
        });

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    }
}
