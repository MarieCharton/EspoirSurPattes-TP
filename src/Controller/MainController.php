<?php

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Region;
use App\Repository\AnimalRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\RegionRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homePage(AnimalRepository $animalRepository, ArticleRepository $articleRepository)
    {

        $animals = $animalRepository->findLastPets();
        $articles = $articleRepository->findLastArticles();

        return $this->render('homepage/homepage.html.twig', [
            "animals" => $animals,
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsPage(CategoryRepository $categoryRepository, ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request,UserRepository $userRepository)
    {
        $authors = $userRepository ->findAuthors();

        $categories = $categoryRepository->findAll();

        $articles = $paginator->paginate(
            $articleRepository->findAllArticlesByDate(),
            $request->query->getInt('page', 1),
            4
        );


        return $this->render('news/news.html.twig', [
            "categories" => $categories,
            "articles" => $articles,
            "authors" => $authors
        ]);
    }

    /**
     * @Route("/map", name="map")
     */
    public function mapPage(RegionRepository $regionRepository)
    {
        $regions = $regionRepository->findAll();

        return $this->render('map/map.html.twig',[
            "regions"=>$regions
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactPage(MailerInterface $mailer)
    {

        if(isset($_POST) && isset($_POST['submit'])) {
 
            $email = (new Email())
                ->from($_POST['email'])
                ->to('contact.espoirsurpattes@gmail.com')
                ->subject('Espoir sur Pattes : message de ' .$_POST['name'])
                ->html
                (
                "<p><strong> Contenu du message : </strong> " . $_POST['message']. "</p>
                <p> Adresse mail de l'expediteur : " . $_POST['email'] . "</p>"
                );                
            $mailer->send($email);
            
            $this->addFlash('success', "Votre message a bien été envoyé ! ");
            return $this->redirectToRoute('contact');
            
    
        }  

        return $this->render('contact/contact.html.twig');
    }
}
