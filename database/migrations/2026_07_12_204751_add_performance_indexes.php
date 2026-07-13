<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ─── donations (most-queried table) ───
        Schema::table('donations', function (Blueprint $table) {
            $table->index('status');
            $table->index('email');
            $table->index('project_id');
            $table->index('story_id');
            $table->index('donor_id');
            $table->index('created_at');
            $table->index('donated_at');
            $table->index(['status', 'created_at']);
            $table->index(['status', 'project_id']);
            $table->index(['status', 'story_id']);
        });

        // ─── projects ───
        Schema::table('projects', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('sort_order');
            $table->index(['is_active', 'sort_order']);
        });

        // ─── stories ───
        Schema::table('stories', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('slug');
        });

        // ─── emergency_campaigns ───
        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['is_active', 'created_at']);
            $table->index(['is_active', 'is_featured', 'created_at']);
            $table->index('ends_at');
        });

        // ─── posts ───
        Schema::table('posts', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('type');
            $table->index('published_at');
        });

        // ─── contact_submissions ───
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->index('is_read');
        });

        // ─── volunteers ───
        Schema::table('volunteers', function (Blueprint $table) {
            $table->index('status');
        });

        // ─── donation_submissions ───
        Schema::table('donation_submissions', function (Blueprint $table) {
            $table->index('status');
        });

        // ─── statistics ───
        Schema::table('statistics', function (Blueprint $table) {
            $table->index('type');
            $table->index(['type', 'is_active']);
        });

        // ─── programs ───
        Schema::table('programs', function (Blueprint $table) {
            $table->index('is_active');
            $table->index(['is_active', 'sort_order']);
        });

        // ─── sliders ───
        Schema::table('sliders', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order']);
        });

        // ─── partners ───
        Schema::table('partners', function (Blueprint $table) {
            $table->index('is_active');
        });

        // ─── payment_confirmations ───
        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['email']);
            $table->dropIndex(['project_id']);
            $table->dropIndex(['story_id']);
            $table->dropIndex(['donor_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['donated_at']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['status', 'project_id']);
            $table->dropIndex(['status', 'story_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['is_active', 'sort_order']);
        });

        Schema::table('stories', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['sort_order']);
            $table->dropIndex(['slug']);
        });

        Schema::table('emergency_campaigns', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_active', 'created_at']);
            $table->dropIndex(['is_active', 'is_featured', 'created_at']);
            $table->dropIndex(['ends_at']);
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['type']);
            $table->dropIndex(['published_at']);
        });

        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropIndex(['is_read']);
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('donation_submissions', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('statistics', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['type', 'is_active']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_active', 'sort_order']);
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });

        Schema::table('payment_confirmations', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
