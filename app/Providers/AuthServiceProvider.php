<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\ContactSubmission;
use App\Models\CurrencyRate;
use App\Models\Donation;
use App\Models\DonationSubmission;
use App\Models\EmergencyCampaign;
use App\Models\EmergencyDonation;
use App\Models\GazaStat;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use App\Models\Post;
use App\Models\Program;
use App\Models\Project;
use App\Models\ProjectMedia;
use App\Models\SiteSetting;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Story;
use App\Models\Tag;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\VolunteerOpportunity;
use App\Models\VolunteerTask;
use App\Policies\CategoryPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\ContactSubmissionPolicy;
use App\Policies\CurrencyRatePolicy;
use App\Policies\DonationPolicy;
use App\Policies\DonationSubmissionPolicy;
use App\Policies\EmergencyCampaignPolicy;
use App\Policies\EmergencyDonationPolicy;
use App\Policies\GazaStatPolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\PagePolicy;
use App\Policies\PartnerPolicy;
use App\Policies\PaymentGatewayPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\PostPolicy;
use App\Policies\ProgramPolicy;
use App\Policies\ProjectMediaPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SiteSettingPolicy;
use App\Policies\SliderPolicy;
use App\Policies\StatisticPolicy;
use App\Policies\StoryPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use App\Policies\VolunteerOpportunityPolicy;
use App\Policies\VolunteerPolicy;
use App\Policies\VolunteerTaskPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register all application policies.
     * Each model is mapped to its corresponding policy for authorization.
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Complaint::class => ComplaintPolicy::class,
        ContactSubmission::class => ContactSubmissionPolicy::class,
        CurrencyRate::class => CurrencyRatePolicy::class,
        Donation::class => DonationPolicy::class,
        DonationSubmission::class => DonationSubmissionPolicy::class,
        EmergencyCampaign::class => EmergencyCampaignPolicy::class,
        EmergencyDonation::class => EmergencyDonationPolicy::class,
        GazaStat::class => GazaStatPolicy::class,
        Newsletter::class => NewsletterPolicy::class,
        Page::class => PagePolicy::class,
        Partner::class => PartnerPolicy::class,
        PaymentGateway::class => PaymentGatewayPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        Post::class => PostPolicy::class,
        Program::class => ProgramPolicy::class,
        Project::class => ProjectPolicy::class,
        ProjectMedia::class => ProjectMediaPolicy::class,
        SiteSetting::class => SiteSettingPolicy::class,
        Slider::class => SliderPolicy::class,
        Statistic::class => StatisticPolicy::class,
        Story::class => StoryPolicy::class,
        Tag::class => TagPolicy::class,
        User::class => UserPolicy::class,
        Volunteer::class => VolunteerPolicy::class,
        VolunteerOpportunity::class => VolunteerOpportunityPolicy::class,
        VolunteerTask::class => VolunteerTaskPolicy::class,
    ];

    public function boot(): void
    {
        // Register all policies defined in $policies property
        $this->registerPolicies();
    }
}
