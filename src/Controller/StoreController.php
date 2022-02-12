<?php

namespace App\Controller;

use App\Entity\Store\Brand;
use App\Entity\Store\Comment;
use App\Entity\Store\Product;
use App\Form\CommentType;
use App\Repository\Store\BrandRepository;
use App\Repository\Store\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\Store\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StoreController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CommentRepository $commentRepository,
        private BrandRepository $brandRepository,
        private EntityManagerInterface $em
        )
    {}

    #[Route('/store', name: 'store')]
    public function productShowList(): Response
    {
        $products = $this->productRepository->findAllWithDetails();

        return $this->render('store/index.html.twig', [
            'controller_name' => 'StoreController',
            'products' => $products,
            'brandId' => null,
        ]);
    }



    #[Route('/store/product/{id}/{slug}', 
    name: 'store_show_product', 
    methods:['GET', 'POST'] )]
    public function productDetail(int $id, string $slug, Request $request): Response
    {
        $product = $this->productRepository->findeOneWithDetails($id);

        $comment = new Comment();
        $comment->setProduct($product);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {   
            $this->em->persist($comment);
            $this->em->flush();

            $this->addFlash('success', 'Merci, votre commentaire a bien été envoyé.');
        }

        return $this->render('store/show.html.twig', [
            'slug' => $slug,
            'comments' => $product->getComments(),
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/store/products/brand/{brandId}', 
    name: 'store_list_products_by_brand',
    requirements: ['brandId' =>'\d+']
    )]
    public function listProductsByBrand(int $brandId): Response
    {
        $brand = $this->brandRepository->find($brandId);
        if($brand === null) {
            throw new NotFoundHttpException();
        }

        $products = $this->productRepository->findBy(['brand' => $brand]);

        return $this->render('store/index.html.twig', [
            'brand' => $brand,
            'brandId' => $brandId,
            'products' => $products,
        ]);

    }
    
    public function listAllBrands($brandId): Response
    {
        $brands = $this->brandRepository->findAll();

        return $this->render('store/_list_brands.html.twig', [
            'brands' => $brands,
            'brandId' => $brandId,
        ]);
    }
}
