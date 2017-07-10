<?php

namespace App\Entity;

/**
 * App\Entity\MarketCategory
 *
 * @property integer $id
 * @property string $name 类别名称
 * @property-read mixed $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\MarketCategory whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\MarketCategory whereName($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Market[] $markets
 */
class MarketCategory extends BaseEntity
{
  public $timestamps = false;

  protected $fillable = [
    'name',
  ];

  public function markets()
  {
    return $this->hasMany(Market::class)
      ->select([
        'id',
        'name',
        'en_name',
        'price_sell',
        'price_buy',
        'increase',
        'unit',
        'market_category_id'
      ]);
  }
}
