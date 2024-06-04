<?php

namespace App\Controller\Backend;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/models', name: 'app.admin.models')]
class ModelController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    #[Route('', name: '.index')]
    public function index(ModelRepository $modelRepository): Response
    {
        return $this->render('Backend/Models/index.html.twig', [
            'models' => $modelRepository->findall(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response | RedirectResponse
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();
            $this->addFlash('success', 'Modèle créé avec succès');

            return $this->redirectToRoute('app.admin.models.index');
        }

        return $this->render(
            'Backend/Models/create.html.twig',
            ['form' => $form]
        );
    }

    #[Route('/{id}/update', name: '.update', methods: ['POST', 'GET'])]
    public function update(?Model $model, Request $request): Response | RedirectResponse

    {
        if (!$model) {
            $this->addFlash('error', 'Ce modèle n\'existe pas');

            return $this->redirectToRoute('app.admin.models.index');
        }
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($model);
            $this->em->flush();
            $this->addFlash('success', "Modèle de chaussure créé avec succès");

            return $this->redirectToRoute('app.admin.models.index');
        }

        return $this->render('Backend/Models/update.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: '.delete', methods: ['POST'])]
    public function delete(?Model $model, Request $request): RedirectResponse
    {
        if (!$model) {
            $this->addFlash('error', 'Modèle pas trouvé');

            return $this->redirectToRoute('app.admin.models.index');
        }
        if ($this->isCsrfTokenValid('delete' . $model->getId(), $request->request->get('token'))) {
            $this->em->remove($model);
            $this->em->flush();
            $this->addFlash('success', "Modèle de chaussure supprimé avec succès");
        } else {
            $this->addFlash('error', 'Suppression du modèle de chaussure');
        }

        return $this->redirectToRoute('app.admin.models.index');
    }
}
