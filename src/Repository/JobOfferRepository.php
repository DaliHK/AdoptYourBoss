<?php

namespace App\Repository;

use App\Entity\JobOffer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JobOffer|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobOffer|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobOffer[]    findAll()
 * @method JobOffer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobOfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobOffer::class);
    }

     /**
      * @return JobOffer[] Returns an array of JobOffer objects
      */
    public function findByRecruiter($recruiter)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.recruiter = :id')
            ->setParameter('id', $recruiter)
            ->orderBy('j.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    // public function findByRecruiter($recruiter)
    // {
    //         $qb = $this->createQueryBuilder('q')
    //         ->select('q')
    //         ->leftJoin('q.recruiter', 'r') 
    //         ->Where('r.id  = :id')
    //         ->setParameter('id', $recruiter)
    //         ->getQuery()
    //         ->getResult();

    //         return $qb;
    // }

}
