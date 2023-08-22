<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route("/forget_password", name:"app_forget_pwd")]
    public function resetPassword(
        Request $request,
        UserRepository $userRepository,
        TokenGeneratorInterface $tokenGeneratorInterface,
        EntityManagerInterface $manager,
        SendMailService $email
    ) {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //* on cherche le user par son email
            $user = $userRepository->findOneByEmail($form->get('email')->getData());
            // $user = $userRepository->findOneByLastname('Courtois');
            // $user = $userRepository->findOneBy<Property name in PASACLCASE ; example: findOneByLastname, findOneByFirstname findOneByAge >('Courtois');
            if ($user) {
                //* on génère un token par tokenGeneratorInterface
                $token = $tokenGeneratorInterface->generateToken();
                $user->setResetToken($token);
                $manager->persist($user);
                $manager->flush();
                // dd($token);
                //* on génère le lien de réinitalisation de mot de passse
                $url = $this->generateUrl("app_forget_pwd_link", ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                // dd($url);

                // * on cre les données de l'email
                $context = compact('user', 'url');

                // * on envoi l'email
                $email->send(
                    'admin@ecommerce-sf6.com',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe',
                    'reset_password',
                    $context
                );
                $this->addFlash('success', 'email envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }
            // $user est null
            $this->addFlash('danger', 'un problème est survenu');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('security/reset_password_request.html.twig', [
            'ResetPasswordForm' => $form->createView()
        ]);
    }

    #[Route("/forget_password/{token}", name:"app_forget_pwd_link")]
    public function resetPasswordView(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $passwordHasher
    ) {
        // * on verfie le user à traves resteToken
        $user = $userRepository->findOneByResetToken($token) ;
        if ($user) {
            // * on verifie si le token est valide
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // on efface le resetToken
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'mot de passe réinitialiser avec succès');
                $this->redirectToRoute('app_login');
            }


            return $this->render('security/reset_password.html.twig', [
                     'ResetPasswordForm' => $form->createView()
                 ]);

        }
        $this->addFlash('danger', "invalid token");
        return $this->redirectToRoute('app_login');

    }

}
