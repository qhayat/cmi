<?php

namespace App\Http\QueryParser;

use Symfony\Component\HttpFoundation\RequestStack;

class PageQueryParser extends AbstractQueryParser
{
    public const DEFAULT_NUMBER = 1;
    protected const PARAMETER_NAME = 'page';

    public function __construct(
        protected readonly RequestStack $requestStack,
        private readonly int $defaultPageSize
    ) {
        $this->parse();
    }

    public function getNumber(): int
    {
        if (null == $this->params) {
            return self::DEFAULT_NUMBER;
        }

        return $this->params->get('number', self::DEFAULT_NUMBER);
    }

    public function getSize(): int
    {
        if (null === $this->params) {
            return $this->defaultPageSize;
        }

        return $this->params->get('size', $this->defaultPageSize);
    }
}
