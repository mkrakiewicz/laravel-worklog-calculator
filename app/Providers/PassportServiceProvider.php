<?php

namespace App\Providers;

use App\TokenServer;
use App\CustomBearerTokenResponse;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;


class PassportServiceProvider extends \Laravel\Passport\PassportServiceProvider
{

    /**
     * Make the authorization service instance.
     *
     * @return AuthorizationServer
     */
    public function makeAuthorizationServer()
    {
        $server = new AuthorizationServer(
            $this->app->make(ClientRepository::class),
            $this->app->make(AccessTokenRepository::class),
            $this->app->make(ScopeRepository::class),
            'file://' . Passport::keyPath('oauth-private.key'),
            'file://' . Passport::keyPath('oauth-public.key'),
            $this->app->make(CustomBearerTokenResponse::class)
        );

        $server->setEncryptionKey(app('encrypter')->getKey());

        return $server;
    }
}