<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-23
 * Time: 上午9:55
 */

namespace App\Entity;


/**
 * App\Entity\Im
 *
 * @property integer $id 自增长id
 * @property integer $user_id 用户id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereImUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereImPwd($value)
 * @mixin \Eloquent
 * @property string $im_id im ID
 * @property string $im_password im密码
 * @property boolean $type 类型(0-用户,1-客服)
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereImId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereImPassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereUpdatedAt($value)
 * @property integer $service_id 用户的当前客服id
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereServiceId($value)
 * @property-read \App\Entity\User $user
 * @property-read \App\Entity\ImUser $service
 * @property string $con_id 当前回话id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereConId($value)
 * @property string $nick 用户nickname
 * @property string $icon_url 用户头像url
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereNick($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereIconUrl($value)
 * @property boolean $online 是否在线
 * @property-read mixed $last_msg
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereOnline($value)
 * @property string $device_id 设备号
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\ImUser whereDeviceId($value)
 */
class ImUser extends BaseEntity
{
  protected $fillable = ['user_id', 'im_id', 'im_password', 'type', 'con_id', 'nick', 'icon_url', 'online','service_id','device_id'];

  protected $hidden = [
    'service_id', 'type', 'updated_at', 'user_id', 'device_id'
  ];

  protected $casts = [
    'online'      => 'bool',
    'last_msg_at' => 'timestamp'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function user()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function service()
  {
    return $this->belongsTo(ImUser::class, 'service_id')->select([
      'id', 'user_id', 'im_id', 'nick', 'icon_url'
    ]);
  }

  public function getLastMsgAttribute()
  {
    $lastMsg = $this->attributes['last_msg'];
    if ($lm = json_decode($lastMsg, true)) {
      if (!isset($lm['hello'])) {
        $lm['hello'] = array_get($lm, 'message._lcattrs.hello', false);
      }
      return $lm;
    }
    return $lastMsg;
  }

  public function getNickAttribute()
  {
    $nick = $this->attributes['nick'];

    return $nick ?: '匿名用户';
  }
}