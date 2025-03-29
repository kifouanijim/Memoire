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
        // Récupérer le nombre de reviews par jour
        $qb = $this->createQueryBuilder('r')
            ->select("r.created_at AS day", "COUNT(r.id) AS review_count")
            ->groupBy('day')
            ->orderBy('day', 'ASC');
        
        $results = $qb->getQuery()->getResult();

        // Récupérer les commentaires et les utilisateurs associés
        $qbComments = $this->createQueryBuilder('r')
            ->select("r.created_at AS day", "r.commentaire", "u.username") // Récupérer le nom de l'utilisateur et le commentaire
            ->join('r.user', 'u') // Joindre avec la table des utilisateurs
            ->orderBy('day', 'ASC');
        
        $commentsResults = $qbComments->getQuery()->getResult();

        // Organiser les commentaires par utilisateur et par jour
        $commentsByUser = [];
        foreach ($commentsResults as $row) {
            $commentsByUser[$row['day']->format('Y-m-d')][$row['username']][] = $row['commentaire'];
        }

        // Ajouter les commentaires et les utilisateurs aux résultats principaux
        return array_map(fn($result) => [
            'day' => $result['day']->format('Y-m-d'), // Formatage de la date
            'review_count' => $result['review_count'],
            'comments_by_user' => $commentsByUser[$result['day']->format('Y-m-d')] ?? [] // Récupérer les commentaires de ce jour
        ], $results);
    }
    public function getSentimentStatistics(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT r.niveau FROM App\Entity\Reviews r'
        );

        $results = $query->getResult();

        $sentimentCounts = [];

        foreach ($results as $result) {
            foreach ($result['niveau'] as $sentiment) {
                if (isset($sentimentCounts[$sentiment])) {
                    $sentimentCounts[$sentiment]++;
                } else {
                    $sentimentCounts[$sentiment] = 1;
                }
            }
        }

        arsort($sentimentCounts); // Trie les sentiments du plus fréquent au moins fréquent

        return $sentimentCounts;
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
