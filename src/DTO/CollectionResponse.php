<?php

namespace App\DTO;

use App\DTO\Collection\Meta;
use App\DTO\Collection\Page;
use App\Http\QueryParser\PageQueryParser;

class CollectionResponse
{
    public Meta $meta;

    /**
     * @param array<mixed> $data
     */
    public function __construct(
        public readonly array $data,
        private readonly int $total,
        private PageQueryParser $pageQueryParser
    ) {
        $pages = (int) floor($total / $pageQueryParser->getSize());

        $this->meta = new Meta(
            totalItems: $total,
            page: new Page(
                total: $pages === $total ? $pages : $pages + 1,
                current: $pageQueryParser->getNumber(),
                size: $pageQueryParser->getSize(),
            ),
        );
    }
}
