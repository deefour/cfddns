<?php namespace Deefour\CFDDNS\Exception;

/**
 * Base class for the various dynamic DNS updater exceptions
 */
class GenericException extends \Exception {

  /**
   * Reference to the calling dns updater instance from which this exception was
   * thrown
   *
   * @access protected
   * @var \Deefour\CFDDNS\Updater
   */
  protected $updater;

  /**
   * Constructor; stores a reference to the invoking updater along with an
   * optional message that will be json encoded to a string.
   *
   * @param  \Deefour\CFDDNS\Updater $updater
   * @param  string|null             $message
   *
   * @return void
   */
  public function __construct(\Deefour\CFDDNS\Updater $updater, $message = null) {
    $this->updater = $updater;

    if ( ! is_null($message)) {
      $message = json_encode($message);
    }

    parent::__construct($message);
  }

}
