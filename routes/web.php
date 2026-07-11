<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EmergencyCampaignController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/'.config('app.locale', 'ar')));

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/rss.xml', [App\Http\Controllers\RssController::class, 'index'])->name('rss');


Route::prefix('{locale}')->where(['locale' => implode('|', config('app.supported_locales', ['ar', 'en']))])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projects/{slug}', [ProjectController::class, 'show'])->name('projects.show');
        Route::get('/page/privacy-policy', fn () => view('pages.privacy-policy', ['settings' => \App\Models\SiteSetting::current()]))->name('pages.privacy');
        Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');
        Route::get('/about', [App\Http\Controllers\AboutController::class, 'index'])->name('about.index');
        Route::get('/transparency', [App\Http\Controllers\TransparencyController::class, 'index'])->name('transparency.index');
        Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
        Route::get('/stories/{slug}', [StoryController::class, 'show'])->name('stories.show');

        // Blog
        Route::get('/blog', [App\Http\Controllers\Blog\PostController::class, 'index'])->name('posts.index');
        Route::get('/blog/categories', [App\Http\Controllers\Blog\CategoryController::class, 'index'])->name('posts.categories');
        Route::get('/blog/tags', [App\Http\Controllers\Blog\TagController::class, 'index'])->name('posts.tags');
        Route::get('/blog/{slug}', [App\Http\Controllers\Blog\PostController::class, 'show'])->name('posts.show');
        Route::get('/category/{slug}', [App\Http\Controllers\Blog\CategoryController::class, 'show'])->name('posts.category');
        Route::get('/tag/{slug}', [App\Http\Controllers\Blog\TagController::class, 'show'])->name('posts.tag');

        Route::get('/donate/project/{slug}', [DonationController::class, 'projectPage'])->name('donate.project');
        Route::get('/donate/story/{slug}', [DonationController::class, 'storyPage'])->name('donate.story');
        Route::get('/donate', [App\Http\Controllers\DonateController::class, 'index'])->name('donate.page');
        Route::post('/donate', [DonationController::class, 'store'])->name('donate.store')->middleware('throttle:donations');

        // Emergency Campaigns
        Route::get('/emergency-campaigns', [EmergencyCampaignController::class, 'index'])->name('emergency-campaigns.index');
        Route::get('/emergency-campaigns/{campaign:slug}', [EmergencyCampaignController::class, 'show'])->name('emergency-campaigns.show');
        Route::post('/emergency-campaigns/{campaign:slug}/donate', [EmergencyCampaignController::class, 'donate'])->name('emergency-campaigns.donate')->middleware('throttle:5,1');
        Route::get('/api/emergency-campaigns/{campaign:slug}/donations', [EmergencyCampaignController::class, 'donations'])->name('emergency-campaigns.donations')->middleware('throttle:30,1');
        Route::get('/api/emergency-campaigns/{campaign:slug}/stats', [EmergencyCampaignController::class, 'stats'])->name('emergency-campaigns.stats')->middleware('throttle:30,1');

        Route::post('/contact', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:contact');
        Route::get('/complaints/create', [App\Http\Controllers\ComplaintController::class, 'create'])->name('complaints.create');
        Route::post('/complaints', [App\Http\Controllers\ComplaintController::class, 'store'])->name('complaints.store')->middleware('throttle:10,1');
        Route::get('/volunteer', [VolunteerController::class, 'dashboard'])->name('volunteer.dashboard');
        Route::get('/volunteer/register', [VolunteerController::class, 'register'])->name('volunteer.register');
        Route::post('/volunteer', [VolunteerController::class, 'store'])->name('volunteer.store')->middleware('throttle:volunteer');

        Route::get('/payment/success/{donation}', [PaymentController::class, 'success'])->name('payment.success');
        Route::get('/payment/cancel/{donation}', [PaymentController::class, 'cancelForm'])->name('payment.cancel');
        Route::post('/payment/cancel/{donation}', [PaymentController::class, 'cancel'])->name('payment.cancel.post');
        Route::get('/payment/instructions/{donation}', [PaymentController::class, 'instructions'])->name('payment.instructions');

                Route::get('/currency/rates', [App\Http\Controllers\CurrencyController::class, 'rates'])->name('currency.rates')->middleware('throttle:60,1');

                Route::get('/rss.xml', [App\Http\Controllers\RssController::class, 'showLocale'])->name('rss.locale');

    });

Route::post('/payment/webhook/stripe', [WebhookController::class, 'stripe'])->name('payment.webhook.stripe')->middleware('throttle:60,1');
Route::post('/payment/webhook/paypal', [WebhookController::class, 'paypal'])->name('payment.webhook.paypal')->middleware('throttle:60,1');

// Admin locale switcher
Route::match(['GET', 'POST'], '/admin/locale/{locale}', function ($locale) {
    $supported = config('app.supported_locales', ['ar', 'en', 'es', 'id', 'tr', 'sv']);
    if (in_array($locale, $supported)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }
    return redirect()->back();
})->name('admin.locale')->middleware(['auth', 'throttle:10,1']);
