<?php

declare(strict_types=1);

namespace App\Component\Elasticsearch;

use stdClass;

/**
 * Heavily inspired by @see \App\Model\Product\Search\FilterQuery
 *
 * @see https://github.com/shopsys/shopsys/issues/2362
 */
abstract class AbstractFilterQuery
{
    protected const DEFAULT_LIMIT = 1000;
    protected const DEFAULT_FROM = 0;

    /**
     * @var array
     */
    protected array $filters = [];

    /**
     * @var string
     */
    protected string $indexName;

    /**
     * @var array
     */
    protected array $sorting = [];

    /**
     * @var int
     */
    protected int $limit = self::DEFAULT_LIMIT;

    /**
     * @var array
     */
    protected array $match;

    /**
     * @var int
     */
    protected int $from = self::DEFAULT_FROM;

    /**
     * @param string $indexName
     */
    public function __construct(string $indexName)
    {
        $this->indexName = $indexName;
        $this->match = [
            'match_all' => new stdClass(),
        ];
    }

    /**
     * @param int|null $limit
     * @return \App\Component\Elasticsearch\AbstractFilterQuery
     */
    public function setLimit(?int $limit): self
    {
        $clone = clone $this;
        $clone->limit = $limit ?? static::DEFAULT_LIMIT;

        return $clone;
    }

    /**
     * @param int|null $from
     * @return \App\Component\Elasticsearch\AbstractFilterQuery
     */
    public function setFrom(?int $from): self
    {
        $clone = clone $this;
        $clone->from = $from ?? static::DEFAULT_FROM;

        return $clone;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return [
            'index' => $this->indexName,
            'body' => [
                'from' => $this->from,
                'size' => $this->limit,
                'sort' => $this->sorting,
                'query' => [
                    'bool' => [
                        'must' => $this->match,
                        'filter' => $this->filters,
                    ],
                ],
            ],
        ];
    }
}