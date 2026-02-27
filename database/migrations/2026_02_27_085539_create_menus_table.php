<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Menu name
            $table->string('icon')->nullable(); // Icon class
            $table->string('route')->nullable(); // Route name
            $table->string('url')->nullable(); // Direct URL
            $table->string('role')->default('user'); // admin/user/both
            $table->integer('parent_id')->nullable(); // For submenu
            $table->integer('order')->default(0); // Menu order
            $table->string('section')->nullable(); // Main/header/footer
            $table->string('header_text')->nullable(); // Menu header text
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};