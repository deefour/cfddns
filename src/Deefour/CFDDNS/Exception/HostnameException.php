<?php namespace Deefour\CFDDNS\Exception;

/**
 * Thrown when the hostname found in the configuration file is malformed. Currently
 * this means the hostname does not conform to a `[subdomain].[domain]` format.
 *
 * @see Deefour\CFDDNS\Updater::_parseHostname
 */
class HostnameException extends GenericException {

  /**
   * Magic method for a pretty string representation of this class, used when
   * displaying a user-friendly error message via the CLI
   *
   * @access public
   * @return string
   */
  public function __toString() {
    return '<red>A valid hostname in the format</red> ' .
           '<white><bold>[subdomain].[domain]</bold></white> ' .
           '<red>must be provided</red>';
  }

}
