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

    Schema::create('users', function (Blueprint $table) {
      $table->char('id', 36)->primary();
      $table->string('name', 51);
      $table->string('email', 51)->nullable();
      $table->string('phone', 10)->nullable();
      $table->string('profile')->nullable();
      $table->enum('gender', ['male', 'female'])->nullable();
      $table->string('social_id', 51)->nullable();;
      $table->string('social_type', 51)->nullable();;
      $table->boolean('is_active')->default(1)->comment('0:Blocked,1:Active');
      $table->timestamp('email_verified_at')->nullable();
      $table->char('created_by', 36)->nullable(); // Create By Wich User
      $table->char('updated_by', 36)->nullable(); // Update By Wich User
      $table->char('deleted_by', 36)->nullable(); // Delete By Wich User
      $table->timestamps(); // Adds created_at and updated_at columns
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
