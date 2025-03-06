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
        SELECT DATE(sale_date) AS day, 
               COUNT(id) AS sales_count, 
               SUM(quantity) AS total_phones_sold
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
        WITH months AS (
            SELECT DATE_FORMAT(sale_date, '%Y-%m') AS month, SUM(quantity) AS sales_count
            FROM sales
            GROUP BY month
        )
        SELECT m.month, COALESCE(s.sales_count, 0) AS sales_count
        FROM (
            SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL n MONTH), '%Y-%m') AS month
            FROM (SELECT 0 n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
                  UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 
                  UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11) t
        ) m
        LEFT JOIN months s ON m.month = s.month
        ORDER BY m.month ASC
    ";

    $stmt = $conn->prepare($sql);
    $result = $stmt->executeQuery();
    
    return $result->fetchAllAssociative();
}



}
