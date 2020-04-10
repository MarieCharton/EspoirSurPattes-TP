<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Category;
use App\Form\AnimalType;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Services\Slugger;
use App\Services\ImageUploader;
use App\Repository\AnimalRepository;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    public function __construct(AnimalRepository $animalRepository)
    {
        $this->repository = $animalRepository;

    }

    /**
     * @Route("/list/{id}/category/", name="articles__by_category")
     */
    public function articlesByCategory(PaginatorInterface $paginator, Request $request, $id,ArticleRepository $articleRepository)
    {
        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);
        $category = $categoriesRepository->find($id);
        $categoryId = $category->getId();



        $articles = $paginator->paginate(
            $articleRepository->findArticlesByCategory($categoryId),
            $request->query->getInt('page', 1),
            4
        );

        return $this->render("news/articles_by_category.html.twig", [
            "articles" => $articles,
            "category" => $category,
        ]);
    }


    // !CRUD !//
    /**
     * @Route("/create", name="article_create")
     */
    public function createArticle(Request $request, ImageUploader $imageUploader,Slugger $slugger)
    {
        $article = new Article();

        $formArticle = $this->createForm(ArticleType::class, $article);

        $formArticle->handleRequest($request);


        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            
      
            $manager = $this->getDoctrine()->getManager();

            $file = $formArticle['image']->getData();

            //Article Picture :
            if (!empty($file)) {

                $fileName = $imageUploader->moveAndRename($file);
                
                $article->setImage($fileName);
                
            } else {
                $article->setImage("article.jpg");
            }

            //Slugify the Title for the URL
            $slug = $slugger->slugify($article->getTitle());
            $article->setSlug($slug);

            //Set the date automatically
            $article->setCreatedAt(new DateTime());

            //Set the User:
            $user = $this->getUser();
            $article->setUser($user);

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success',"L'article a bien été ajouté");
            return $this->redirectToRoute('article_view', ['slug' => $slug]);
        }

            return $this->render(
                "/form-article/create_article.html.twig", [
                    "formArticle" => $formArticle->createView()
                ]);

    }
    
    /**
     * @Route("/{slug}/view", name="article_view")
     */
    public function articleByslug(Article $article,Request $request)
    {

        $slug = $article->getSlug();

        $comments = $article->getComments();

        $newComment = new Comment();
        $newComment->setArticle($article);

        $formComment = $this->createForm(CommentType::class, $newComment);
        $formComment->handleRequest($request);

        if ($formComment->isSubmitted() && $formComment->isValid()) {

            $manager = $this->getDoctrine()->getManager();

            //Set the date 
            $newComment->setCreatedAt(new DateTime());

            //Set the User:
            $user = $this->getUser();
            $newComment->setUser($user);

            $manager->persist($newComment);
            $manager->flush();

            $this->addFlash("success", "Le commentaire a bien été ajouté");

            return $this->redirectToRoute('article_view', ['slug' => $slug]);
        }

        return $this->render(
            "news/article.html.twig",[
                "article" => $article,
                "comments" => $comments,
                "formComment" => $formComment->createView()
            ]);
    }
}