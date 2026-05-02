<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->string('payment_method', 50)->nullable()->after('subscription_status');
            $table->string('payment_reference', 120)->nullable()->after('payment_method');
            $table->string('payment_proof_path')->nullable()->after('payment_reference');
            $table->timestamp('paid_at')->nullable()->after('payment_proof_path');
            $table->text('payment_admin_notes')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('adherents', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_reference',
                'payment_proof_path',
                'paid_at',
                'payment_admin_notes',
            ]);
        });
    }
};
