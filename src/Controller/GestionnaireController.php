<?php

namespace App\Controller;

use App\Entity\Gestionnaire;
use App\Entity\Utilisateurs;
use App\Form\GestionnaireType;
use App\Repository\GestionnaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/gestionnaire")
 */
class GestionnaireController extends AbstractController
{
    /**
     * @Route("/", name="gestionnaire_index", methods={"GET"})
     */
    public function index(GestionnaireRepository $gestionnaireRepository): Response
    {
        return $this->render('gestionnaire/index.html.twig', [
            'gestionnaires' => $gestionnaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="gestionnaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $gestionnaire = new Gestionnaire();
        $user = new Utilisateurs();

        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($gestionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('gestionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestionnaire/new.html.twig', [
            'gestionnaire' => $gestionnaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gestionnaire_show", methods={"GET"})
     */
    public function show(Gestionnaire $gestionnaire): Response
    {
        return $this->render('gestionnaire/show.html.twig', [
            'gestionnaire' => $gestionnaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="gestionnaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Gestionnaire $gestionnaire): Response
    {
        $form = $this->createForm(GestionnaireType::class, $gestionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('gestionnaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('gestionnaire/edit.html.twig', [
            'gestionnaire' => $gestionnaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="gestionnaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Gestionnaire $gestionnaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gestionnaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($gestionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gestionnaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
