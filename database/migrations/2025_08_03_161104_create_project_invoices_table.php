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
        Schema::create('project_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('projinv_number', 20)->nullable();
            $table->string('projinv_invoice', 1)->nullable();
            $table->string('projinv_invoice_number', 20)->nullable();
            $table->string('projinv_grand_total', 16)->nullable();
            $table->string('projinv_total', 16)->nullable();
            $table->string('projinv_tax', 16)->nullable();
            $table->string('projinv_discount', 16)->nullable();
            $table->timestamp('projinv_started_at')->nullable();
            $table->timestamp('projinv_finished_at')->nullable();
            $table->enum('projinv_status', [
                'Open',
                'Not Started',
                'Started',
                'On Going',
                'Hold',
                'Revisi Mesin',
                'Approval',
                'Cancelled',
                'Done',
            ])->nullable();
            $table->enum('projinv_sent_by', [
                'Whatsapp',
                'Email',
                'Whatsapp & Email',
            ])->nullable();
            $table->string('projinv_email_to')->nullable();
            $table->string('projinv_wa_to')->nullable();
            $table->timestamp('projinv_send_at')->nullable();
            $table->longText('projinv_hold_message')->nullable();
            $table->longText('projinv_revisi_message')->nullable();
            $table->longText('projinv_cancel_message')->nullable();
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
        Schema::dropIfExists('project_invoices');
    }
};
