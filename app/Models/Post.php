<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post  extends BaseModel
{
  use HasFactory;

  protected $fillable = [
    'post_type',
    'name',
    'image',
    'text',
    'is_active',
    'created_by',
    'updated_by',
    'deleted_by',
  ];

  // Define the relationship with users through the shared_posts table
  public function users()
  {
    return $this->belongsToMany(User::class, 'shared_posts', 'post_id', 'user_id');
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  // Define the relationship with comments
  public function comments()
  {
    return $this->hasMany(Comment::class);
  }
}
