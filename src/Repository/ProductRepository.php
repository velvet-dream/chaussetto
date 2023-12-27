<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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


    public function getProductByCategory( string $categoryName){
        return $this->createQueryBuilder('p')
        ->innerJoin('p.categories','c')
        ->andWhere('c.label = :categoryName')
        ->setParameter('categoryName',$categoryName)
        ->getQuery()
        ->getResult();
    }

    public function getTaxes( string $categoryName){
        return $this->createQueryBuilder('p')
        ->innerJoin('p.categories','c')
        ->andWhere('c.label = :categoryName')
        ->setParameter('categoryName',$categoryName)
        ->getQuery()
        ->getResult();
    }



    public function save(Product $product): Product
    {
        $this->getEntityManager()->persist($product);
        $this->getEntityManager()->flush();
        return $product;
    }

    public function findAvailableProductsForPromotion()
    {
        return $this->createQueryBuilder('p')
           ->andWhere('p.promotion is null')
           ->getQuery()
           ->getResult()
        ;
    }

    public function searchByName(string $name, string $triName) : ?array
    {
        return $this->createQueryBuilder('p')
            ->where('p.name like :val')
            ->setParameter('val' , '%'.$name.'%')
            ->addOrderBy('p.name', $triName)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
