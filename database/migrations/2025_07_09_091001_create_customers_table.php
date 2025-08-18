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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cust_name')->nullable();
            $table->longText('cust_address')->nullable();
            $table->string('cust_contact_number', 30)->nullable();
            $table->string('cust_director_name')->nullable();
            $table->string('cust_email', 100)->nullable();
            $table->enum('cust_type', [
                'EU',
                'KT',
                'ME',
                'SI',
                'R'
            ])->nullable();
            $table->string('cust_active', 1)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
