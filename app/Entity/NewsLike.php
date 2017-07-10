<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\NewsLike
 *
 * @mixin \Eloquent
 */
class NewsLike extends Model
{
  protected $fillable = [
    'user_id', 'news_id', 'type'
  ];
}
