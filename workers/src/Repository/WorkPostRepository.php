<?php

namespace App\Repository;

use App\Entity\WorkPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WorkPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkPost[]    findAll()
 * @method WorkPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WorkPost::class);
    }

    // /**
    //  * @return WorkPost[] Returns an array of WorkPost objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkPost
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
