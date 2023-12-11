<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'post_id', 'user_id', 'comment_text', 'created_by', 'updated_by', 'deleted_by',
  ];

  // Define the relationship with posts
  public function post()
  {
    return $this->belongsTo(Post::class);
  }

  // Define the relationship with users (if necessary)
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
