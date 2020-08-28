<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\RegistrationFormType;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private $manger;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->manager = $entityManager;
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EmailSender $emailSender ): Response
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

            /* cette méthode était au début pour cerrer le formulaire d'inscription 
                Remarque on a modifier la méthode par la mméthode 2 puisque on abesoin d'envoyer un mail de confirmation
                regade la methode 2

            $user = $form->getData();

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );  
            */

            //méthode 2 pour un formulaire avec confirmation de mail
            //récupération des données de formuliare(entité user + mot de passe)
            $user = $form->getData();
            $password = $form->get('plainPassword')->getData();

            //hash du mot de passe et création du jeton 
            $user->setPassword($passwordEncoder->encodePassword($user, $password))
                ->renewToken()
            
            ;

            //$entityManager = $this->getDoctrine()->getManager(); => cette ligne est remplacer par faire appel a EntityManagerInterface dans la methode
            $this->manager->persist($user);
            $this->manager->flush();
            // do anything else you need here, like send an email


            //Envoi de l'email de confirmation
            $emailSender->sendAccountConfirmationEmail($user);


            $this->addFlash('success', 'Vous avez bien été inscrit ! Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Confirmation du compte (via un lien envoyé par email)
     * @Route("/confirm-account/{id<\d+>}/{token}", name="account_confirmation")
     */
    public function confirmAccount($id, $token, UserRepository $repository)
    {   
        //Rechercher de l'utilisateur
        $user = $repository->findOneBY([
            'id' => $id,
            'token' => $token,
        ]);

        if($user === null){
            $this ->addFlash('danger', 'Utilisateur ou jeton invalide. ');
            return $this->redirectToRoute('app_login');
        }

        //Utilisateur trouvé: confirmation du compte
        $user
            ->confirmAccount()
            ->renewToken()
        ;

        $this->manager->flush();

        $this->addFlash('success', 'Votre compte est confirmé, vous pouvez vous connecter !');
        return $this->redirectToRoute('app_login');

    }








}
