<?php

namespace App\Entity;

/**
 * App\Entity\Version
 *
 * @property integer $id
 * @property string $content 内容
 * @property string $version 版本号
 * @property boolean $type 更新类型0-更新提醒,1-强制更新
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Version whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Version extends BaseEntity
{
  protected $fillable = ['content', 'version', 'type'];

  protected $casts = [
    'build' => 'int'
  ];
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}
