<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Add a cryptographically-strong access token to every donation.
     *
     * The token replaces guessable auto-increment IDs as the authorization
     * mechanism for the public payment/certificate/tax-invoice endpoints,
     * closing the IDOR vulnerability where any donation could be accessed or
     * cancelled by simply enumerating IDs.
     */
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->unique()->after('transaction_id');
        });

        // Back-fill existing donations so legacy links keep working (with a token).
        DB::table('donations')
            ->whereNull('access_token')
            ->orderBy('id')
            ->chunkById(500, function ($donations) {
                foreach ($donations as $donation) {
                    do {
                        $token = Str::random(64);
                    } while (DB::table('donations')->where('access_token', $token)->exists());

                    DB::table('donations')->where('id', $donation->id)->update([
                        'access_token' => $token,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('access_token');
        });
    }
};
