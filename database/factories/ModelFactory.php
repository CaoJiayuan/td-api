<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Entity\Anchor;
use App\Entity\Banner;
use App\Entity\Channel;
use App\Entity\News;
use App\Entity\Studio;
use App\Entity\StudioChannel;
use App\User;

$factory->define(User::class, function (Faker\Generator $faker) {
  return [
    'name'           => $faker->name,
    'email'          => $faker->safeEmail,
    'password'       => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
  ];
});

$factory->define(Banner::class, function (Faker\Generator $faker) {

  return [
    'img'    => $faker->imageUrl(),
    'url'    => 'http://www.baidu.com',
    'status' => 1,
  ];
});

$factory->define(Channel::class, function (Faker\Generator $faker) {
  $faker = \Faker\Factory::create('zh_CN');

  return [
    'name' => $faker->title,
  ];
});


$factory->define(Studio::class, function () {
  $faker = \Faker\Factory::create('zh_CN');

  return [
    'type'   => rand(0, 1),
    'title'  => $faker->title,
    'status' => 1,
    'vip'    => rand(0, 1),
  ];
});

$factory->define(StudioChannel::class, function (Faker\Generator $faker) {
  $channelId = Channel::all()->random()->id;

  $studio = factory(Studio::class)->create();

  return [
    'studio_id'  => $studio->id,
    'channel_id' => $channelId,
  ];
});


$factory->define(Anchor::class, function (Faker\Generator $faker) {
  $faker = \Faker\Factory::create('zh_CN');

  return [
    'studio_id'   => Studio::all()->random()->id,
    'name'        => $faker->name,
    'description' => $faker->text(100),
  ];
});
