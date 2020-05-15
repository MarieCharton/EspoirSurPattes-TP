<?php

namespace App\Controller;

use DateTime;
use App\Entity\Animal;
use App\Entity\Department;
use App\Entity\Region;
use App\Form\AnimalType;
use App\Services\ImageUploader;
use App\Repository\AnimalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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

    // !CRUD !//
    //? Create //
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

            //set the Region
            $region = $animal->getDepartment()->getRegion();
            $animal->setRegion($region);
            
            $manager->persist($animal);
            
            $manager->flush();

            $animalId = $animal->getId();

            $this->addFlash('success', "L'animal a bien été signalé");
            return $this->redirectToRoute("animal_view", ['id' => $animalId]);
        }

        return $this->render(
            "/form-animal/create_animal.html.twig",
            [
                "formAnimal" => $formAnimal->createView()
            ]
        );
    }

    //? Read //
    /**
     * @Route("/{id}/view", name="animal_view")
     */
    public function animalById(Animal $animal)
    {
        return $this->render(
            "animal/solo_animal.html.twig",
            [
                "animal" => $animal,
            ]
        );
    }

    //? Update //
    /**
     * @Route ("/updade/{id}",name ="animal_update")
     * @IsGranted("ROLE_USER")
     */
    public function updateAnimal(ImageUploader $imageUploader, Request $request, $id, Animal $animal)
    {
        $animalId = $animal->getId();
        $image = $animal->getImage();

        $this->denyAccessUnlessGranted('update', $animal);

        $formAnimal = $this->createForm(AnimalType::class, $animal);
        $formAnimal->handleRequest($request);

        if ($formAnimal->isSubmitted() && $formAnimal->isValid()) {

            $file = $formAnimal['image']->getData();

            // Animal Picture
            if (!empty($file)) {
                $fileName = $imageUploader->moveAndRename($file);
                $animal->setImage($fileName);
            } else {
                $animal->setImage($image);
            }
            $updatedAt = new DateTime();

            $animal->setUpdatedAt($updatedAt);

            $manager = $this->getDoctrine()->getManager();

            $manager->flush();

            $this->addFlash('success', "L'animal a bien été modifié");

            return $this->redirectToRoute('animal_view', ['id' => $animalId]);
        }

        return $this->render(
            "/form-animal/create_animal.html.twig",
            [
                "formAnimal" => $formAnimal->createView()
            ]
        );
    }

    //? Delete //
    /**
     * @Route ("/delete/{id}",name ="animal_delete")
     * @IsGranted("ROLE_USER")
     */
    public function deleteAnimal(Animal $animal)
    {
        $this->denyAccessUnlessGranted('delete', $animal);

        $this->getDoctrine()->getManager()->remove($animal);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', "Le signalement a bien été supprimé");

        return $this->redirectToRoute('map');
    }

    /**
     * @Route("/region/{id}/list", name="animal_by_region")
     */
    public function animalByRegion($id, Region $region, PaginatorInterface $paginator, Request $request)
    {
       
        $animalsByRegion = $paginator->paginate(
            $this->repository->findAnimalsByRegion($id),
            $request->query->getInt('page', 1),
            12
        );
        

        return $this->render("map/animals_by_region.html.twig", [
            "animalsByRegion" => $animalsByRegion,
            "region" => $region,

        ]);
    }

    /**
     * @Route("/departement/{id}/list", name="animal_by_department")
     */
    public function animalByDepartment($id, Department $department, PaginatorInterface $paginator, Request $request)
    {
       
        $animalsByDepartement = $paginator->paginate(
            $this->repository->findAnimalsByDepartment($id),
            $request->query->getInt('page', 1),
            12
        );
        

        return $this->render("map/animals_by_department.html.twig", [
            "animalsByDepartment" => $animalsByDepartement,
            "department" => $department,

        ]);
    }
}
