<?php
namespace App\Security;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface; // Utiliser UserPasswordEncoderInterface pour encoder les mots de passe
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Core\Password\UserPasswordHasherInterface; // Nouvelle interface pour le hachage

class UsersAuthenticator extends AbstractAuthenticator
{
    private UsersRepository $usersRepository;
    private UserPasswordHasherInterface $passwordHasher; // Changer de PasswordHasherInterface à UserPasswordHasherInterface

    public function __construct(UsersRepository $usersRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->usersRepository = $usersRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'app_login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');

        // Trouver l'utilisateur par son email
        $user = $this->usersRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new AuthenticationException('Aucun utilisateur trouvé pour cet email.');
        }

        // Vérifier que le mot de passe fourni correspond à celui stocké dans la base de données
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new AuthenticationException('Échec de l\'authentification : Bad credentials.');
        }

        return new Passport(
            new UserBadge($user->getEmail()), // Charger l'utilisateur
            new PasswordCredentials($password) // Vérifier le mot de passe
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Rediriger l'utilisateur ou envoyer une réponse en cas de succès
        return new Response('Connexion réussie !');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Message d'erreur en cas d'échec d'authentification
        return new Response('Échec de l\'authentification : ' . $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }
}
