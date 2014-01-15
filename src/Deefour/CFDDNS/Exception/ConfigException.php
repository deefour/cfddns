<?php namespace Deefour\CFDDNS\Exception;

/**
 * Thrown when the configuration file specified does not exist or is missing
 * required attributes
 *
 * @see Deefour\CFDDNS\Updater::_loadConfig
 */
class ConfigException extends GenericException {

  /**
   * Magic method for a pretty string representation of this class, used when
   * displaying a user-friendly error message via the CLI
   *
   * @access public
   * @return string
   */
  public function __toString() {
    return '<red>cfddns INI file must contain</red>' . PHP_EOL .
           '  - <white>apikey</white>' . PHP_EOL .
           '  - <white>email</white>' . PHP_EOL .
           '  - <white>hostname</white>';
  }

}
