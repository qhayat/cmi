<?php

namespace App\Http\QueryParser;

use Symfony\Component\HttpFoundation\RequestStack;

class SortQueryParser extends AbstractQueryParser
{
    use AllowedParamsTrait;

    protected const PARAMETER_NAME = 'sort';

    public function __construct(
        protected readonly RequestStack $requestStack,
        private readonly array $allowedParams
    ) {
        $this->parse();
    }

    protected function parse(): void
    {
        parent::parse();

        $this->checkAllowedParams($this->params, $this->allowedParams);

        foreach ($this->params as $key => $value) {
            if (!\defined(SortDirection::class.'::'.\strtoupper($value))) {
                throw new \InvalidArgumentException(\sprintf('Invalid sort value "%s" for key "%s". Allowed values: %s', $value, $key, \implode(', ', SortDirection::toArray())));
            }
        }
    }
}
