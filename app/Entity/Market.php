<?php

namespace App\Entity;

/**
 * App\Entity\Market
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name 名称
 * @property string $en_name 英文名称
 * @property float $increase 涨幅(%)
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereEnName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereIncrease($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereUpdatedAt($value)
 * @property boolean $enable 启用状态(0-未开启,1-开启)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereEnable($value)
 * @property integer $price_sell 价格卖出(分)
 * @property integer $price_buy 价格买入(分)
 * @property integer $market_category_id 类别
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market wherePriceSell($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market wherePriceBuy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Market whereMarketCategoryId($value)
 */
class Market extends BaseEntity
{

  protected $fillable = [
    'name',
    'en_name',
    'price_sell',
    'price_buy',
    'increase',
    'enable',
    'unit',
    'market_category_id',
  ];


  protected $casts = [
    'price_sell' => 'float',
    'price_buy'  => 'float',
    'increase'   => 'float',
  ];

  protected $hidden = [
    'updated_at',
    'created_at',
    'market_category_id',
  ];

  public static function enabled()
  {
    return static::whereEnable(true);
  }
}
