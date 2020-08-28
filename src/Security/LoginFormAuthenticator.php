<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * 1. Vérifier qu'il y ait une tentative de connexion
     *          Vérifier si on se trouve sur la page de connexion
     *          et que l'on a envoyé le formuliare
     */
    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }
    /**
     * 2.   Récupérer les informations de connexion
     */
    public function getCredentials(Request $request)
    {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    /**
     * 3.  Récuperer l'utilisateur qui de connecte
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        //dd($credentials);
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        /** @var UserRepository $repository */
        $repository = $this->entityManager->getRepository(User::class);
        $user = $repository->findOneByUsername($credentials['username']);

        /**    Méthode de Aymeric
         * 
         * 

                    $user = $this->entityManager->getRepository(User::class)->findOneBy(['pseudo' => $credentials['username']]);
                          ?? $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['username']]);
                          (si le premier résultat  rendre null (?? sinon) il lance une 2 eme requete 
         */

         

        //dd($user);
        //$this->repos['notif']->findBy(array('status' => array(1, 2, 3)));

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Adresse Email ou Pseudo inValide.');
        }

        return $user;
    }

    /**
     * 4. Vérifiér la validité du mot de passe
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $validPassword =  $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
        
        if($validPassword === false){
            throw new CustomUserMessageAuthenticationException('Mot de passe erroné.');
        }

        /** @var User $user */
        if($user->getIsConfirmed() !== true){
            throw new CustomUserMessageAuthenticationException('Vous deves confirmez votre adresse pour se connecter.');
        }

        return true;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    /**
     * 5.  Action à effectuer aprés avoir été connecté
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
