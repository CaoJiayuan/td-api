<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Variety
 *
 * @mixin \Eloquent
 */
class Variety extends Model
{
    protected $fillable = [
      'key','value'
    ];
}
