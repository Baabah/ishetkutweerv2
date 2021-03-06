<?php

// Load application
require('bootstrap.php');
$app = new Silex\Application();
$app['debug'] = false;

// Load config file
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__ . '/config/config.yaml'));

// Get database config based on env
$app['env'] = $app['config']['env'];
$databaseConfig = $app['config'][$app['env']]['database'];
if ($app['env'] === 'dev') {
    $app['debug'] = true;
}

// Setup database
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'dbname' => $databaseConfig['name'],
        'user' => $databaseConfig['user'],
        'password' => $databaseConfig['password'],
        'host' => $databaseConfig['host'],
        'driver' => $databaseConfig['driver']
    ),
));

// Setup views
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/resources/views',
));

// Define service providers
$app->register(new \Providers\DataServiceProvider());
$app->register(new \Providers\ControllerServiceProvider());
$app->register(new \Providers\StationServiceProvider());
$app->register(new \Providers\RatingServiceProvider());

// Define routes
$app->get('/{slug}', function ($slug) use ($app) {
    return $app['routingController']->renderBySlug($slug);
});
$app->get('/{lat}/{lon}', function ($lat, $lon) use ($app) {
    return $app['routingController']->renderByLatLon($lat, $lon);
});
$app->get('/', function (\Symfony\Component\HttpFoundation\Request $request) use ($app) {
    return $app['routingController']->renderByCookie($request);
});

return $app;