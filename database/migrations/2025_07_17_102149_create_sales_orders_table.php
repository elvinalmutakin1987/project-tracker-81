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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('so_number', 30)->nullable();
            $table->string('so_total', 16)->nullable();
            $table->string('so_tax', 16)->nullable();
            $table->string('so_grand_total', 16)->nullable();
            $table->enum('so_status', [
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
        Schema::dropIfExists('sales_orders');
    }
};
