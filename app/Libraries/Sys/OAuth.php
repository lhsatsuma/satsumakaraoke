<?php

namespace App\Libraries\Sys;

use \OAuth2\Storage\Pdo;

class OAuth
{
    public \OAuth2\Server $server;
    private Pdo $storage;

    public function __construct()
    {
        $this->storage = new Pdo([
            'dsn' => getenv('database.default.dsn'),
            'username' => getenv('database.default.username'),
            'password' => getenv('database.default.password'),
        ]);
        $this->server = new \OAuth2\Server($this->storage);
        $this->server->addGrantType(new \OAuth2\GrantType\ClientCredentials($this->storage));
    }
}