<?php

namespace App\Http\QueryParser;

use Symfony\Component\HttpFoundation\RequestStack;

class FilterQueryParser extends AbstractQueryParser
{
    use AllowedParamsTrait;

    protected const PARAMETER_NAME = 'filter';

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
    }
}
