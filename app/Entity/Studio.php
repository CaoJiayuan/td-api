<?php

namespace App\Entity;

/**
 * App\Entity\Studio
 *
 * @property integer $id
 * @property boolean $type 直播间类型(0-文字,1-视频)
 * @property string $title 标题
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $status 发布状态(0-未审核,1-已审核)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereStatus($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Channel[] $channels
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property boolean $enable 启用状态(0-未开启,1-开启)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereEnable($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Entity\Anchor[] $anchors
 * @property boolean $vip VIP直播(0-是,1-否)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Studio whereVip($value)
 */
class Studio extends BaseEntity
{
  protected $fillable = [
    'type',
    'title',
    'status',
    'enable',
    'vip',
  ];

  protected $casts = [
    'type'   => 'int',
    'status' => 'int',
    'vip'    => 'bool',
  ];
  

  public function channels()
  {
    return $this->belongsToMany(Channel::class, 'studio_channels', 'studio_id', 'channel_id')->select([
      'channels.id',
      'channels.name'
    ]);
  }

  public function anchors()
  {
    return $this->hasMany(Anchor::class)->select([
      'id',
      'name',
      'description',
      'studio_id'
    ]);
  }
}
