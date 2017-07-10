<?php

use App\Entity\Market;
use App\Entity\MarketCategory;
use App\Traits\ModelHelper;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
  use ModelHelper;

  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $markets = [
      '自选' => [
        [
          'name'       => '黄金延期',
          'en_name'    => 'Au(T+D)',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
        [
          'name'       => '白银延期',
          'en_name'    => 'Ag(T+D)',
          'price_buy'  => 94.45,
          'price_sell' => 94.45,
          'increase'   => 0.12,
        ],
        [
          'name'       => '迷你黄金',
          'en_name'    => 'mAu(T+D)',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => -1.12,
        ],
      ],
      '上海黄金' => [
        [
          'name'       => '黄金延期',
          'en_name'    => 'Au(T+D)',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
        [
          'name'       => '白银延期',
          'en_name'    => 'Ag(T+D)',
          'price_buy'  => 94.45,
          'price_sell' => 94.45,
          'increase'   => 0.12,
        ],
        [
          'name'       => '迷你黄金',
          'en_name'    => 'mAu(T+D)',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => -1.12,
        ],
      ],
      '参考报价'   => [
        [
          'name'       => '铂金99.95',
          'en_name'    => 'Pt99.95',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
        [
          'name'       => '白银99.9',
          'en_name'    => 'Ag99.9',
          'price_sell' => 94.45,
          'price_buy'  => 94.45,
          'increase'   => 1.12,
        ],
        [
          'name'       => '黄金100g',
          'en_name'    => 'Au100g',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
        [
          'name'       => '黄金99.9',
          'en_name'    => 'Au99.9',
          'price_sell' => 94.45,
          'price_buy'  => 94.45,
          'increase'   => 1.12,
        ],
        [
          'name'       => '黄金50g',
          'en_name'    => 'Au50g',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
        [
          'name'       => '黄金单月延期',
          'en_name'    => 'Au(T+N1)',
          'price_sell' => 94.45,
          'price_buy'  => 94.45,
          'increase'   => 1.12,
        ],
        [
          'name'       => '黄金双月延期',
          'en_name'    => 'Au(T+N2)',
          'price_sell' => 288.14,
          'price_buy'  => 288.14,
          'increase'   => 1.12,
        ],
      ],
    ];

    foreach ($markets as $key => $market) {
      $cat = MarketCategory::create([
        'name' => $key,
      ]);

      foreach ($market as $item) {
        $item['market_category_id'] = $cat->id;

        $this->copy(Market::class, $item);
      }
    }
  }
}
