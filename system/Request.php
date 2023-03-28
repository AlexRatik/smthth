<?php

namespace system;

class Request
{
    private string $method;
    private string $url;
    private array $queryParams;
    private array|null $postParams;
    private array $cookies;


    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->postParams = json_decode(file_get_contents('php://input'), true) ?? null;
        $this->cookies = $_COOKIE;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getPostParams(): array
    {
        return $this->postParams;
    }

    public function getCookies(): array
    {
        return $this->cookies;
    }
}