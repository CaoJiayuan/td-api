<?php
/**
 * Created by Cao Jiayuan.
 * Date: 16-12-9
 * Time: 上午10:25
 */

namespace App\Entity;


/**
 * App\Entity\Event
 *
 * @property-read mixed $created_at
 * @mixin \Eloquent
 */
class Event extends BaseEntity
{

  protected $table = 'INT_FINCE_EVENT_VIEW';


  protected $casts = [
    'publish_at'    => 'timestamp',
    'importance'    => 'int',
  ];
}