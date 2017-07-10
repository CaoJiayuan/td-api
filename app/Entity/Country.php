<?php

namespace App\Entity;

/**
 * App\Entity\Country
 *
 * @property integer $id
 * @property string $name
 * @property-read mixed $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Country whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Country whereName($value)
 * @mixin \Eloquent
 */
class Country extends BaseEntity
{
  public $timestamps = false;
  
  protected $fillable = [
    'name',
  ];
}
