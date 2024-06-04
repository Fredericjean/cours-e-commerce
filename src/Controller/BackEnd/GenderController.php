<?php

namespace App\Controller\Backend;

use App\Entity\Gender;
use App\Form\GenderType;
use App\Repository\GenderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/genders', name: 'app.admin.genders')]
class GenderController extends AbstractController
{
   

    public function __construct( 
        private EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(GenderRepository $genderRepository): Response
    {
        return $this->render('Backend/Genders/index.html.twig', [
            'genders' => $genderRepository->findAll(),
        ]);
    }

    #[Route('/{id}/update', name: '.update', methods: ['GET', 'POST'])]
    public function update(?Gender $gender, Request $request): Response | RedirectResponse
    {
        if (!$gender) {
            $this->addFlash('error', 'Genre pas trouvé');
           
            return $this->redirectToRoute('app.admin.genders.index');
        }

        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($gender);
            $this->em->flush();
            $this->addFlash('success', 'Genre modifié avec succès');
           
            return $this->redirectToRoute('app.admin.genders.index');
        }

        return $this->render('Backend/Genders/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Gender $gender, Request $request): RedirectResponse
    {
        if (!$gender) {
            $this->addFlash('error', 'Genre pas trouvé');
           
            return $this->redirectToRoute('app.admin.genders.index');
        }

        if ($this->isCsrfTokenValid('delete'.$gender->getId(), $request->request->get('token'))) {
            $this->em->remove($gender);
            $this->em->flush();
            $this->addFlash('success', 'Genre supprimé avec succès');
        } else {
            $this->addFlash('error', 'Token invalide');
        }
        
        return $this->redirectToRoute('app.admin.genders.index');
    }

    #[Route('/create', name:'.create', methods:['POST','GET'])]
    public function create (Request $request): Response
    {
        $gender = new Gender();
        $form = $this->createForm(GenderType::class, $gender);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($gender);
            $this->em->flush();
            $this->addFlash('success', 'Genre créé avec succès');
           
            return $this->redirectToRoute('app.admin.genders.index');
        }
       
        return $this->render('Backend/Genders/create.html.twig', [
            'form'=>$form]);


    }
}
