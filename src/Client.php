<?php

namespace DrinksIt\SfCacheClient;

class Client
{
    private ConfigInterface $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $tags
     * @throws \ErrorException
     */
    public function invalidateTags(array $tags): void
    {
        $data = [
            'token' => $this->config->getToken(),
            'tags' => $tags,
        ];
        $data = http_build_query($data);

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
            'Content-Length: ' . strlen($data),
            'User-Agent: Micro Cache Client'
        ];

        $basicAuthToken = $this->config->getBasicAuthToken();
        if ($basicAuthToken) {
            $headers[] = 'Authorization: Basic ' . $basicAuthToken;
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => $headers,
                'content' => $data,
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        if (false === $data = @file_get_contents($this->config->getFlushCacheUrl(), false, $context)) {
            $error = error_get_last();
            throw new \ErrorException($error['message'], $error['type']);
        }
    }
}
