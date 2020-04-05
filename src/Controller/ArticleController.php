<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\AnimalType;
use App\Form\ArticleType;
use App\Services\ImageUploader;
use App\Repository\AnimalRepository;
use App\Services\Slugger;
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
                $article->setImage("help.png");
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


            // $flashy->success("L'article a bien été ajouté");

                return $this->redirectToRoute("homepage");
            }

            return $this->render(
                "/form-article/create_article.html.twig", [
                    "formArticle" => $formArticle->createView()
                ]);

    }
}