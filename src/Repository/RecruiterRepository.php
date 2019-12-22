<?php


namespace App\Repository;


use App\Entity\Recruiter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RecruiterRepository extends ServiceEntityRepository
{
    /**
     * @method Recruiter|null find($id, $lockMode = null, $lockVersion = null)
     * @method Recruiter|null findOneBy(array $criteria, array $orderBy = null)
     * @method Recruiter[]    findAll()
     * @method Recruiter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recruiter::class);
    }

    // /**
    //  * @return Possess[] Returns an array of Possess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Possess
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}