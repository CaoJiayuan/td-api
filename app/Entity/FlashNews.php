<?php
/**
 * Created by Cao Jiayuan.
 * Date: 16-11-28
 * Time: 上午9:53
 */

namespace App\Entity;


/**
 * App\Entity\FlashNews
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 */
class FlashNews extends BaseEntity
{

  protected $table = 'CMD_NEWS_FLASH';

  protected $casts = [
    'published_at' => 'timestamp'
  ];
}