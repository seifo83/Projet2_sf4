<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager ): Response
    {
        /**
         * $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request); 
            on a supprimé  $user = new User(); et dans createForm(RegistrationFormType::class, $user); on supprime $user
            on a fait appel de à l'entity User avec l'objet $user dans le test if directement  $user = $form->getData(); 
            car dans le formuliare le fichier RegistrationFromType.php on a déclaprer par défaut que l'entité appeler ser User
            dans la méthode configureOptions()
            $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

        **/


        $form = $this->createForm(RegistrationFormType::class);   // on  enlevé $user et on l'appel dans le test if avec $user = $form->getData(); 
         $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            ); 

            //$entityManager = $this->getDoctrine()->getManager(); => cette ligne est remplacer par faire appel a EntityManagerInterface dans la methode
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email


            $this->addFlash('success', 'Vous avez bien été inscrit !');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
