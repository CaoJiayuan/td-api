<?php

namespace App\Entity;

/**
 * App\Entity\Complaint
 *
 * @property int $id
 * @property int $comment_id 评论id
 * @property bool $type 类型0-资讯,1-专栏
 * @property string $content 内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereCommentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property bool $user_id 用户id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Complaint whereUserId($value)
 */
class Complaint extends BaseEntity
{
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  protected $fillable = [
    'comment_id', 'content', 'type','user_id'
  ];
}
