<?php

namespace App\Http\QueryParser;

interface QueryParserInterface
{
    public function getParams(): array;
}
