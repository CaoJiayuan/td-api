<?php

namespace App\Entity;


/**
 * App\Entity\Banner
 *
 * @property integer $id
 * @property string $img 图片
 * @property string $url
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereImg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $status 发布状态(0-发布中|未审核,1-已审核)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Banner whereStatus($value)
 */
class Banner extends BaseEntity
{
  public function __construct()
  {
    \DB::setTablePrefix('protoss_');
    \DB::setDatabaseName('td');
    parent::__construct();
  }
  protected $fillable = [
    'img',
    'url',
    'status'
  ];

  protected $casts = [
    'type' => 'int'
  ];


  public static function reviewed()
  {
    return static::whereStatus(REVIEWED);
  }
}
