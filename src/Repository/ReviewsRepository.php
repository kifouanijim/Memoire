<?php

namespace App\Repository;

use App\Entity\Reviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reviews>
 */
class ReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reviews::class);
    }
    

    public function getReviewsCountByDay(): array
    {
        $qb = $this->createQueryBuilder('r')
            ->select('r.created_at AS day', 'COUNT(r.id) AS review_count')
            ->groupBy('r.created_at')
            ->orderBy('day', 'ASC');

        $results = $qb->getQuery()->getResult();

        // Formatage pour ne conserver que la date (année-mois-jour)
        foreach ($results as &$result) {
            $result['day'] = $result['day']->format('Y-m-d'); // Formater la date en format "YYYY-MM-DD"
        }

        return $results;
    }




//    /**
//     * @return Reviews[] Returns an array of Reviews objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Reviews
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
