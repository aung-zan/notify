<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Manager;
use Tymon\JWTAuth\Factory;

class TokenService
{
    protected $jwt;
    protected $payloadFactory;

    public function __construct(Manager $jwt, Factory $payloadFactory)
    {
        $this->jwt = $jwt;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * Generate access token and refresh token.
     *
     * @param array $app
     * @param array $data
     * @return array
     */
    public function generateToken(array $app, array $data): array
    {
        $tokenClaims = $this->getTokenClaims($app, $data);
        $refreshTokenClaims = $this->getRefreshTokenClaims($tokenClaims);

        $tokenPayload = $this->payloadFactory->customClaims($tokenClaims)->make();
        $refreshTokenPayload = $this->payloadFactory->customClaims($refreshTokenClaims)->make();

        return [
            'token' => $this->jwt->encode($tokenPayload)->get(),
            'refresh_token' => $this->jwt->encode($refreshTokenPayload)->get()
        ];
    }

    /**
     * Get token claims for the access token.
     *
     * @param array $app
     * @param array $data
     * @return array
     */
    private function getTokenClaims(array $app, array $data): array
    {
        // TODO: change the expiration time to 15 minutes.
        return [
            'sub'  => $app['id'],
            'iat'  => time(),
            'exp'  => time() + 3600,
            'appId'  => $app['id'],
            'channels' => $data['channels'],
            'scopes' => $app['scopes'],
        ];
    }

    /**
     * Get token claims for the refresh token.
     *
     * @param array $tokenClaims
     * @return array
     */
    private function getRefreshTokenClaims(array $tokenClaims): array
    {
        return array_merge($tokenClaims, [
            'type' => 'refresh',
            'exp' => time() + (3600 * 24 * 30)
        ]);
    }
}
