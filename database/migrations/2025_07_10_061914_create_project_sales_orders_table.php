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
        Schema::create('project_sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('projso_number', 20)->nullable();
            $table->timestamp('projso_started_at')->nullable();
            $table->timestamp('projso_finished_at')->nullable();
            $table->string('projso_grand_total', 16)->nullable();
            $table->string('projso_sales_order', 1)->nullable();
            $table->string('projso_so_number', 20)->nullable();
            $table->string('projso_po_number', 20)->nullable();
            $table->enum('projso_status', [
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
            $table->enum('projso_sent_by', [
                'Whatsapp',
                'Email',
                'Whatsapp & Email',
            ])->nullable();
            $table->longText('projso_hold_message')->nullable();
            $table->longText('projso_revisi_message')->nullable();
            $table->longText('projso_cancel_message')->nullable();
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
        Schema::dropIfExists('project_sales_orders');
    }
};
