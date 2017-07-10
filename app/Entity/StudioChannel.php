<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\StudioChannel
 *
 * @property integer $id
 * @property integer $studio_id
 * @property integer $channel_id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\StudioChannel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\StudioChannel whereStudioId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\StudioChannel whereChannelId($value)
 * @mixin \Eloquent
 */
class StudioChannel extends Model
{
  public $timestamps = false;
  
  
  protected $fillable = [
    'studio_id',
    'channel_id'
  ];
}
