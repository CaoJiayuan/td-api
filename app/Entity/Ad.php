<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-2-28
 * Time: 上午11:15
 */

namespace App\Entity;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Entity\Ad
 *
 * @property integer $id
 * @property string $title 广告标题
 * @property string $img 广告图片
 * @property integer $ad_position_id 广告位id
 * @property boolean $type 类型(0-链接,1-素材,2-专题)
 * @property string $url 广告链接
 * @property integer $resource_id 素材id
 * @property boolean $status 0-已删除,1-可用
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Entity\AdPosition $position
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereImg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereAdPositionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereResourceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $deleted_at
 * @property string $resource_name 资源名称
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Ad whereResourceName($value)
 */
class Ad extends Model
{
  use SoftDeletes;
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function position()
  {
    return $this->belongsTo(AdPosition::class, 'ad_position_id', 'id');
  }

  public function getUrlAttribute()
  {
    $url = $this->attributes['url'];
    $type = $this->attributes['type'];

    switch ($type) {
      case 0:
      case 1:
      case 4:
        $url = (int)$url;
        break;
      default:
        break;
    }
    return $url;
  }
}