<?php

namespace App\Repository;

use App\Entity\JsonPlaceholderUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JsonPlaceholderUser>
 *
 * @method JsonPlaceholderUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method JsonPlaceholderUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method JsonPlaceholderUser[]    findAll()
 * @method JsonPlaceholderUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JsonPlaceholderUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JsonPlaceholderUser::class);
    }

    //    /**
    //     * @return JsonPlaceholderUser[] Returns an array of JsonPlaceholderUser objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JsonPlaceholderUser
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
