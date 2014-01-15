<?php namespace Deefour\CFDDNS\Exception;

/**
 * Thrown when the there is a network issue between the executing computer
 * and the web service used for IP resolution
 *
 * @see Deefour\CFDDNS\Updater::_resolveIpAddress
 */
class IpResolutionException extends GenericException {

  /**
   * Magic method for a pretty string representation of this class, used when
   * displaying a user-friendly error message via the CLI
   *
   * @access public
   * @return string
   */
  public function __toString() {
    return '<red>A network error prevented the lookup of network ip address</red>';
  }

}
