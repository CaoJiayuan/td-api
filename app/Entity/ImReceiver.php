<?php

namespace App\Entity;

/**
 * App\Entity\ImReceiver
 *
 * @property integer $id
 * @property integer $im_message_id 消息id,对应im_messages
 * @property integer $receiver_id 消息接受者id,对应im_users
 * @property-read mixed $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImReceiver whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImReceiver whereImMessageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImReceiver whereReceiverId($value)
 * @mixin \Eloquent
 * @property-read mixed $nick
 */
class ImReceiver extends BaseEntity
{
  public $timestamps = false;

  protected $fillable = [
    'im_message_id',
    'receiver_id',
  ];

  protected $hidden = [
    'im_message_id',
    'receiver_id',
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function getNickAttribute()
  {
    $nick = $this->attributes['nick'];

    return $nick ?: '匿名用户';
  }
}
