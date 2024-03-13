<?php

/**
 * @file
 * Contains \SMPL\composer\SmplHandler.
 */

namespace Smpl\composer;

use Composer\Script\Event;
use Composer\EventDispatcher\ScriptExecutionException;
use DrupalFinder\DrupalFinder;

class SmplHandler {

  private static function runSmplThemeNPMCommand($cmd, Event $event) {
    $drupalFinder = new DrupalFinder();
    $drupalFinder->locateRoot(getcwd());
    $drupalRoot = $drupalFinder->getDrupalRoot();

    $path = $drupalRoot . "/profiles/smpl_profile/themes/custom/smpl_theme";
    $exitCode = 0;
    $event->getIO()->write("    Running $cmd in $path");
    exec("cd $path; $cmd", $output, $exitCode);

    if ($exitCode > 0) {
      $event->getIO()->writeError($output);
      throw new ScriptExecutionException('Command exited with a failure', $exitCode);
    }
    else {
      $event->getIO()->write($output);
    }

  }

  public static function SmplThemeInstall(Event $event) {
    static::runSmplThemeNPMCommand('npm install', $event);
  }

  public static function SmplThemeBuildDev(Event $event) {
    static::runSmplThemeNPMCommand('npm run dev --silent', $event);
  }

  public static function SmplThemeBuildProd(Event $event) {
    static::runSmplThemeNPMCommand('npm run production --silent', $event);
  }

  public static function SmplThemeLint(Event $event) {
    static::runSmplThemeNPMCommand('npm run lint --silent', $event);
  }

  public static function SmplThemeLintFix(Event $event) {
    static::runSmplThemeNPMCommand('npm run lint:fix --silent', $event);
  }

}
