<?php

namespace App\Repository;

use App\Entity\ReadingSheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReadingSheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReadingSheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReadingSheet[]    findAll()
 * @method ReadingSheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReadingSheetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReadingSheet::class);
    }

//    /**
//     * @return ReadingSheet[] Returns an array of ReadingSheet objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReadingSheet
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
