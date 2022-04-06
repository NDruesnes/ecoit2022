<?php

namespace App\Controller;

use App\Entity\Instructor;
use App\Entity\InstructorStatut;
use App\Entity\ProfilImg;
use App\Form\RegistrationInstructorFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/registerinstructor", name="app_registerinstructor")
     */
    public function registerInstructor(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Instructor();
        $form = $this->createForm(RegistrationInstructorFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            //set statut "en attente"
            $tmp = $this->getDoctrine()->getRepository(InstructorStatut::class)->find(1);
            $user->setInstructorStatut($tmp);

            //setRole
            $user->setRoles(['instructor']);

            $entityManager->persist($user);
            $entityManager->flush();

            //on récuperes l'image de profil
            $profilimg = $form->get('profilimg')->getData();
            //donne un nom unique
            $fichier = md5(uniqid()) . '.' .$profilimg->guessExtension();
            //copie le fichier dans le dossiers uploads
            $profilimg->move(
                $this->getParameter('images_directory'),
                $fichier
            );

            //on stocke profilimg en bdd
            $img = new ProfilImg();
            $img->setName($fichier);
            $img->setFileUrl($this->getParameter('images_directory').$fichier);
            $img->setInstructor($user);
            $entityManager->persist($img);
            $entityManager->flush();
            
            // $img->setInstructor();
            

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('ecoit.contact@gmail.com', 'Ecoit'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );
            // do anything else you need here, like send an email
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/registerInstructor.html.twig', [
            'registrationInstructorForm' => $form->createView(),
        ]);
    }

    //     /**
    //  * @Route("/registerStudent", name="app_registerStudent")
    //  */
    // public function registerStudent(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new Instructor();
    //     $form = $this->createForm(RegistrationFormType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // encode the plain password
    //         $user->setPassword(
    //         $userPasswordHasher->hashPassword(
    //                 $user,
    //                 $form->get('plainPassword')->getData()
    //             )
    //         );

    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         // generate a signed url and email it to the user
    //         $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
    //             (new TemplatedEmail())
    //                 ->from(new Address('ecoit.contact@gmail.com', 'Ecoit'))
    //                 ->to($user->getEmail())
    //                 ->subject('Please Confirm your Email')
    //                 ->htmlTemplate('registration/confirmation_email.html.twig')
    //         );
    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('app_home');
    //     }

    //     return $this->render('registration/register.html.twig', [
    //         'registrationForm' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}
