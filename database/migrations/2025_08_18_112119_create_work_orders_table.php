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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('project_work_order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('wo_number', 20)->nullable();
            $table->string('wo_date')->nullable();
            $table->string('wo_print_count', 20)->nullable();
            $table->timestamp('wo_started_at')->nullable();
            $table->timestamp('wo_finished_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('checked1_by')->nullable();
            $table->unsignedBigInteger('checked2_by')->nullable();
            $table->unsignedBigInteger('checked3_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('project_work_order_id')->references('id')->on('project_work_orders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checked1_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checked2_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('checked3_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
