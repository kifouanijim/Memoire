<?php

namespace App\Repository;

use App\Entity\Sales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sales>
 */
class SalesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sales::class);
    }

//    /**
//     * @return Sales[] Returns an array of Sales objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sales
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function getSalesCountByDay(): array
{
    $conn = $this->getEntityManager()->getConnection();
    $sql = "
        SELECT DATE(sale_date) AS day, COUNT(id) AS sales_count
        FROM sales
        GROUP BY day
        ORDER BY day ASC
    ";
    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery();
    return $result->fetchAllAssociative();
}


public function getSalesCountByMonth(): array
{
    $conn = $this->getEntityManager()->getConnection();
    $sql = "
        SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month, COUNT(id) AS sales_count
        FROM sales
        GROUP BY DATE_FORMAT(sale_date, '%Y-%m')
        ORDER BY month ASC

    ";
    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery();
    return $result->fetchAllAssociative();
}


}
