<?php

/**
 * Dynamic DNS Updater for CloudFlare
 *
 * Updates an A record to reflect the external IP address of the network the
 * executing computer resides within
 *
 * @author Jason Daly <jason@deefour.me>
 * @copyright 2014 Jason Daly
 * @license MIT
 * @version 0.1.0
 *
 * @example ./cfddns.phar -c ~/.cfddns
 */

require_once __DIR__ . '/vendor/autoload.php';

use Deefour\CFDDNS\Updater;
use Colors\Color;



// Setup
// -----------------------------------------------------------------------------
$color = new Color;
$shortOpts = 'c:v';
$longOpts  = [ 'config:', 'verbose' ];
$options   = getopt($shortOpts, $longOpts);
$iniFile   = getset([ $options['c'], $options['config'], getenv('HOME') . '/.cfddns' ]);
$verbose   = ! empty(array_intersect([ 'v', 'verbose' ], array_keys($options)));



// Runner
// -----------------------------------------------------------------------------
try {
  $updater = new Updater($iniFile);
  $updater->update();
} catch (Exception $e) {
  echo $color($e)->colorize() . PHP_EOL;

  if ($verbose and $e->getMessage()) {
    echo PHP_EOL . $e->getMessage() . PHP_EOL . PHP_EOL;
  }

  die(1);
}



echo PHP_EOL .
     $color(
       '<yellow><bold>CloudFlare DNS updated!</bold></yellow> ' .
       sprintf('<bold><white>%s</white></bold>', $updater->config()['hostname']) .
       ' <magenta>-></magenta> ' .
       sprintf('<bold><white>%s</white></bold>', $updater->ip())
     )->colorize() .
     PHP_EOL .
     PHP_EOL;
