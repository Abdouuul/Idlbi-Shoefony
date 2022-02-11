<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Mailer\ContactMailer;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class MainController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContactMailer $contactmailer, 
        private ProductRepository $productsrepository
        )
    {
        $this->em = $em;
        $this->contactmailer = $contactmailer;
        $this->productsrepository = $productsrepository; 
    }

    #[Route('/', name: 'main_homepage')]
    public function index(): Response
    {
        $latestProducts = $this->productsrepository->findLatestProducts();
        $mostCommentedProducts = $this->productsrepository->findMostPopularProducts();

        for($i = 0; $i < 4; $i++)
        {
            $latestProducts[$i]->getImage();
            $mostCommentedProducts[$i]->getImage();
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
            'latestProducts' => $latestProducts,
            'mostCommentedProducts' => $mostCommentedProducts,
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
