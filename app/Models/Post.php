<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post  extends BaseModel
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
    parent::booted();
    static::bootMethod();
  }

  // Define the relationship with users through the shared_posts table
  public function users()
  {
    return $this->belongsToMany(User::class, 'shared_posts', 'post_id', 'user_id');
  }

  public function owner()
  {
    return $this->belongsTo(User::class, 'created_by');
  }
}
