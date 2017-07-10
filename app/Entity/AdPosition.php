<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-2-28
 * Time: 上午10:43
 */

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\AdPosition
 *
 * @property integer $id
 * @property string $name 广告位名称
 * @property boolean $status 状态(0-已删除,1-可用)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $size 图片尺寸
 * @property boolean $code 广告位代码
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereSize($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\AdPosition whereCode($value)
 */
class AdPosition extends Model
{
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}