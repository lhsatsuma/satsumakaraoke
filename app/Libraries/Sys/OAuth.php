<?php

namespace App\Libraries\Sys;

use OAuth2\GrantType\ClientCredentials;
use OAuth2\Server;
use OAuth2\Storage\Pdo;
use App\Models\OAuthTokens\OAuthTokens;

class OAuth
{
    public Server $server;
    private Pdo $storage;

    public function __construct()
    {
        $this->storage = new Pdo([
            'dsn' => getenv('database.default.dsn'),
            'username' => getenv('database.default.username'),
            'password' => getenv('database.default.password'),
        ]);
        $this->server = new Server($this->storage);
        $this->server->addGrantType(new ClientCredentials($this->storage));
    }

    public function getRecord($access_token)
    {
        if(!$access_token){
            return false;
        }
        $mdl = new OAuthTokens();
        $mdl->where['access_token'] = $access_token;
        $result = $mdl->search(1);
        if($result[0]){
            return $result[0];
        }

        return [];
    }
}