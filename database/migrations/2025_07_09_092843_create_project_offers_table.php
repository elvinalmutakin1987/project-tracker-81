<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('projoff_started_at')->nullable();
            $table->timestamp('projoff_finished_at')->nullable();
            $table->string('projoff_grand_total', 16)->nullable();
            $table->string('projoff_offer_number', 20)->nullable();
            $table->string('projoff_quotation', 1)->nullable();
            $table->enum('projoff_status', [
                'Open',
                'Started',
                'Hold',
                'Revisi Mesin',
                'Approval',
                'Cancelled',
                'Done',
            ])->nullable();
            $table->enum('projoff_sent_by', [
                'Whatsapp',
                'Email',
                'Whatsapp & Email',
            ])->nullable();
            $table->longText('projoff_hold_message')->nullable();
            $table->longText('projoff_revisi_message')->nullable();
            $table->longText('projoff_cancel_message')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_offers');
    }
};
