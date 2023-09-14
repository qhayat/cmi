<?php

namespace App\Repository;

use App\Http\QueryParser\FilterQueryParser;
use App\Http\QueryParser\PageQueryParser;
use App\Http\QueryParser\SortQueryParser;

interface RepositoryInterface
{
    public function find($id, $lockMode = null, $lockVersion = null);

    public function total(FilterQueryParser $filterQueryParser): int;

    public function paginate(PageQueryParser $pageQueryParser, SortQueryParser $sortQueryParser, FilterQueryParser $filterQueryParser): array;
}
