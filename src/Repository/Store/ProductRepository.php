<?php

namespace App\Repository\Store;

use App\Entity\Store\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findeOneWithDetails(int $id): ?Product
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id= :id')
            ->setParameter('id', $id);

        $this->addJoinImage($qb);
        $this->addJoinComments($qb);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findLatestProducts(): ?array
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->orderBy('p.createdAt', 'DESC')
            ->setMaxResults(4);

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    public function findMostPopularProducts(): ?array
    {
        $qb =  $this
            ->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->groupBy('p')
            ->orderBy('COUNT(c.id)', 'DESC')
            ->setMaxResults(4);

        $this->addJoinImage($qb);

        return $qb->getQuery()->getResult();
    }

    public function addJoinImage(QueryBuilder $qb): void
    {
        $qb
            ->addSelect('c')
            ->leftJoin('p.image', 'c')
        ;
    }

    public function addJoinComments(QueryBuilder $qb)
    {
        $qb
            ->addSelect('i')
            ->innerJoin('p.comments', 'i')
        ;
    }

    /**
     * @return Product[]
     */
    public function findAllWithDetails(): array
    {
        $qb = $this->createQueryBuilder('p');
        $this->addJoinImage($qb);
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findProductsByBrand(){

    }
}
