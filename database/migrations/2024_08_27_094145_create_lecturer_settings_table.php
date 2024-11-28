<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('lecturer_settings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('lecturer_id')->constrained('users');
        $table->boolean('is_identity_visible')->default(true); // Defaults to true, meaning the identity is visible
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturer_settings');
    }
};
