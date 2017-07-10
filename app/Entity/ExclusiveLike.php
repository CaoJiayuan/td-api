<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 上午11:34
 */

namespace App\Entity;


/**
 * App\Entity\ExclusiveLike
 *
 * @property integer $id
 * @property integer $user_id
 * @property float $news_id 新闻id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $type 新闻类型0-独家,1-资讯
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereNewsId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ExclusiveLike whereType($value)
 * @mixin \Eloquent
 */
class ExclusiveLike extends BaseEntity
{
  protected $table = 'news_likes';

  protected $fillable = [
    'user_id', 'news_id'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}