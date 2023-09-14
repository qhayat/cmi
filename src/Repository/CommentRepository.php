<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Http\QueryParser\FilterQueryParser;
use App\Http\QueryParser\PageQueryParser;
use App\Http\QueryParser\SortQueryParser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository implements RepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return Comment[]
     */
    public function paginate(PageQueryParser $pageQueryParser, SortQueryParser $sortQueryParser, FilterQueryParser $filterQueryParser): array
    {
        $pageSize = $pageQueryParser->getSize();
        $query = $this->createQueryBuilder('c')
            ->setMaxResults($pageSize)
            ->setFirstResult(($pageQueryParser->getNumber() - 1) * $pageSize);

        $this->applyFilterOnQuery($query, $filterQueryParser);
        $this->applySortOnQuery($query, $sortQueryParser);

        return $query
            ->getQuery()
            ->getResult();
    }

    public function total(FilterQueryParser $filterQueryParser): int
    {
        $query = $this->createQueryBuilder('c')
            ->select('COUNT(c.id)');

        $this->applyFilterOnQuery($query, $filterQueryParser);

        return $query
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function applySortOnQuery(QueryBuilder $queryBuilder, SortQueryParser $sortQueryParser): void
    {
        foreach ($sortQueryParser->getParams() as $key => $value) {
            $queryBuilder->orderBy(sprintf('c.%s', $key), $value);
        }
    }

    private function applyFilterOnQuery(QueryBuilder $queryBuilder, FilterQueryParser $sortQueryParser): void
    {
        foreach ($sortQueryParser->getParams() as $key => $value) {
            $queryBuilder
                ->andWhere(sprintf('c.%s = :%s', $key, $key))
                ->setParameter($key, $value);
        }
    }
}
