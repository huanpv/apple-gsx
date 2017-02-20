<?php

/*
PHP wrapper for  GSX Web Service API

@license
DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
Version 2, December 2004

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.
*/
class GSX {

	private $client; // SoapClient instance

	/*
	function __construct: Create SoapClient instance

	@param string $region: GSX region code
	@param string $environment: GSX environment code
	@param string $certificate: Certificate file
	@param string $passphrase: Certificate passphrase
	*/
	public function __construct ($region, $environment, $certificate, $passphrase) {

		// Check for SoapClient class
		if (!class_exists('SoapClient')) {
			throw new Exception('SOAP is not installed');
		}

		// Check that certificate is readable
		if (!is_readable($certificate)) {
			throw new Exception('Certificate is unreadable');
		}

		// Check for valid region
		$regions = ['am', 'emea', 'apac', 'la'];
		if (!in_array($region, $regions)) {
			$regions = implode($regions, ', ');
			throw new Exception("Invalid region, should be one of {$regions}");
		}

		// Check for valid environment
		$environments = ['it', 'ut', 'production'];
		if (!in_array($environment, $environments)) {
			$environments = implode($environments, ', ');
			throw new Exception("Invalid environment, should be one of {$environments}");
		}

		// Environment code should be empty if production
		$environment = ($environment == 'production' ? '' : $environment);
		$wsdl = "https://gsxapi{$environment}.apple.com/wsdl/{$region}Asp/gsx-{$region}Asp.wsdl";

		$options = [
			'trace' => TRUE,
			'exceptions' => TRUE,
			'local_cert' => $certificate,
			'passphrase' => $passphrase,
			'connection_timeout' => '10', // Connection attempt timeout
			'default_socket_time' => '15', // Request timeout
		];

		$this->client = new SoapClient($wsdl, $options);

		if(!$this->client) {
			throw new Exception('Failed to create SoapClient instance');
		}

	}

	/*
	@function request: Do the GSX request

	@param string $method: Method name
	@param array $data: Method data

	@return object: Method response
	*/
	public function request($method, $data) {

		try {
			return $this->client->$method($data);
		}
		catch (Exception $e) {
			echo $e;
		}

	}

}

?>
