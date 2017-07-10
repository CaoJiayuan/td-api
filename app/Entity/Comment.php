<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-15
 * Time: 上午10:12
 */

namespace App\Entity;

use Illuminate\Database\Query\Builder;


/**
 * App\Entity\Comment
 *
 * @property int $id
 * @property float $news_id 新闻id
 * @property string $content 内容
 * @property int $user_id 用户id
 * @property bool $type 类型(0-独家专栏,1-新闻资讯)
 * @property bool $status 状态(0-已删除,1-可用)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $parent_id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereNewsId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Comment whereParentId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Comment[] $children
 * @property-read \App\Entity\User $user
 * @property-read \App\Entity\Comment $parent
 */
class Comment extends BaseEntity
{
  public static $con = 'mysql';

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection(static::$con);
  }

  protected $fillable = [
    'news_id',
    'content',
    'user_id',
    'parent_id'
  ];
  protected $hidden = ['user_id', 'parent_id'];

  protected $casts = [
    'created_at' => 'timestamp'
  ];

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder
   */
  public function children()
  {
    return $this->hasMany(Comment::class, 'parent_id');
  }

  public function comments()
  {
    $builder = $this->children();
    return $builder;
  }

  public function parent()
  {
    return $this->belongsTo(Comment::class, 'parent_id','id');
  }
  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}