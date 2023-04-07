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
        Schema::create('import', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('last_name', 50);
            $table->string('first_name', 50);
            $table->string('middle_name', 100)->nullable();
            $table->string('address_street', 100);
            $table->string('address_brgy', 50);
            $table->string('address_city', 50);
            $table->string('address_province', 50);
            $table->string('contact_phone', 15)->nullable();
            $table->string('contact_mobile', 15);
            $table->string('email', 255)->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import');
    }
};
