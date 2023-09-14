<?php

namespace App\Http\QueryParser;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractQueryParser implements QueryParserInterface
{
    protected const PARAMETER_NAME = null;
    protected ?ParameterBag $params = null;

    public function __construct(protected readonly RequestStack $requestStack)
    {
        $this->parse();
    }

    public function getParams(): array
    {
        if (null === $this->params) {
            $this->parse();
        }

        return $this->params->getIterator()->getArrayCopy();
    }

    protected function parse(): void
    {
        $params = [];
        $query = $this->requestStack->getCurrentRequest()->query;

        if (null !== static::PARAMETER_NAME && $query->has(static::PARAMETER_NAME ?? '') && \is_array($query->all()[static::PARAMETER_NAME])) {
            $params = $query->all()[static::PARAMETER_NAME];
        }

        $this->params = new ParameterBag($params);
    }
}
