<?php

require 'vendor/autoload.php';

//$hubspot = SevenShores\Hubspot\Factory::create('demo');
$hubSpot = SevenShores\Hubspot\Factory::createWithOAuth2Token('CIHGmq2NLxICAQEYu4y-BCDSoKULKNn2EDIZAEhUT-APx1ATg22_1MvBcHyk-5ZxW1_0MjoaAAoCQQAADIADAAgAAAABAAAAAAAAABjAABNCGQBIVE_gtuAb5KuyWL7v3p4tig0uAMZtFXBKA25hMQ');

$handlerStack = \GuzzleHttp\HandlerStack::create();
$handlerStack->push(
	\SevenShores\Hubspot\RetryMiddlewareFactory::createRateLimitMiddleware(
		\SevenShores\Hubspot\Delay::getConstantDelayFunction()
	)
);

$handlerStack->push(
	\SevenShores\Hubspot\RetryMiddlewareFactory::createInternalErrorsMiddleware(
		\SevenShores\Hubspot\Delay::getExponentialDelayFunction(2)
	)
);

$guzzleClient = new \GuzzleHttp\Client(['handler' => $handlerStack]);

$config = [
	'key'      => "ca301355-d31c-422e-a4f9-4c6d9af8ac53",
	'oauth2'   =>  false,
];

$hubspot = new \SevenShores\Hubspot\Factory($config, new \SevenShores\Hubspot\Http\Client($config, $guzzleClient));

$contact = $hubspot->contacts()->getByEmail('nick@abrt.vc');

echo $contact->properties->email->value;

//$response = $hubspot->contacts()->all([
//	'count'     => 10,
//	'property'  => ['firstname', 'lastname'],
//	'vidOffset' => 123456,
//]);
//foreach ($response->contacts as $contact) {
//	echo sprintf(
//		"Contact name is %s %s." . PHP_EOL,
//		$contact->properties->firstname->value,
//		$contact->properties->lastname->value
//	);
//}
//
//// Info for pagination
//echo $response->{'has-more'};
//echo $response->{'vid-offset'};