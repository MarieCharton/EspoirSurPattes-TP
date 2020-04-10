<?php

namespace App\Controller;


use App\Entity\Category;
use App\Repository\AnimalRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(AnimalRepository $animalRepository, ArticleRepository $articleRepository)
    {

        $animals = $animalRepository->findLastPets();
        $articles = $articleRepository->findLastArticles();

        return $this->render('homepage/homepage.html.twig',[
            "animals" => $animals,
            "articles" =>$articles
        ]);
    }

    /**
     * @Route("/news", name="news")
     */
    public function showNewsPage(CategoryRepository $categoryRepository,ArticleRepository $articleRepository,PaginatorInterface $paginator,Request $request)
    {

        $categories = $categoryRepository->findAll();
       
        $articles = $paginator->paginate(
            $articleRepository->findAllArticlesByDate(),
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('news/news.html.twig',[
            "categories" => $categories,
            "articles" => $articles
        ]);
    }
    
}

