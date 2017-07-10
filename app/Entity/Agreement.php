<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Agreement
 *
 * @property int $id
 * @property string $title 网站标题
 * @property string $keyWords 关键字
 * @property string $content 内容
 * @property string $logo 网站logo
 * @property bool $type 0-关于我们,1-基本设置
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereKeyWords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereLogo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Agreement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Agreement extends Model
{
  public $timestamps = true;
  protected $table = 'about';
  protected $fillable = [
    'id',
    'title',
    'keyWords',
    'content',
    'type'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}
