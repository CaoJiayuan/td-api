<?php

namespace App\Entity;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entity\Resource
 *
 * @property int $id
 * @property string $name 素材名称
 * @property string $content 素材内容
 * @property bool $status 状态(0-已删除,1-可用)
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resource whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Entity\Resources whereDeletedAt($value)
 */
class Resources extends Model
{

  protected $table = 'resources';
  protected $fillable = ['name', 'content', 'status'];
  protected $hidden = ['created_at'];
  protected $dates = ['deleted_at'];

  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);
    $this->setConnection('protoss');
  }
}
