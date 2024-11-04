<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Test;

abstract class CommonGraphQlWithLoginTestCase extends GraphQlTestCase
{
    public const string DEFAULT_USER_EMAIL = 'no-reply@shopsys.com';
    public const string DEFAULT_USER_PASSWORD = 'user123';

    private string $currentAccessToken = '';

    protected function login(): void
    {
        if ($this->currentAccessToken === '') {
            $responseData = $this->getResponseContentForGql(__DIR__ . '/../Functional/Login/graphql/LoginMutation.graphql', [
                'email' => static::DEFAULT_USER_EMAIL,
                'password' => static::DEFAULT_USER_PASSWORD,
            ]);
            $this->currentAccessToken = $responseData['data']['Login']['tokens']['accessToken'];
        }

        $this->configureCurrentClient(
            null,
            null,
            [
                'CONTENT_TYPE' => 'application/graphql',
                'HTTP_X-Auth-Token' => sprintf('Bearer %s', $this->currentAccessToken),
            ],
        );
    }

    protected function logout(): void
    {
        $this->currentAccessToken = '';

        $this->configureCurrentClient(
            null,
            null,
            [
                'CONTENT_TYPE' => 'application/graphql',
            ],
        );
    }
}
