<?php

namespace WPKirk\Flags;

class FlagsProvider
{

  /**
   * @param $name
   * @param $arguments
   * @return mixed
   */
  public static function __callStatic($name, $arguments)
  {
    $method = "callable" . ucfirst($name);

    $instance = new self;

    if (method_exists($instance, $method)) {
      return call_user_func_array([$instance, $method], $arguments);
    }

    return $instance;
  }
}
