<?php namespace Deefour\CFDDNS\Exception;

/**
 * Thrown when something goes wrong on CloudFlare's end, either due to a
 * malformed request by the updater or issues internal to their service
 *
 * @see Deefour\CFDDNS\Updater::update
 */
class ApiException extends GenericException {

  /**
   * Magic method for a pretty string representation of this class, used when
   * displaying a user-friendly error message via the CLI
   *
   * @access public
   * @return string
   */
  public function __toString() {
    return sprintf(
      '<red>There was an API error setting A record:</red> ' .
      '<white><bold>%s</bold></white>',
      $this->updater->config()['hostname']
    );
  }

}
