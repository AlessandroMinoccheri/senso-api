<?php

namespace Sensorario;

class Request implements
    \JsonSerializable
{
    private $content;

    private function __construct(array $content)
    {
        $this->content = $content;
    }

    public function isRequestFromBrowser() : bool
    {
        return 'browser' === $this->content['client'];
    }

    public function isRequestFromCli() : bool
    {
        return !$this->isRequestFromBrowser();
    }

    public static function create() : Request
    {
        $isHttpRequest = static::isHttpRequest();

        $httpRequest = $isHttpRequest
            ? 'yes'
            : 'no';

        $client = ['browser','console'][!$isHttpRequest];

        $httpVerb = $isHttpRequest
            ? $_SERVER['REQUEST_METHOD']
            : 'not available';

        $postVars = $isHttpRequest ? $_POST : 'not available';
        $queryVars = $isHttpRequest ? $_GET : 'not available';

        return new Request([
            'http-request' => $httpRequest,
            'http-verb' => $httpVerb,
            'post-vars' => $postVars,
            'query-vars' => $queryVars,
            'client' => $client,
        ]);
    }

    public function getQueryVars()
    {
        return $this->content['query-vars'];
    }

    public function getPostVars()
    {
        return $this->content['post-vars'];
    }

    public function getHttpVerb()
    {
        return $this->content['http-verb'];
    }

    public function asArray()
    {
        return $this->content;
    }

    public function isRequestVerb($verb)
    {
        return $verb === $this->getHttpVerb();
    }

    public function isGetRequest()
    {
        return $this->isRequestVerb('GET');
    }

    public function isPostRequest()
    {
        return $this->isRequestVerb('POST');
    }

    public function isValidHttpVerb()
    {
        $validHttpVerbs = [
            'GET',
            'POST',
        ];

        return in_array(
            $this->getHttpVerb(),
            $validHttpVerbs
        );
    }

    public static function isHttpRequest()
    {
        return isset($_SERVER['REQUEST_METHOD']);
    }

    public function JsonSerialize()
    {
        return [
            '_GET', $_GET,
        ];
    }

    public static function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getPathInfo()
    {
        return $_SERVER['PATH_INFO'] ?? '/';
    }
}
