<?php

namespace App\Http\Controllers;

use Api\StarterKit\Controllers\ApiController;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class Controller extends ApiController
{

  /**
   * @var Request
   */
  protected $request;

  public function __construct()
  {
    $this->request = app(Request::class);
  }

  public function inputGet($key, $default = null)
  {
    return $this->request->get($key, $default);
  }

  public function inputAll()
  {
    return $this->request->all();
  }

  public function validateRequest(array $rules, array $messages = [])
  {
    $this->validate($this->request, $rules, $messages);
  }

  public function getValidatedData($rules, array $messages = [])
  {
    $fixedRules = [];

    $keys = [];

    foreach ($rules as $key => $rule) {
      if (!is_numeric($key)) {
        $fixedRules[$key] = $rule;
        $keys[] = $key;
      } else {
        $keys[] = $rule;
      }
    }
    
    $this->validateRequest($fixedRules, $messages);

    return $this->request->only($keys);
  }

  public function getInputData($keys)
  {
    $data = [];
    foreach ((array)$keys as $key => $type) {
      if (!is_numeric($key)) {
        $data[$key] = $this->getCastedValue($type, $this->request->get($key));
      } else {
        $data[$type] = $this->request->get($type);
      }
    }
    return $data;
  }

  public function getCastedValue($type, $value)
  {
    switch ($type) {
      case 'int':
      case 'integer':
        return (int) $value;
      case 'real':
      case 'float':
      case 'double':
        return (float) $value;
      case 'string':
        return (string) $value;
      case 'bool':
      case 'boolean':
        return (bool) $value;
      case 'object':
        return json_decode($value, true);
      case 'array':
      case 'json':
        return json_decode($value, true);
      case 'collection':
        return new Collection(json_decode($value, true));
      default:
        return $value;
    }
  }
}
