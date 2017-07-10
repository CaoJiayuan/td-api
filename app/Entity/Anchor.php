<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Anchor
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereUpdatedAt($value)
 * @property string $name 主讲姓名
 * @property string $description 描述
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereDescription($value)
 * @property integer $studio_id
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Anchor whereStudioId($value)
 */
class Anchor extends Model
{
  protected $fillable = [
    'studio_id',
    'name',
    'description',
  ];

  protected $hidden = [
    'studio_id',
    'updated_at',
  ];
}
