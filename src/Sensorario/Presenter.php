<?php

namespace Sensorario;

class Presenter
{
    private $params;

    private function __construct(array $params)
    {
        $this->pre = '';
        $this->post = '';
        if (true == $params['show-html']) {
            $this->pre = '<pre>';
            $this->post = '</pre>';
        }

        $this->content = $params['content'];
    }

    public static function fromRequest(Request $request)
    {
        $content = json_encode(
            $request->asArray(),
            JSON_PRETTY_PRINT
        );

        return new Presenter([
            'content' => $content,
            'show-html' => $request->isRequestFromBrowser(),
        ]);
    }

    public function getContent()
    {
        return $this->pre
            . $this->content
            . $this->post;
    }
}
