<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 上午10:38
 */

namespace App\Entity;


use Api\StarterKit\Entities\Entity;

/**
 * App\Entity\Exclusive
 *
 * @property integer $id
 * @property string $title 标题
 * @property string $from 来源
 * @property string $summary 摘要
 * @property string $thumbnail 缩略图
 * @property string $content 内容
 * @property integer $news_category_id 新闻类别id
 * @property boolean $status 发布状态(0-发布中|未审核,1-已审核)
 * @property string $published_at 发布时间
 * @property integer $read 阅读量
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $like
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereThumbnail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereNewsCategoryId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereRead($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Exclusive whereLike($value)
 * @mixin \Eloquent
 */
class Exclusive extends Entity
{
  protected $table = 'news';
  protected $casts = [
    'published_at' => 'timestamp',
    'liked'        => 'bool'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}