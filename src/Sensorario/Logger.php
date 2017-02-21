<?php

namespace Sensorario;

class Logger
{
    private $handler;

    public function __construct()
    {
    }

    public function getHandler()
    {
        if (!$this->handler) {
            $this->handler = fopen(__DIR__ . '/../../logs/requests.log', 'a+');
        }

        return $this->handler;
    }


    public function logRequest(Request $request)
    {
        fwrite($this->getHandler(), "\n");
        fwrite($this->getHandler(), "\n> " . $request->getHttpVerb() . " " . $request->getRequestUri());
    }

    public function logResponse($content)
    {
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            fwrite($this->getHandler(), "\n< " . $line);
        }
    }

    public function __destruct()
    {
        fclose($this->getHandler());
    }
}
