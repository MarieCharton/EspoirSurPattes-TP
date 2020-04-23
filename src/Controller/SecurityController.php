<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        if ($this->getUser()) {
            // $url = $request->headers->get('referer');
            // return $this->redirect($url);

            return $this->redirectToRoute('target_path');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        $this->addFlash('success', 'Vous avez bien été deconnecté.');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route ("/create_account", name="create_account")
     */
    public function createUser(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $newUser = new User();

        $formUser = $this->createForm(UserType::class, $newUser);

        $formUser->handleRequest($request);


        if ($formUser->isSubmitted() && $formUser->isValid()) {

            $plainPassword = $formUser->get('plain_password')->getData();

            $encodedPassword = $encoder->encodePassword($newUser, $plainPassword);

            $newUser->setPassword($encodedPassword);

            $newUser->setCreatedAt(new DateTime());

            $newUser->setRoles(['ROLE_USER']);


            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newUser);
            $manager->flush();

            $this->addFlash('success', 'Votre compte à bien été enregistré.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            "/form-user/create_account.html.twig",
            [
                "formUser" => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin()
    {
        return $this->render("/admin/admin.html.twig");
    }
}
