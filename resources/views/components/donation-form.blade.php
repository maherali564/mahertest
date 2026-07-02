<div id="donate-form" class="ec-donation-card">
    <div class="ec-donation-header">
        <i aria-hidden="true" class="fas fa-hand-holding-heart"></i>
        <h2>تبرع الآن وأنقذ حياة</h2>
        <p>اختر المبلغ الذي ترغب بالتبرع به</p>
    </div>

    <div class="ec-quick-amounts">
        <button type="button" class="ec-amount-btn" data-amount="50">
            <span class="ec-amount-value">50</span>
        </button>
        <button type="button" class="ec-amount-btn" data-amount="100">
            <span class="ec-amount-value">100</span>
        </button>
        <button type="button" class="ec-amount-btn featured" data-amount="250">
            <span class="ec-amount-value">250</span>
            <span class="ec-amount-badge">الأكثر شيوعاً</span>
        </button>
        <button type="button" class="ec-amount-btn" data-amount="500">
            <span class="ec-amount-value">500</span>
        </button>
    </div>

    <div class="ec-currency-selector">
        <button type="button" class="ec-currency-btn active" data-currency="USD">USD</button>
        <button type="button" class="ec-currency-btn" data-currency="EUR">EUR</button>
    </div>

    <form id="emergency-donation-form" action="{{ $action }}" method="POST">
        @csrf
        <div style="position:absolute;left:-9999px" aria-hidden="true">
            <input type="text" name="website_url" tabindex="-1" autocomplete="off">
        </div>

        <input type="hidden" name="currency" id="donation-currency" value="USD">

        <div class="ec-floating-input">
            <input type="number" name="amount" id="donation-amount" required min="1" placeholder=" ">
            <label for="donation-amount">مبلغ آخر</label>
            <i aria-hidden="true" class="fas fa-money-bill-wave ec-input-icon"></i>
        </div>

        <div class="ec-floating-input">
            <input type="text" name="donor_name" id="donor-name" required placeholder=" " maxlength="100">
            <label for="donor-name">الاسم الكامل</label>
            <i aria-hidden="true" class="fas fa-user ec-input-icon"></i>
        </div>

        <div class="ec-floating-input">
            <input type="email" name="donor_email" id="donor-email" required placeholder=" " maxlength="255">
            <label for="donor-email">البريد الإلكتروني</label>
            <i aria-hidden="true" class="fas fa-envelope ec-input-icon"></i>
        </div>

        <div class="ec-floating-input">
            <textarea name="message" id="donor-message" rows="2" placeholder=" " maxlength="500"></textarea>
            <label for="donor-message">رسالتك (اختياري)</label>
            <i aria-hidden="true" class="fas fa-comment ec-input-icon"></i>
        </div>

        <button type="submit" class="ec-donate-submit-btn" id="donate-btn">
            <i aria-hidden="true" class="fas fa-heart"></i>
            <span>أنا أتبرع الآن</span>
            <div class="ec-btn-shimmer"></div>
        </button>

        <div class="ec-security-note">
            <i aria-hidden="true" class="fas fa-lock"></i>
            تبرع آمن ومشفر بالكامل
        </div>
    </form>
</div>
