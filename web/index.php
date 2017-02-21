<?php

require __DIR__ . '/../vendor/autoload.php';

use Sensorario\Logger;
use Sensorario\Presenter;
use Sensorario\Request;
use Sensorario\Resources\Configurator;
use Sensorario\Resources\Container;
use Sensorario\Resources\Resource;
use Sensorario\Response;

$request = Request::create();
$presenter = Presenter::fromRequest($request);
$logger = new Logger();

/** @todo use slim as routing component */
/** @todo add pimple as DI container */
/** @todo add an application component */

try {
    $variables = [];

    if ($request->isRequestFromBrowser()) {
        if ($request->isValidHttpVerb()) {
            if ($request->isGetRequest()) {
                $variables = $request->getQueryVars();
                $logger->logRequest($request);
            } elseif ($request->isPostRequest()) {
                $variables = $request->getPostVars();
            } else {
                throw new \RuntimeException(
                    'Oops!'
                );
            }
        } else {
            throw new \RuntimeException(
                'Oops! VERB '
                . $request->getHttpVerb()
                . ' is not valid'
            );
        }
    }

    if ($request->isRequestFromCli())
    {
        if ($argc > 1) {
            $params = [];

            foreach ($argv as $argumentIndex => $argument) {
                if ($argumentIndex > 0) {
                    if ($argumentIndex < $argc) {
                        if (!preg_match("/^--[a-z][\w]+=.*$/", $argument)) {
                            throw new \RuntimeException(
                                'Oops! Wrong parameter: ' . $argument
                            );
                        } else {
                            $params[] = $argument;
                        }
                    }
                }
            }

            foreach ($params as $key => $value) {
                preg_match("/^--([a-z][\w]+)=(.*)$/", $value, $matches);
                $variables[$matches[1]] = $matches[2];
            }
        }
    }

    $config = require_once(__DIR__ . '/../config/config.php');

    if (
        isset($config['container']['resources'][$request->getPathInfo()]) && 
        !in_array(
            $request->getHttpVerb(),
            $config['container']['resources'][$request->getPathInfo()]['options']
        )
    ) {
        throw new \RuntimeException(
            'Oops! Method '. $request->getHttpVerb() .' not allowed. Allowed methods: ' . var_export($config['container']['resources'][$request->getPathInfo()], true)
        );
    }

    $resource = Resource::box(
        $variables, //json_decode($request, true),
        new Configurator(
            $request->getPathInfo(),
            new Container($config['container'])
        )
    );

    if ($request->isHttpRequest()) {
        $responses = require_once(__DIR__ . '/../config/responses.php');

        $handler = fopen(__DIR__ . '/../logs/requests.log', 'a+');
        $logger->logRequest($request);
        $logger->logResponse(
            $content = json_encode(
                $responses[$request->getPathInfo()],
                JSON_PRETTY_PRINT
            )
        );


        echo $content;

        exit(0);

        //Response::createFromRequest($request);
    }

} catch (\Exception $exception) {
    Response::createFromException(
        $request,
        $exception
    );
}
