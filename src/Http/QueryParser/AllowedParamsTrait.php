<?php

namespace App\Http\QueryParser;

use Symfony\Component\HttpFoundation\ParameterBag;

trait AllowedParamsTrait
{
    public function checkAllowedParams(ParameterBag $params, array $allowedParams): void
    {
        foreach ($params as $key => $value) {
            if (false === array_search($key, $allowedParams)) {
                $this->params->remove($key);
            }
        }
    }
}
