<?php
/**
 * ModelHelper.php
 * Date: 16/9/19
 * Time: 上午9:57
 * Created by Caojiayuan
 */

namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use UnexpectedValueException;

trait ModelHelper
{
  public $forceCopy = false;

  /**
   * @param Builder $builder
   * @param $columns
   * @param $keyword
   * @return Builder
   */
  public function likeOr($builder, $columns, $keyword)
  {
    $keyword = trim($keyword);
    $builder->where(function ($builder) use ($columns, $keyword) {
      foreach ((array)$columns as $column) {
        /** @var Builder $builder */
        $builder->orWhere($column, 'like', "%{$keyword}%");
      }
    });

    return $builder;
  }

  public function copy($model, $data, $whereKey = null)
  {
    if (!is_object($model)) {
      $model = app($model);
    }

    if (!$model instanceof Model) {
      throw new UnexpectedValueException('Except instance of '
        . Model::class . ', instance of ' . get_class($model) . ' giving.');
    }

    $cantCopy = property_exists(get_class($model), 'cantCopy') && $model->cantCopy != null ? $model->cantCopy : [];

    $fillable = $model->getFillable();

    if ($whereKey && array_key_exists($whereKey, $data)) {
      if ($find = $model->where($whereKey, '=', $data[$whereKey])->first()) {
        $model = $find;
      }
    } else {
      $key = $model->getKeyName();
      if (array_key_exists($key, $data)) {
        if ($find = $model->where($key, '=', $data[$key])->first()) {
          $model = $find;
        }
      }
    }

    if ($this->forceCopy) {
      $cantCopy = [];
    }

    foreach ((array)$fillable as $column) {
      if (array_key_exists($column, $data) && null != $data[$column] && !in_array($column, $cantCopy)) {
        $model->$column = $data[$column];
      }
    }

    $model->save();

    return $model;
  }

  /**
   * @param $model
   * @param Request $request
   * @param bool $required
   * @return array
   */
  public function getFillableDataFromRequest($model, $request, $required = true)
  {
    if (!is_object($model)) {
      $model = app($model);
    }

    if (!$model instanceof Model) {
      throw new UnexpectedValueException('Except instance of '
        . Model::class . ', instance of ' . get_class($model) . ' giving.');
    }
    $fillable = $model->getFillable();

    if ($required) {
      $rule = [];
      foreach ($fillable as $item) {
        $rule[$item] = 'required';
      }
      $this->validate($request, $rule);
    }

    return $request->only($fillable);
  }
}