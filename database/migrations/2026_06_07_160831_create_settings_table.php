<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->string('company_name')->default('PT. Equity World Futures Surabaya');

            $table->string('office_ip', 255)->nullable();
            $table->decimal('office_latitude', 10, 7)->nullable();
            $table->decimal('office_longitude', 10, 7)->nullable();
            $table->integer('allowed_radius')->nullable();

            $table->time('check_in_start')->nullable();
            $table->time('check_in_end')->nullable();
            $table->time('check_out_start')->nullable();
            $table->time('check_out_end')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};