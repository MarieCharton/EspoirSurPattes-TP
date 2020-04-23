<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAuthors()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
        SELECT DISTINCT user.username
        FROM App\Entity\Article article
        INNER JOIN article.user user
        WHERE article.user IS NOT NULL
        ORDER BY user.username DESC
        ");
                
        return $query->getResult();
    }
}

