<?php namespace Deefour\CFDDNS;

use Guzzle\Http\Client;

/**
 * Dynamic DNS updater for the CloudFlare
 *
 * Updates an A record via the CloudFlare API, setting the specified hostname's
 * value to the current external IP address through which the executing computer
 * is available on the internet
 *
 * The api key, hostname, email address (for a CloudFlare account), and record
 * id are tracked in an INI file. Unless specified on the command line of the
 * runner, the config path will default to ~/.cfddns
 */
class Updater {

  /**
   * Path to the .cfddns configuration file
   *
   * @access private
   * @var string
   */
  private $iniFile;

  /**
   * Config data from the INI file
   *
   * @access private
   * @var array
   */
  private $config;

  /**
   * The resolved external IP address
   *
   * @access private
   * @var string
   */
  private $ip;

  /**
   * The TLD parsed from the hostname in the INI
   *
   * @access private
   * @var string
   */
  private $domain;

  /**
   * The subdomain parsed from the hostname in the INI
   *
   * @access private
   * @var string
   */
  private $subdomain;

  /**
   * The IP resolution service. icanhazip.com spits out a raw text response
   * containing only the IP address
   *
   * @access private
   * @var string
   */
  private $ipResolver = 'http://icanhazip.com/';

  /**
   * The base URL for the API calls
   *
   * @access private
   * @var string
   */
  private $apiBase = 'https://www.cloudflare.com';

  /**
   * The request path for the API calls
   *
   * @access private
   * @var string
   */
  private $apiPath = '/api_json.html';

  /**
   * Constructor; loads the configuration, parses the TLD and subdomain from
   * the
   * configured hostname, and determines the external IP of the executing
   * machine
   *
   * @access public
   *
   * @param  $iniFile  string
   *
   * @throws Exception\ConfigException
   * @throws Exception\HostnameException
   * @throws Exception\IpResolutionException
   */
  public function __construct($iniFile) {
    $this->iniFile = $iniFile;

    $this->_loadConfig();
    $this->_parseHostname();
    $this->_resolveIpAddress();
  }

  /**
   * Performs the API call to create or update the A record at CloudFlare
   * with the resolved external IP
   *
   * @access public
   * @return bool when something goes wrong
   * @throws Exception\ApiException
   */
  public function update() {
    $data = [
      'act'          => 'rec_new',
      'tkn'          => $this->config()['apikey'],
      'email'        => $this->config()['email'],
      'z'            => $this->domain,
      'ttl'          => 1,
      'type'         => 'A',
      'name'         => $this->subdomain,
      'content'      => $this->ip(),
      'service_mode' => 0,
    ];

    if ($this->config()['rec_id']) {
      $data = array_merge(
        $data,
        [ 'act' => 'rec_edit', 'id' => $this->config()['rec_id'] ]
      );
    }

    $client   = new Client($this->apiBase);
    $response = $client->get(
      implode('?', [ $this->apiPath, http_build_query($data) ])
    )->send()->json();

    if ($response['result'] !== 'success') {
      throw new Exception\ApiException($this, $response);
    }

    if ( ! array_key_exists('rec_id', $this->config)) {
      file_put_contents(
        $this->iniFile,
        sprintf("\nrec_id=%s\n", $response['response']['rec']['obj']['rec_id']),
        FILE_APPEND
      );
    }

    return true;
  }

  /**
   * Accessor for the config data
   *
   * @access public
   * @return array
   */
  public function config() {
    return $this->config;
  }

  /**
   * Accessor for the ip address
   *
   * @access public
   * @return array
   */
  public function ip() {
    return $this->ip;
  }

  /**
   * Parses the TLD and subdomain from the configured hostname
   *
   * @access private
   * @throws Exception\HostnameException
   */
  private function _parseHostname() {
    preg_match('/([^\.]+)\.([^\.]+\.[^\/$]+)/', $this->config()['hostname'], $domainParts);
    list($ignore, $this->subdomain, $this->domain) = $domainParts;

    if (empty($this->domain) or empty($this->subdomain)) {
      throw new Exception\HostnameException($this);
    }
  }

  /**
   * Resolves the IP address by sending a GET request to the configured
   * resolver
   *
   * @access private
   * @throws Exception\IpResolutionException
   */
  private function _resolveIpAddress() {
    $client   = new Client($this->ipResolver);
    $response = $client->get('/')->send();

    $this->ip = trim($response->getBody(true));

    if ( ! filter_var($this->ip(), FILTER_VALIDATE_IP)) {
      throw new Exception\IpResolutionException($this, $this->ip());
    }
  }

  /**
   * Loads and parses the INI config if it's a valid file
   *
   * @access private
   * @throws Exception\ConfigException
   */
  private function _loadConfig() {
    if ( ! is_file($this->iniFile)) {
      throw new Exception\ConfigException($this);
    }

    $this->config = parse_ini_file($this->iniFile);

    if ( ! empty(array_diff([ 'email', 'hostname', 'apikey' ], array_keys($this->config)))) {
      throw new Exception\ConfigException($this);
    }
  }
}
