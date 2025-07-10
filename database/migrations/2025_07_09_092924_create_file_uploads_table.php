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
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->enum('file_doc_type', [
                'Denah',
                'Shop Drawing',
                'SLD/Topology',
                'RAB/BOQ/Budget',
                'Personil',
                'Schedule',
                'BAST',
                'BAP',
                'BAT',
                'BATC'
            ])->nullable();
            $table->string('file_table', 50)->nullable();
            $table->string('file_table_id', 20)->nullable();
            $table->longText('file_directory')->nullable();
            $table->longText('file_name')->nullable();
            $table->longText('file_real_name')->nullable();
            $table->string('file_ext', 10)->nullable();
            $table->longText('file_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
    }
};
