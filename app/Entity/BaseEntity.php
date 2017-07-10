<?php
/**
 * BaseEntity.php
 * Date: 2016/10/12
 * Time: 上午11:33
 * Created by Caojiayuan
 */

namespace App\Entity;


use Api\StarterKit\Entities\Entity;

/**
 * App\Entity\BaseEntity
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 */
class BaseEntity extends Entity
{
  protected $hidden = [
    'updated_at'
  ];
}