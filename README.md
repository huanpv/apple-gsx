# apple-gsx

A wrapper class written in PHP that simplifies communicating with Apple GSX Web Service API via SOAP.

## Requirements

* PHP 5.4.x
* SOAP support
* Signed certificate(s)
* Whitelisted IP address

## Usage

Include the class in your project.

```php
function autoload($class) {
	require_once("{$class}.class.php");
}

spl_autoload_register('autoload');
```

The class has a constructer, so you have to instantiate it using parameters.

```php
$gsx = new GSX('{REGION CODE}', '{ENVIRONMENT}', '{CERTIFICATE FILE}', '{CERTIFICATE PASSPHRASE}');
```

Now you can simply call `$gsx->request('method', $data)` where `$data` is an array containing the appropriate data for the method.

## Examples

#### Authentication

Authentication creates a session ID that's needed for all other methods.
The session ID is valid for 30 minutes, so you'll probably want to save it along with a timestamp in a browser session or database to prevent the user from getting a ATH.LOG.20 error.

```php
$data = [
	'AuthenticateRequest' => [
		'userId' => 'john@doe.com',
		'languageCode' => 'en',
		'userTimeZone' => 'CEST',
		'serviceAccountNo' => '1000001',
	],
];

$request = $gsx->request('Authenticate', $data)->AuthenticateResponse;
echo json_encode($request);
```

The output (if successful) would look something like

```json
{"userSessionId":"26US9Svslm6DzQpQRDLkdfM","operationId":"LnjrLfZfhK3GLM4TzwRKv"}
```

#### Warranty status

Want to know the warranty status of a device?

```php
'WarrantyStatusRequest' => [
	$data = [
		'userSession' => [
			'userSessionId' => '26US9Svslm6DzQpQRDLkdfM',
		],
		'unitDetail' => [
			'serialNumber' => '70033CDFA4S',
			'shipTo' => '1000001',
		],
	],
];

$gsx->request('WarrantyStatus', $data)->WarrantyStatusResponse->warrantyDetailInfo->warrantyStatus;
```

Need to know if specific part(s) are covered with a certain CompTIA?

```php
$parts[] = ['partNumber' => '661-02757', 'comptiaCode' => 'H03'];
$parts[] = ['partNumber' => '661-7109', 'comptiaCode' => 'L03'];

$data = [
	'WarrantyStatusRequest' => [
		'userSession' => [
			'userSessionId' => '26US9Svslm6DzQpQRDLkdfM',
		],
		'unitDetail' => [
			'serialNumber' => '70033CDFA4S',
			'partNumbers' => $parts,
			'shipTo' => '1000001',
		],
	],
];

$gsx->request('WarrantyStatus', $data);
```

For the remaining examples `$data` is an array containing the appropriate data for the API method.

#### Find my iPhone / Activation Lock status

To lookup Activation Lock status, simply grab the activationLockStatus key in the response.

```php
$gsx->request('WarrantyStatus', $data)->WarrantyStatusResponse->warrantyDetailInfo->activationLockStatus;
```

#### IMEI to serial number

```php
$gsx->request('FetchIOSActivationDetails', $data)->FetchIOSActivationDetailsResponse->activationDetailsInfo->serialNumber;
```

#### CompTIA codes

```php
$gsx->request('ComptiaCodeLookup', $data)->ComptiaCodeLookupResponse->comptiaInfo;
```

#### GSX API docs

[Integration Testing (GSXIT)](https://gsxwsut.apple.com/apidocs/it/html/WSHome.html)

[Acceptance Testing (GSXUT)](https://gsxwsut.apple.com/apidocs/ut/html/WSHome.html)

[Production (GSX)](https://gsxwsut.apple.com/apidocs/prod/html/WSHome.html)

#### License

```
DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
Version 2, December 2004

Copyright (C) 2004 Sam Hocevar <sam@hocevar.net>

Everyone is permitted to copy and distribute verbatim or modified
copies of this license document, and changing it is allowed as long
as the name is changed.

DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION

0. You just DO WHAT THE FUCK YOU WANT TO.
```
