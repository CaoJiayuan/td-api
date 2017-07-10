<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-1-13
 * Time: 下午5:47
 */

namespace App\Entity;


/**
 * App\Entity\User
 *
 * @property int $id 自增ID
 * @property int $version 版本号
 * @property string $create_time 创建时间
 * @property string $update_time 更新时间
 * @property string $phone 电话
 * @property string $real_name 真名
 * @property string $nick_name 昵称
 * @property string $avatar 头像
 * @property string $address 住址
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereVersion($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereCreateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereUpdateTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereRealName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereNickName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereAvatar($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereAddress($value)
 * @mixin \Eloquent
 * @property-read mixed $created_at
 * @property integer $broker_id 客户经理id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\User whereBrokerId($value)
 */
class User extends BaseEntity
{
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  protected $table = 'user';

  const CREATED_AT = 'create_time';
  const UPDATED_AT = 'update_time';

  protected $fillable = [
    'version',
    'create_time',
    'update_time',
    'phone',
    'real_name',
    'nick_name',
    'avatar',
    'address',
  ];
}