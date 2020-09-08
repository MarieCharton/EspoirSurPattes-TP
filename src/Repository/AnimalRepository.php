<?php

namespace App\Repository;

use App\Entity\Animal;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;


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

    public function findAnimalsByType($id): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
                                SELECT animal,type
                                FROM App\Entity\Animal animal
                                INNER JOIN animal.type type
                                WHERE type.id = $id
                                ORDER BY animal.createdAt DESC
        ");
        return $query;
    }

    public function findAnimalsByDepartment($departmentId): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
                                SELECT animal,department
                                FROM App\Entity\Animal animal
                                INNER JOIN animal.department department
                                WHERE animal.department = $departmentId 
                                ORDER BY animal.createdAt DESC
                                ");
                
        return $query;
    }
    public function findAnimalsByRegion($regionId): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
                                SELECT animal,department,region
                                FROM App\Entity\Animal animal
                                INNER JOIN animal.department department
                                INNER JOIN department.region region
                                WHERE department.region = $regionId 
                                ORDER BY animal.createdAt DESC
                                ");
                
        return $query;
    }
}
