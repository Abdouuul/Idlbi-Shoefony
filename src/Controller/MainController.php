<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    private ContactMailer $contactmailer;
    private $em;
    public function __construct(EntityManagerInterface $em,ContactMailer $contactmailer)
    {
        $this->em = $em;
        $this->contactmailer = $contactmailer;
    }

    #[Route('/', name: 'main_homepage')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/presentation', name: 'presentation_page')]
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/contact', name: 'contact_page', methods:['GET', 'POST'])]
    public function contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);


        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $this->em->persist($contact);
            $this->em->flush();

            $this->addFlash('success', 'Merci, votre message a bien été envoyé.');
            
            $this->contactmailer->send($contact);
            return $this->redirectToRoute('contact_page');
        }
        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
