<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Calendar
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereUpdatedAt($value)
 * @property string $title 标题
 * @property string $unit 单位
 * @property float $value_before 前值
 * @property float $value_predict 预测值
 * @property float $value 公布值
 * @property string $publish_at 公布时间
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereUnit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereValueBefore($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereValuePredict($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar wherePublishAt($value)
 * @property integer $country_id 国家ID
 * @property boolean $importance 重要程度
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereCountryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Calendar whereImportance($value)
 * @property-read mixed $effect
 */
class Calendar extends Model
{
  protected $table = 'GLOB_ECO_INDR_VIEW';

  protected $casts = [
    'value_before'  => 'float',
    'value_predict' => 'float',
    'value'         => 'float',
    'publish_at'    => 'timestamp',
    'next_time'     => 'timestamp',
    'importance'    => 'int',
  ];


  protected $hidden = [
    'updated_at',
    'created_at',
  ];

  public function getValueAttribute()
  {
    $var = $this->attributes['value'];
    if ($var == '待公布')   {
      return null;
    }
    return (float)$var;
  }

  public function getEffectAttribute()
  {
    return html_entity_decode(array_get($this->attributes, 'effect', ''));
  }

}
