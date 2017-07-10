<?php

namespace App\Entity;

/**
 * App\Entity\News
 *
 * @property integer $id
 * @property string $title 标题
 * @property string $from 来源
 * @property string $summary 摘要
 * @property string $thumbnail 缩略图
 * @property string $content 内容
 * @property boolean $type 新闻类型(0-新闻资讯,1-快讯)
 * @property boolean $status 发布状态(0-发布中|未审核,1-已审核)
 * @property string $reviewed_at 审核通过时间
 * @property string $published_at 发布时间
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereFrom($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereThumbnail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereReviewedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\News whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read mixed $read
 * @property-read mixed $like
 */
class News extends BaseEntity
{


  const CREATED_AT = 'createtime';
  const UPDATED_AT = 'updatetime';

  protected $table = 'CMD_NEWS_MAIN';
  protected $fillable = [
    'title',
    'from',
    'summary',
    'thumbnail',
    'content',
    'type',
    'status',
    'reviewed_at',
    'published_at',
  ];

  protected $casts = [
    'published_at' => 'timestamp',
    'type'         => 'int',
    'id'           => 'int',
    'read'         => 'int',
    'like'         => 'int',
    'liked'        => 'bool',
  ];

  public function getReadAttribute()
  {
    return (int)(array_get($this->attributes, 'read', 0));
  }

  public function getLikeAttribute()
  {
    return (int)(array_get($this->attributes, 'like', 0));
  }
}
