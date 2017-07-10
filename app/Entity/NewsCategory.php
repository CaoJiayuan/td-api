<?php
/**
 * Created by Cao Jiayuan.
 * Date: 16-11-29
 * Time: 下午4:43
 */

namespace App\Entity;


/**
 * App\Entity\NewsCategory
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 */
class NewsCategory extends BaseEntity
{
  protected $fillable = [
    'name'
  ];
}