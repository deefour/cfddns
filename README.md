# cfddns - CloudFlare Dynamic DNS updater

A simple dynamic DNS updater for the CloudFlare.com API, written in PHP. Requires PHP 5.5.

### Installing

Each release of cfddns includes a `cfddns.phar` file containing all files needed to run the tool. You can find a list of releases and the available downloads at https://github.com/deefour/cfddns/releases

**Download the `cfddns.phar` and put it in a directory in your `$PATH`.**

### Configuration

cfddns reads an INI configuration file to make proper API calls against CloudFlare. An annotated template can be found below and at https://github.com/deefour/cfddns/blob/master/.cfddns.template

```ini
; Your CloudFlare API key
apikey=your_api_key_here

; The email address associated with your CloudFlare account
email=jason@deefour.me

; The full domain you wish to set the A record for.
;
; The TLD should already exist within your cloudflare account. It does not matter
; if an A record for the subdomain already exists
hostname=home.deefour.me
```

### Execution

The updater is only useful if run regularly. This can be done by creating a cron job for `cfddns`.

```
*\2 * * * * /usr/bin/env php /usr/local/bin/cfddns.phar -c ~/.cfddns
```

## Changelog

#### 0.3.2 - May 25, 2014

 - Code formatting

#### 0.3.1 - July 20, 2014

 - Fixed PHP notices
 - Upgraded dependencies

## License

Copyright (c) 2014 Jason Daly *(deefour)*. Released under the [MIT License](http://deefour.mit-license.org/)
