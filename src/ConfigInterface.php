<?php

namespace DrinksIt\SfCacheClient;

interface ConfigInterface
{
    public function getToken(): string;
    public function getBasicAuthToken(): ?string;
    public function getFlushCacheUrl(): string;
}
