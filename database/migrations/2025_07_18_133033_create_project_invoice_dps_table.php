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
        Schema::create('project_invoice_dps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('permit_by')->nullable();
            $table->unsignedBigInteger('create_wo_by')->nullable();
            $table->string('projinvdp_number', 20)->nullable();
            $table->string('projinvdp_invoice', 1)->nullable();
            $table->string('projinvdp_invoice_number', 20)->nullable();
            $table->string('projinvdp_grand_total', 16)->nullable();
            $table->string('projinvdp_total', 16)->nullable();
            $table->string('projinvdp_tax', 16)->nullable();
            $table->string('projinvdp_discount', 16)->nullable();
            $table->timestamp('projinvdp_started_at')->nullable();
            $table->timestamp('projinvdp_finished_at')->nullable();
            $table->string('projinvdp_permit_wo', 1)->nullable();
            $table->string('projinvdp_create_wo', 1)->nullable();
            $table->enum('projinvdp_status', [
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
            $table->enum('projinvdp_sent_by', [
                'Whatsapp',
                'Email',
                'Whatsapp & Email',
            ])->nullable();
            $table->string('projinvdp_email_to')->nullable();
            $table->string('projinvdp_wa_to')->nullable();
            $table->timestamp('projinvdp_send_at')->nullable();
            $table->timestamp('projinvdp_permit_at')->nullable();
            $table->longText('projinvdp_hold_message')->nullable();
            $table->longText('projinvdp_revisi_message')->nullable();
            $table->longText('projinvdp_cancel_message')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permit_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('create_wo_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_invoice_dps');
    }
};
