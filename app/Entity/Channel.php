<?php

namespace App\Entity;

/**
 * App\Entity\Channel
 *
 * @property integer $id
 * @property string $name 频道名称
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Studio[] $studios
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Channel whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Channel whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Channel whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Channel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Channel extends BaseEntity
{
  protected $fillable = [
    'name',
  ];

  protected $hidden = [
    'pivot',
    'updated_at',
  ];

  public function studios()
  {
    return $this->belongsToMany(Studio::class, 'studio_channels', 'channel_id', 'studio_id');
  }
}
