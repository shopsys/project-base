<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Functional\Login;

use Shopsys\FrontendApiBundle\Model\Token\Exception\InvalidTokenUserMessageException;
use Shopsys\FrontendApiBundle\Model\Token\TokenFacade;
use Tests\FrontendApiBundle\Test\GraphQlTestCase;
use Throwable;

class LoginTest extends GraphQlTestCase
{
    /**
     * @inject
     */
    protected TokenFacade $tokenFacade;

    public function testLoginMutation(): void
    {
        $graphQlType = 'Login';

        $response = $this->getResponseContentForGql(
            __DIR__ . '/graphql/LoginMutation.graphql',
            $this->getDefaultCredentials(),
        );
        $responseData = $this->getResponseDataForGraphQlType($response, $graphQlType);

        $this->assertArrayHasKey('tokens', $responseData);
        $this->assertIsString($responseData['tokens']['accessToken']);

        $this->assertArrayHasKey('tokens', $responseData);
        $this->assertIsString($responseData['tokens']['refreshToken']);

        try {
            $this->tokenFacade->getTokenByString($responseData['tokens']['accessToken']);
        } catch (Throwable) {
            $this->fail('Token is not valid');
        }

        $clientOptions = ['HTTP_X-Auth-Token' => sprintf('Bearer %s', $responseData['tokens']['accessToken'])];
        $this->configureCurrentClient(null, null, $clientOptions);

        $authorizationResponse = $this->getResponseContentForGql(
            __DIR__ . '/graphql/LoginMutation.graphql',
            $this->getDefaultCredentials(),
        );
        $authorizationResponseData = $this->getResponseDataForGraphQlType($authorizationResponse, $graphQlType);

        $this->assertArrayHasKey('tokens', $authorizationResponseData);
        $this->assertIsString($authorizationResponseData['tokens']['accessToken']);

        $this->assertArrayHasKey('tokens', $authorizationResponseData);
        $this->assertIsString($authorizationResponseData['tokens']['refreshToken']);
    }

    public function testInvalidTokenException(): void
    {
        $this->expectException(InvalidTokenUserMessageException::class);
        $this->tokenFacade->getTokenByString('abcd');
    }

    public function testInvalidTokenInHeader(): void
    {
        $expectedError = [
            'errors' => [
                [
                    'message' => 'Token is not valid.',
                    'extensions' => [
                        'userCode' => 'invalid-token',
                    ],
                ],
            ],
        ];

        $this->configureCurrentClient(null, null, ['HTTP_X-Auth-Token' => 'Bearer 123']);

        $response = $this->getResponseContentForGql(
            __DIR__ . '/graphql/LoginMutation.graphql',
            $this->getDefaultCredentials(),
        );

        $this->assertSame($expectedError, $response);
    }

    /**
     * @return array<string, string>
     */
    private function getDefaultCredentials(): array
    {
        return [
            'email' => 'no-reply@shopsys.com',
            'password' => 'user123',
        ];
    }
}
