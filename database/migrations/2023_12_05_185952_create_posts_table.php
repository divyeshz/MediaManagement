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
    Schema::create('posts', function (Blueprint $table) {
      $table->char('id', 36)->primary();
      $table->string('post_type', 10)->nullable();
      $table->string('name', 51)->nullable();
      $table->string('image')->nullable();
      $table->longText('text')->nullable();
      $table->boolean('is_active')->default(1)->comment('0:Blocked,1:Active');
      $table->char('created_by', 36)->nullable(); // Create By Wich User
      $table->char('updated_by', 36)->nullable(); // Update By Wich User
      $table->char('deleted_by', 36)->nullable(); // Delete By Wich User
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('posts');
  }
};
