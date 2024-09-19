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
   * @return WPKirk\Flags\FlagsProvider
   */
  function wpbones_flags()
  {
    return WPKirk\Flags\FlagsProvider::init();
  }
}
