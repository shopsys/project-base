<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Test;

use Symfony\Component\HttpFoundation\Response;
use Tests\ShopBundle\Test\FunctionalTestCase;

abstract class GraphQlTestCase extends FunctionalTestCase
{
    protected function setUp(): void
    {
        $frontendApiRouteLoader = $this->getContainer()->get('shopsys.frontend_api.route_loader');

        if (!$frontendApiRouteLoader->isEnabledOnCurrentDomain()) {
            $this->markTestSkipped('Frontend API disabled on domain');
        }
    }

    /**
     * @param string $query
     * @param string $jsonExpected
     * @param string $jsonVariables
     */
    protected function assertQueryWithExpectedJson(string $query, string $jsonExpected, $jsonVariables = '{}'): void
    {
        $this->assertQueryWithExpectedArray($query, json_decode($jsonExpected, true), json_decode($jsonVariables, true));
    }

    /**
     * @param string $query
     * @param array $expected
     * @param array $variables
     */
    protected function assertQueryWithExpectedArray(string $query, array $expected, array $variables = []): void
    {
        $response = $this->getResponseForQuery($query, $variables);

        $this->assertSame(200, $response->getStatusCode());

        $result = $response->getContent();
        $this->assertEquals($expected, json_decode($result, true), $result);
    }

    /**
     * @param string $query
     * @param array $variables
     * @return array
     */
    protected function getResponseContentForQuery(string $query, array $variables = []): array
    {
        $content = $this->getResponseForQuery($query, $variables)->getContent();

        return json_decode($content, true);
    }

    /**
     * @param string $query
     * @param array $variables
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    private function getResponseForQuery(string $query, array $variables): ?Response
    {
        $client = $this->getClient(true);
        $path = $this->getContainer()->get('router')->generate('overblog_graphql_endpoint');

        $client->request(
            'GET',
            $path,
            ['query' => $query, 'variables' => json_encode($variables)],
            [],
            ['CONTENT_TYPE' => 'application/graphql']
        );

        return $client->getResponse();
    }
}
