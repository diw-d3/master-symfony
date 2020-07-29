<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Tools\Pagination\Paginator;
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

    public function findAllGreatherThanPrice($price): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.price > :price')
            ->setParameter('price', $price * 100)
            ->orderBy('p.price', 'ASC')
            ->getQuery();

        return $queryBuilder->getResult();
    }

    public function findOneGreatherThanPrice($price): ?Product
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.price > :price')
            ->setParameter('price', $price * 100)
            ->orderBy('p.price', 'ASC')
            ->getQuery();

        return $queryBuilder->setMaxResults(1)->getOneOrNullResult();
    }

    public function findOneExpensive(): ?Product
    {
        // 'SELECT * FROM product p WHERE p.price = (SELECT MAX(product.price) FROM product);';
        $subquery = $this->createQueryBuilder('p1')
            ->select('MAX(p1.price)');

        $queryBuilder = $this->createQueryBuilder('p2')
            ->where(
                'p2.price = (SELECT MAX(p1.price) FROM App\Entity\Product p1)'
            )
            ->getQuery();

        return $queryBuilder->getOneOrNullResult();
    }

    public function findAllAsPDO($price)
    {
        $db = $this->getEntityManager()->getConnection();

        $sql = 'SELECT * FROM product WHERE price > :price';
        $stmt = $db->prepare($sql);
        $stmt->execute(['price' => $price]);

        return $stmt->fetchAll(FetchMode::CUSTOM_OBJECT, Product::class);
    }

    public function findAllWithPagination(int $page)
    {
        $q = $this->createQueryBuilder('p')
            ->setFirstResult(($page - 1) * 10)
            ->setMaxResults(10);

        // return $q->getQuery()->getResult();
        return new Paginator($q);
    }
}
