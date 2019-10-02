<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Functional\Category;

use Tests\FrontendApiBundle\Test\GraphQlTestCase;

class CategoryTreeTest extends GraphQlTestCase
{
    public function testRootCategories(): void
    {
        $query = '
            query {
                categoryTree {
                    name
                }
            }
        ';

        $arrayExpected = [
            'data' => [
                'categoryTree' => [
                    ['name' => 'Electronics'],
                    ['name' => 'Books'],
                    ['name' => 'Toys'],
                    ['name' => 'Garden tools'],
                    ['name' => 'Food'],
                ],
            ],
        ];

        $this->assertQueryWithExpectedArray($query, $arrayExpected);
    }

    public function testChildCategories(): void
    {
        $query = '
            query {
                categoryTree {
                    name
                    children {
                        name
                    }
                }
            }
        ';

        $output = $this->getResponseContentForQuery($query);

        $expected = [
            'name' => 'Electronics',
            'children' => [
                ['name' => 'TV, audio'],
                ['name' => 'Cameras & Photo'],
                ['name' => 'Printers'],
                ['name' => 'Personal Computers & accessories'],
                ['name' => 'Mobile Phones'],
                ['name' => 'Coffee Machines'],
            ],
        ];

        $this->assertEquals($expected, $output['data']['categoryTree'][0]);
    }
}
