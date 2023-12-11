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
    Schema::create('comments', function (Blueprint $table) {
      $table->char('id', 36)->primary();
      $table->uuid('post_id');
      $table->uuid('user_id')->nullable(); // User who posted the comment
      $table->longText('comment_text');
      $table->char('created_by', 36)->nullable(); // Create By Wich User
      $table->char('updated_by', 36)->nullable(); // Update By Wich User
      $table->char('deleted_by', 36)->nullable(); // Delete By Wich User
      $table->timestamps();

      $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->foreign('post_id')->references('id')->on('posts')->cascadeOnDelete()->cascadeOnUpdate();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('comments');
  }
};
