<?php
/**
 * Created by Cao Jiayuan.
 * Date: 17-2-28
 * Time: 下午4:50
 */

namespace App\Entity;


use Api\StarterKit\Entities\Entity;

/**
 * App\Entity\NewsCount
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 */
class NewsCount extends Entity
{

  protected $fillable = [
    'news_id',
    'read',
    'like',
  ];
}