<?php

namespace App\Entity;

/**
 * App\Entity\Feedback
 *
 * @property int $id
 * @property string $mobile 手机号
 * @property string $content 内容
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereMobile($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $img 图片
 * @property string $email email
 * @property int $user_id 用户id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereImg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Feedback whereUserId($value)
 */
class Feedback extends BaseEntity
{
  protected $table = 'feedback';

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  protected $fillable = [
    'mobile', 'content', 'img', 'email','user_id'
  ];

  protected $casts = [
    'created_at' => 'timestamp'
  ];
  protected $hidden = [
    'updated_at','user_id'
  ];
}
