<?php

namespace WPKirk\Flags;

class Flags
{

  /**
   * The plugin instance
   *
   * @var WPKirk
   */
  private $plugin;

  /**
   * Flags
   */
  private $flags = [];

  private $path = 'config/flags.yaml';

  /**
   * FlagsProvider constructor.
   *
   * @param string $path Optional. The path to the flags file.
   */
  public function __construct($path = '')
  {
    $this->plugin = WPKirk();

    if (!empty($path)) {
      $this->path = $path;
    } else {
      // Check if in the config/plugin.php there a path for flags
      $path = $this->plugin->config('plugin.flags.path', null);

      if ($path) {
        $this->path = $path;
      }
    }

    $this->initFlags();
  }

  /**
   * Init Flags
   *
   * @since 1.6.0
   */
  private function initFlags()
  {
    if (empty($this->path)) {
      throw new \Exception('WP Bones Flags package: path to flags file is not set.');
    }

    try {
      $flags_file = "{$this->plugin->getBasePath()}/{$this->path}";

      if (function_exists('yaml_parse_file')) {
        $this->flags = yaml_parse_file($flags_file);
      } else {
        $this->flags = Yaml::parseFile($flags_file);
      }
    } catch (\Exception $e) {
      $this->flags = [];
    }
  }

  /**
   * Set the path to the flags file.
   *
   * @param string $path The path to the flags file.
   *
   * @return $this
   */
  public function withPath($path)
  {
    $this->path = $path;
    $this->initFlags();
    return $this;
  }

  /**
   * Get a flags value.
   *
   * @param string $key The dot-separated key. E.g. 'database.host'
   * @param mixed $default The default value to return if the key is not found.
   *
   * @return mixed
   */
  public function flags(string $key, $default = null)
  {
    $flags = $this->flags;
    $keys = explode('.', $key);
    foreach ($keys as $key) {
      if (isset($flags[$key])) {
        $flags = $flags[$key];
      } else {
        return $default;
      }
    }
    return $flags;
  }

  /**
   * @param $name   string  Name of the method
   * @param $arguments      Arguments passed to the method
   *
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

  /**
   * Get a flags value.
   *
   * @param string $key The dot-separated key. E.g. 'database.host'
   * @param mixed $default The default value to return if the key is not found.
   *
   * @return mixed
   */
  private function callableGet($key, $default = null)
  {
    return $this->flags($key, $default);
  }

  /**
   * Set the path to the flags file.
   *
   * @param string $path The path to the flags file.
   *
   * @return $this
   */
  private function callabeWithPath($path)
  {
    return $this->withPath($path);
  }
}
