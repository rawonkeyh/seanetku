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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('status', ['available', 'reserved', 'sold'])->default('available');
            $table->timestamp('reserved_at')->nullable();
            $table->timestamp('sold_at')->nullable();
            $table->timestamps();
            
            $table->index(['package_id', 'status']);
            $table->index(['status', 'reserved_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
