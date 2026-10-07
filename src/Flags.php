<?php

namespace WPKirk\Flags;

use Symfony\Component\Yaml\Yaml;

/**
 * Feature flags read from a YAML file of the plugin, `config/flags.yaml` by default.
 *
 * get(), flags() and withPath() work both on an instance and statically: `wpbones_flags()->get()`,
 * `$flags->withPath()->get()`, `Flags::get()`, `Flags::withPath()->get()`. They are magic so that
 * one name serves both forms: PHP refuses to call a declared instance method statically.
 *
 * @method mixed get(string $key, mixed $default = null) The value at a dot-separated key, e.g. 'database.host'.
 * @method mixed flags(string $key, mixed $default = null) The same as get().
 * @method $this withPath(string $path) Read the flags from another file of the plugin.
 */
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
      $flags_file = "{$this->plugin->basePath}/{$this->path}";

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
   * Get a flags value.
   *
   * @param string $key The dot-separated key. E.g. 'database.host'
   * @param mixed $default The default value to return if the key is not found.
   *
   * @return mixed
   */
  private function value(string $key, $default = null)
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
   * Magic method to call flags methods.
   *
   * @param string $method The method name.
   * @param array $arguments The arguments passed to the method.
   *
   * @return $this
   */
  public function __call($method, $arguments)
  {
    $method = "callable" . ucfirst($method);

    if (method_exists($this, $method)) {
      return call_user_func_array([$this, $method], $arguments);
    }

    return $this;
  }

  /**
   * Magic method to call flags methods.
   *
   * @param string $name The method name.
   * @param array $arguments The arguments passed to the method.
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
    return $this->value($key, $default);
  }

  private function callableFlags($key, $default = null)
  {
    return $this->value($key, $default);
  }

  /**
   * Set the path to the flags file.
   *
   * @param string $path The path to the flags file.
   *
   * @return $this
   */
  private function callableWithPath($path)
  {
    $this->path = $path;
    $this->initFlags();

    return $this;
  }
}
