<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
  use HasFactory;

  protected $primaryKey = 'id';
  public $incrementing = false;

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

  protected static function booted()
  {
    static::creating(function ($post) {
      $post->id = Str::uuid()->toString();
      $userId = auth()->id() ?? null;
      $post->created_by = $userId;
    });

    static::updating(function ($post) {
      $userId = auth()->id() ?? null;
      $post->updated_by = $userId;
    });

    static::deleting(function ($post) {
      $userId = auth()->id() ?? null;
      $post->deleted_by = $userId;
    });
  }

  // Define the relationship with users through the shared_posts table
  public function users()
  {
      return $this->belongsToMany(User::class, 'shared_posts', 'post_id', 'user_id');
  }
}
