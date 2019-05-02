<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Weather\Controller\StartPage;

$request = Request::createFromGlobals();

$loader = new FilesystemLoader('View', __DIR__ . '/src/Weather');
$twig = new Environment($loader, ['cache' => __DIR__ . '/cache', 'debug' => true]);

$controller = new StartPage();
switch ($request->getRequestUri()) {
    case '/week':
        $renderInfo = $controller->getWeekWeather('db');
        break;
    case '/g-week':
        $renderInfo = $controller->getWeekWeather('googleApi');
        break;
    case '/f-week':
        $renderInfo = $controller->getWeekWeather();
        break;
    case '/g-day':
        $renderInfo = $controller->getTodayWeather('googleApi');
        break;
    case '/f-day':
        $renderInfo = $controller->getTodayWeather();
        break;
    case '/':
    default:
        $renderInfo = $controller->getTodayWeather('db');
    break;
}
$renderInfo['context']['resources_dir'] = 'src/Weather/Resources';

$content = $twig->render($renderInfo['template'], $renderInfo['context']);

$response = new Response(
    $content,
    Response::HTTP_OK,
    array('content-type' => 'text/html')
);
$response->send();
