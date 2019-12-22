<?php

namespace App\Repository;

use App\Entity\OfferSkill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OfferSkill|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferSkill|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferSkill[]    findAll()
 * @method OfferSkill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferSkillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferSkill::class);
    }

    // /**
    //  * @return OfferSkill[] Returns an array of OfferSkill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OfferSkill
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
