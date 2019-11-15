<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function countByCategory(Category $category): int
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a) as total')
            ->where('a.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByCategoryName(string $name): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.category', 'c')
            ->where('c.name = :categoryName')
            ->setParameter('categoryName', $name)
            ->getQuery()
            ->getResult();
    }

}
