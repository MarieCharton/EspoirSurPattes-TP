<?php

namespace App\Controller;

use DateTime;
use App\Entity\Animal;
use App\Form\AnimalType;
use App\Services\ImageUploader;
use App\Repository\AnimalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/animal")
 */
class AnimalController extends AbstractController
{
    public function __construct(AnimalRepository $animalRepository)
    {
        $this->repository = $animalRepository;

    }

    /**
     * @Route("/create", name="animal_create")
     */
    public function createAnimal(Request $request, ImageUploader $imageUploader)
    {
        $animal = new Animal();

        $formAnimal = $this->createForm(AnimalType::class, $animal);

        $formAnimal->handleRequest($request);


        if ($formAnimal->isSubmitted() && $formAnimal->isValid()) {
            
      
            $manager = $this->getDoctrine()->getManager();

            $file = $formAnimal['image']->getData();

            
            //Animal Picture :
            if (!empty($file)) {

                $fileName = $imageUploader->moveAndRename($file);
                
                $animal->setImage($fileName);
                
            } else {
                $animal->setImage("LogoRond.png");
            }

            //Set the date automatically
            $animal->setCreatedAt(new DateTime());

            //Set the User:
            $user = $this->getUser();
            $animal->setUser($user);

            $manager->persist($animal);
            $manager->flush();

                $this->addFlash('success',"L'animal a bien été signalé");
                return $this->redirectToRoute("homepage");
        }

        return $this->render(
            "/form-animal/create_animal.html.twig", [

            "formAnimal" => $formAnimal->createView()
        ]);

    }
}