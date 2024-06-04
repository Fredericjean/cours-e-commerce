<?php

namespace App\Controller\Backend;

use App\Entity\Taxe;
use App\Form\TaxeType;
use App\Repository\TaxeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/taxes', name:'app.admin.taxes')]
class TaxeController extends AbstractController
{
    public function __construct (private EntityManagerInterface $em )
    {}

    #[Route('', name: '.index')]
    public function index(TaxeRepository $taxeRepository): Response
    {

        return $this->render('Backend/Taxes/index.html.twig', [
            'taxes' => $taxeRepository->findAll(),
        ]);
    }

    #[Route('/create', name:".create", methods:['POST', 'GET'])]
    public function create (Request $request): Response
    {
        $taxe = new Taxe();
        $form =$this->createForm(TaxeType::class, $taxe);
        $form-> handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($taxe);
            $this->em->flush();
            $this->addFlash('success', 'Taxe créée avec succès');
            return $this->redirectToRoute('app.admin.taxes.index');
        }
        return $this->render(
            'Backend/Taxes/create.html.twig',
           ['form'=> $form]);
    }

    #[Route('/{id}/update', name:'.update', methods :['GET','POST'])]
    public function update(Request $request, ?Taxe $taxe): Response | RedirectResponse 
    {
        if(!$taxe)
        {
            $this->addFlash('error','Problème dans la recherche de taxes');
            return $this->redirectToRoute('app.admin.taxes.index');
        }

        $form = $this->createForm(TaxeType::class, $taxe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($taxe);
            $this->em->flush();
            $this->addFlash('success','La taxe a été modifiée avec succès');

            return $this->redirectToRoute("app.admin.taxes.index");
        }

        return $this->render ("Backend/Taxes/index.html.twig");
    
    }

    #[Route('/{id}/delete', name:'.delete', methods:['POST'])]
    public function delete (Taxe $taxe, Request $request)
    {
        if(!$taxe){
            return $this->redirectToRoute('app.admin.taxes.index');
            $this->addFlash('error','Problème dans la recherche de taxes');
        }
        if($this->isCsrfTokenValid('delete'.$taxe->getId(),$request->request->get('token')))
        {
            $this->em->remove($taxe);
            $this->em->flush();
            $this->addFlash('success','Taxe supprimée avec succès');
        }
        return $this->redirectToRoute('app.admin.taxes.index');
        }
}
