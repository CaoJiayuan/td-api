<?php

namespace App\Entity;

/**
 * App\Entity\ChatterService
 *
 * @property integer $id
 * @property integer $chatter_id 聊天用户id,客户,关联im_users表
 * @property integer $service_id 客服id,客户经理,关联im_users表
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereChatterId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereServiceId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $last_msg_at 上一条消息发送时间
 * @property string $last_msg 上一条消息
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereLastMsgAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ChatterService whereLastMsg($value)
 */
class ChatterService extends BaseEntity
{
  protected $fillable = ['chatter_id', 'service_id', 'updated_at', 'last_msg_at','last_msg'];

  public $timestamps = false;

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function getLastMsgAttribute()
  {
    $lastMsg = $this->attributes['last_msg'];
    if ($lm = json_decode($lastMsg, true)) {
      return $lm;
    }
    return $lastMsg;
  }
}
