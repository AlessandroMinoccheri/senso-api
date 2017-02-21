<?php

/** @todo add not allowed method */

namespace Sensorario;

use Exception;

class Response
{
    const BAD_REQUEST = 400;

    private static $statusToDescriptionMap = [
        self::BAD_REQUEST => 'Bad Request',
    ];

    private $params;

    private $output;

    private function __construct(array $params)
    {
        $this->params = $params;

        if (isset($this->params['error_message'])) {
            $this->output['error_message'] = $this->params['error_message'];
            $this->output['http_status'] = self::BAD_REQUEST;
            $this->output['description'] = self::$statusToDescriptionMap[self::BAD_REQUEST];
            $this->output['exception_class'] = $this->params['exception_class'];
        }

        $this->output['_links'][] = [
            'rel' => 'self',
            'url' => 'http://localhost:8080' . $this->params['request']->getRequestUri(),
        ];
    }

    public static function createFromException(
        Request $request,
        Exception $exception
    ) : Response {
        return new static([
            'request' => $request,
            'error_message' => $exception->getMessage(),
            'exception_class' => get_class($exception),
        ]);
    }

    public static function createFromRequest(Request $request) : Response
    {
        return new static([
            'request' => $request,
        ]);
    }

    public function __destruct()
    {
        $handler = fopen('php://output', 'w+');
        fputs($handler, json_encode($this->output));
        fclose($handler);

        exit(0);
    }
}
