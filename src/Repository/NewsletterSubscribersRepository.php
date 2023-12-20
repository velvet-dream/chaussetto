<?php

namespace App\Repository;

use App\Entity\NewsletterSubscribers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsletterSubscribers>
 *
 * @method NewsletterSubscribers|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsletterSubscribers|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsletterSubscribers[]    findAll()
 * @method NewsletterSubscribers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsletterSubscribersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSubscribers::class);
    }

//    /**
//     * @return NewsletterSubscribers[] Returns an array of NewsletterSubscribers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NewsletterSubscribers
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
