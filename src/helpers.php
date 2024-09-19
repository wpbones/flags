<?php

if (!function_exists('wpbones_flags')) {

  /**
   * Helper function to return the FlagsProvider instance.
   *
   * @example
   *
   * wpbones_flags()->get('flag', 'value');
   *
   *
   * @return WPKirk\Flags\Flags
   */
  function wpbones_flags($path = '')
  {
    return new WPKirk\Flags\Flags($path);
  }
}
