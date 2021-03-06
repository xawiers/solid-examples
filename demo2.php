<?php

use App\Manager\WeatherManager;
use App\Service\V1\WeatherUpdateService;
use App\Weather\CachedWeatherProvider;
use App\Weather\OpenWeatherProvider;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Dotenv\Dotenv;

require_once __DIR__.'/bootstrap.php';

$dotEnv = new Dotenv();
$dotEnv->load(__DIR__.'/.env');

$config = [
    'cache' => [
        'namespace' => 'weather',
        'ttl' => 3600,
        'directory' => __DIR__.'/data',
    ],
    'openWeather' => [
        'url' => 'http://api.openweathermap.org/data/2.5/weather?q=%LOCATION%&units=metric&appid=%APIKEY%',
        'apikey' => $_ENV['OPEN_WEATHER_API'],
    ],
];

$cacheAdapter = new PhpFilesAdapter(
    $config['cache']['namespace'],
    $config['cache']['ttl'],
    $config['cache']['directory']
);

$weatherProvider = new OpenWeatherProvider(
    $config['openWeather']['url'],
    $config['openWeather']['apikey']
);

$cachedWeatherProvider = new CachedWeatherProvider($cacheAdapter, $weatherProvider);

$weatherManager = new WeatherManager($entityManager);

$weatherUpdateService = new WeatherUpdateService($cachedWeatherProvider, $weatherManager);

$weatherInfo = $weatherUpdateService->downloadWeatherInfo('kaunas');

var_dump($weatherInfo);
