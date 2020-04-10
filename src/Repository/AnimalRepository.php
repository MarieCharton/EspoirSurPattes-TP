<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;


class AnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Animal::class);
    }


    public function findLastPets()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
                                SELECT animal
                                FROM App\Entity\Animal animal
                                ORDER BY animal.createdAt DESC
                                ")
                                ->setMaxResults(5);

        return $query->getResult();
    }
}
