<?php

    namespace App\Security;

    use App\Entity\Admin;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
    use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
    use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
    use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
    use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
    use Symfony\Component\Security\Http\Util\TargetPathTrait;
    use Symfony\Component\Routing\RouterInterface;
    use Symfony\Component\Routing\Exception\RouteNotFoundException;
    use Symfony\Component\Security\Core\Security; // Agregada la importación


    class TrackAuthenticator extends AbstractLoginFormAuthenticator
    {
        use TargetPathTrait;

        public const LOGIN_ROUTE = 'app_login';

        private EntityManagerInterface $entityManager;
        private UrlGeneratorInterface $urlGenerator;

        public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator)
        {
            $this->entityManager = $entityManager;
            $this->urlGenerator = $urlGenerator;
        }

        public function authenticate(Request $request): Passport
        {
            $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        $adminRepository = $this->entityManager->getRepository(Admin::class);
        $admin = $adminRepository->findOneBy(['email' => $email]);

        if (!$admin) {
            // El administrador no existe, define un mensaje de error
            $errorMessage = 'El administrador no existe.';
            return new Passport(new UserBadge(''), new PasswordCredentials(''), [], null, ['error' => $errorMessage]);
        }
    

        $nombreUsuario = $admin->getName();

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }
        $successMessage = 'Session started successfully.'; // Mensaje de éxito
        $request->getSession()->getFlashBag()->add('success', $successMessage); //
        // Redirige al usuario a una ruta específica después de iniciar sesión correctamente
        return new RedirectResponse($this->urlGenerator->generate('app_map'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
