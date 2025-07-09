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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_type_id');
            $table->string('proj_number', 20)->nullable();
            $table->string('proj_name')->nullable();
            $table->string('proj_customer', 100)->nullable();
            $table->string('proj_work_type', 50)->nullable();
            $table->string('proj_leader', 25)->nullable();
            $table->date('proj_start_date')->nullable();
            $table->date('proj_due_date')->nullable();
            $table->date('prof_finished_date')->nullable();
            $table->longText('proj_notes')->nullable();
            $table->string('proj_progress', 10)->nullable();
            $table->enum('proj_status', [
                'Draft',
                'Pra-tender',
                'Submitted',
                'Under Review',
                'Shortlisted',
                'Negotiation',
                'Awarded',
                'Contract Signed',
                'Planning',
                'In Progress',
                'On Hold',
                'Delayed',
                'Cancelled',
                'Completed',
                'Closed'
            ]);
            $table->longText('proj_hold_message')->nullable();
            $table->longText('proj_delayed_message')->nullable();
            $table->longText('proj_cancel_message')->nullable();
            $table->timestamps();
            $table->foreign('work_type_id')->references('id')->on('work_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
