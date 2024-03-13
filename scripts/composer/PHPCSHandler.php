<?php

/**
 * @file
 * Contains \DrupalProject\composer\PHPCSHandler.
 */

namespace DrupalProject\composer;

use Composer\Script\Event;

class PHPCSHandler {

  protected static function getProjectRoot() {
    return getcwd();
  }

  public static function installDrupalSniffs(Event $event) {
    $path = static::getProjectRoot();
    exec("cd $path; vendor/bin/phpcs");
  }

}
