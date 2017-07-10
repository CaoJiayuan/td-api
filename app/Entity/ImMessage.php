<?php

namespace App\Entity;

/**
 * App\Entity\ImMessage
 *
 * @property integer $id
 * @property integer $sender_id 发送者id,对应im_users
 * @property boolean $type 类型
 * @property boolean $summary 摘要
 * @property string $msg 消息内容json receiver
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereSenderId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereSummary($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereMsg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\ImReceiver[] $receivers
 */
class ImMessage extends BaseEntity
{
  protected $fillable = [
    'sender_id',
    'type',
    'summary',
    'msg',
  ];

  protected $casts = [
    'created_at' => 'timestamp'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function receivers()
  {
    return $this->hasMany(ImReceiver::class, 'im_message_id');
  }

//  public function getMsgAttribute()
//  {
//    $msg = $this->attributes['msg'];
//    if ($lm = json_decode($msg, true)) {
//      if (array_get($lm, 'msg')) {
//        $lm['msg'] =
//      }
//      return $lm;
//    }
//    return $msg;
//  }
}
