<?php

/*
  |--------------------------------------------------------------------------
  | ERROR DISPLAY
  |--------------------------------------------------------------------------
  | Don't show ANY in production environments. Instead, let the system catch
  | it and display a generic error message.
 */
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED & ~E_STRICT);
ini_set('display_errors', 0);

/*
  |--------------------------------------------------------------------------
  | DEBUG MODE
  |--------------------------------------------------------------------------
  | Debug mode is an experimental flag that can allow changes throughout
  | the system. It's not widely used currently, and may not survive
  | release of the framework.
 */

defined('CI_DEBUG') || define('CI_DEBUG', false);
