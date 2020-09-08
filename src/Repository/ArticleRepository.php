<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;



class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findLastArticles()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
            SELECT article  
            FROM App\Entity\Article article 
            ORDER BY article.createdAt DESC 
            ")
            ->setMaxResults(12);

        return $query->getResult();
    }

    /**
     * @return Query
     */
    public function findAllNewArticles(): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
            SELECT article  
            FROM App\Entity\Article article 
            ORDER BY article.createdAt DESC 
            ");

        return $query;
    }

        /**
     * @return Query
     */
    public function findAllOldArticles(): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
            SELECT article  
            FROM App\Entity\Article article 
            ORDER BY article.createdAt ASC 
            ");

        return $query;
    }

    /**
     * @return Query
     */
    public function findArticlesByCategory($id): Query
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery("
            SELECT article,category
            FROM App\Entity\Article article
            INNER JOIN article.categories category
            WHERE category.id = $id
            ORDER BY article.createdAt DESC
        ");
        return $query;
    }

}
