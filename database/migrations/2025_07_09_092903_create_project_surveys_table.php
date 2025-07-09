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
        Schema::create('project_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('projsur_started_at')->nullable();
            $table->timestamp('projsur_finished_at')->nullable();
            $table->string('projsur_denah', 1)->nullable();
            $table->string('projsur_shop', 1)->nullable();
            $table->string('projsur_sld', 1)->nullable();
            $table->string('projsur_rab', 1)->nullable();
            $table->string('projsur_personil', 1)->nullable();
            $table->string('projsur_schedule', 1)->nullable();
            $table->enum('projsur_status', [
                'Open',
                'Started',
                'Hold',
                'Cancelled',
                'Done',
            ]);
            $table->longText('projsur_hold_message')->nullable();
            $table->longText('projsur_cancel_message')->nullable();
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
        Schema::dropIfExists('project_surveys');
    }
};
