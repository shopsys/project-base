<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Functional\Settings;

use App\Model\Order\Order;
use Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade;
use Tests\FrontendApiBundle\Test\GraphQlTestCase;

final class GetSettingsTest extends GraphQlTestCase
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Seo\SeoSettingFacade
     * @inject
     */
    private readonly SeoSettingFacade $seoSettingFacade;

    /**
     * @dataProvider dataProvider
     * @param string|null $robotsTxtContent
     * @param string|null $robotsTxtData
     */
    public function testGetSettings(?string $robotsTxtContent, ?string $robotsTxtData): void
    {
        $this->seoSettingFacade->setRobotsTxtContent($robotsTxtContent, $this->domain->getId());
        $expectedSettingsData = $this->getExpectedSettings($robotsTxtData);

        $graphQlType = 'settings';
        $response = $this->getResponseContentForGql(__DIR__ . '/graphql/SettingsQuery.graphql');
        $responseData = $this->getResponseDataForGraphQlType($response, $graphQlType);

        self::assertSame($expectedSettingsData, $responseData);
    }

    /**
     * @return array
     */
    public function dataProvider(): array
    {
        return [
            [
                null,
                null,
            ],
            [
                'Disallow: /private',
                'Disallow: /private',
            ],
            [
                sprintf('Disallow: /private-one%sDisallow: /private-two', PHP_EOL),
                <<<CONTENT
Disallow: /private-one
Disallow: /private-two
CONTENT,
            ],
        ];
    }

    /**
     * @param string|null $data
     * @return array
     */
    private function getExpectedSettings(?string $data): array
    {
        return [
            'seo' => [
                'robotsTxtContent' => $data,
            ],
            'maxAllowedPaymentTransactions' => Order::MAX_TRANSACTION_COUNT,
        ];
    }
}
