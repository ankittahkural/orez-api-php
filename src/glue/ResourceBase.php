<?php

namespace OwnerRez\Api\Glue;

class ResourceBase
{
    protected string $resourcePath;
    protected Service $service;

    public function __construct(Service $service, string $resourcePath) {
        $this->service = $service;
        $this->resourcePath = $resourcePath;
    }

    function getSanatizedPath(string $path)
    {
        return str_replace('//', '/', $path);
    }

    public function request(string $method, string $action = null, string $id = null, array $queryOrFormData = null, $body = null)
    {
        $numericId = preg_replace("/[^0-9]/", "", $id);
        $path = $this->getSanatizedPath($this->resourcePath . '/' . $numericId . '/' . $action);

        $options = null;

        if ($body != null) {
            $options = [ 'body' => json_encode($body) ];
        }
        else {
            $attachAt = 'json';

            if (strcasecmp($method, 'get') == 0 or $body != null)
                $attachAt = 'query';

            $options = [ $attachAt => $queryOrFormData ];
        }

        return $this->service->request(strtoupper($method), $path, $options)->getBody();
    }
}