<?php
/**
 * Created by Cao Jiayuan.
 * Date: 16-12-22
 * Time: 下午6:10
 */


if (!function_exists('file_map')) {
  function file_map($file, Closure $closure, $recursive = true)
  {
    foreach ((array)$file as $fe) {
      if (is_dir($fe)) {
        $items = new FilesystemIterator($fe);
        /** @var SplFileInfo $item */
        foreach ($items as $item) {
          if ($item->isDir() && !$item->isLink() && $recursive) {
            file_map($item->getPathname(), $closure);
          } else {
            $closure($item->getPathname(), $item);
          }
        }
      } else {
        $f = new SplFileInfo($fe);
        $closure($fe, $f);
      }
    }
  }
}

if (!function_exists('object_to_array')) {
  function object_to_array($object, &$result)
  {
    $data = $object;
    if (is_object($data)) {
      $data = get_object_vars($data);
    }
    if (is_array($data)) {
      foreach ($data as $key => $value) {
        $res = null;
        object_to_array($value, $res);
        if (($key == '@attributes') && ($key)) {
          $result = $res;
        } else {
          $result[$key] = $res;
        }
      }
    } else {
      $result = $data;
    }
  }
}