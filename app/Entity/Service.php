<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-3-22
 * Time: 下午5:34
 */

namespace App\Entity;

use Illuminate\Foundation\Auth\User;

/**
 * App\Entity\Service
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $updated_at
 * @property string $mobile 手机号
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereMobile($value)
 * @property \Carbon\Carbon $created_at
 * @property string $address
 * @property string $remarks
 * @property integer $branch_id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Service whereBranchId($value)
 * @property-read \App\Entity\ImUser $imUser
 */
class Service extends User
{
  protected $table = 'admins';

  protected $hidden = [
    'updated_at', 'remember_token', 'password', 'branch_id'
  ];

  protected $casts = [
    'created_at' => 'timestamp'
  ];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }

  public function imUser()
  {
    return $this->hasOne(ImUser::class, 'user_id')->where('type', 1);
  }

  public function offLine()
  {
    if ($this->imUser) {
      $this->imUser->update([
        'online' => false
      ]);
    }
  }
}