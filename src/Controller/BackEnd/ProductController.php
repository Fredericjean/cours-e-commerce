<?php

namespace App\Controller\Backend;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/products', name:'app.admin.products')]
class ProductController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('', name: ".index")]
    public function index(ProductRepository $productRepository): Response
    {

        return $this->render('Backend/Products/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/create', name: ".create")]
    public function create(Request $request): Response | RedirectResponse
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($product);
            $this->em->flush();
            $this->addFlash('success', 'Produit créé avec succès');

            return $this->redirectToRoute('app.admin.products.index');
        }

        return $this->render('Backend/Products/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/update', name: ".update", methods: ['GET', 'POST'])]
    public function update(Request $request, ?Product $product): Response|RedirectResponse
    {
        if (!$product) {
            $this->addFlash('error','Produit introuvable');
            return $this->redirectToRoute(('add.admin.products.index'));
        }
        
        $form=$this->createForm(ProductType::class,$product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 
            $this->em->persist($product);
            $this->em->flush();

            return $this->redirectToRoute('app.admin.products.index');
        }

        return $this->render('Backend/Products/update.html.twig',
    ['form'=>$form]);
    }

    #[Route('/{id}/delete', name:'.delete', methods:['POST'])]
    public function delete (Request $request, ?Product $product): RedirectResponse 
    {
        if(!$product)
        {
            $this->addFlash('error','Produit non trouvé');

            return $this->redirectToRoute('app.admin.products.index');
        }

        if($this->isCsrfTokenValid('delete' . $product->getId(),$request->request->get('token')))
        {
            $this->em->remove($product);
            $this->em->flush();
            $this->addFlash('success','Produit supprimé avec succès');

            return $this->redirectToRoute('app.admin.products.index');
        }else {
            $this->addFlash('error','Erreur lors de la suppression du produit token CSRF invalide');
        }

        return $this->render ('Backend/Products/index');
    }



}


