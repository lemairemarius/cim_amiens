<?php

namespace App\Controller;


use App\Entity\Carte;
use App\Entity\Cimetiere;
use App\Entity\Gestionnaire;
use App\Entity\Utilisateurs;
use App\Form\UtilisateursType;
use App\Repository\CarteRepository;
use App\Repository\CimetiereRepository;
use App\Repository\UtilisateursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @Route("/utilisateurs")
 */
class UtilisateursController extends AbstractController
{
    /**
     * @Route("/", name="utilisateurs_index", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(UtilisateursRepository $utilisateursRepository, CarteRepository $carteRepository, CimetiereRepository $cimetiereRepository): Response
    {
        return $this->render('utilisateurs/index.html.twig', [
            'utilisateurs' => $utilisateursRepository->findAll(),
            'carte'=> $carteRepository->findAll(),
            'cim' => $cimetiereRepository->findAll(),
        ]);

    }

    /**
     * @Route("/new", name="utilisateurs_new", methods={"GET","POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CimetiereRepository $cimetiereRepository, CarteRepository $carteRepository): Response
    {

        $utilisateur = new Utilisateurs();
        $card = new Carte();
        $gest = new Gestionnaire();


        $form = $this->createForm(UtilisateursType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $utilisateur->setCreatedAt(new  \DateTime());
            $utilisateur->setUpdatedAt(new \DateTime());
            $utilisateur->setUpdatedBy($gest->getIdenGes());
            $utilisateur->setCreatedBy($gest->getIdenGes());
            $utilisateur->setPossede($card);


            $card->setCardVal($form->get("cardVal")->getData());
            $card->setDCardEndVal(new \DateTime());
            $card->setNumCard($form->get("id_card")->getData());


            /**insertion de l'id de la carte dans les cimetières*/

            $selectedCimetiere = $form->get("cimetieres")->getData();
            foreach ($selectedCimetiere as $cim){
                $cimetiereEntity = $cimetiereRepository->find($cim);
                $card->addCimetiere($cimetiereEntity);
            }

            /** persistance des données et push dans la base données */
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($utilisateur);
            $entityManager->persist($card);
            $entityManager->flush();


            $this->addFlash('sucess',"Le nouvelle utilisateur a bien été enregistré !");

            return $this->redirectToRoute('utilisateurs_new', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('utilisateurs/new.html.twig', [
            'registrationForm'=> $form->createView()
        ]);

        }


    /**
     * @Route("/{id}", name="utilisateurs_show", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Utilisateurs $utilisateur): Response
    {
        return $this->render('utilisateurs/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /*
     * TODO : Je suis à ce point !!! Flashmessage pour generer les success aprés update
     */

    /**
     * @Route("/{id}/edit", name="utilisateurs_edit", methods={"GET","POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function edit(Request $request, Utilisateurs $utilisateur): Response
    {
        $form = $this->createForm(UtilisateursType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('utilisateurs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateurs/edit.html.twig', [
            'utilisateur'=> $utilisateur,
            'registrationForm'=> $form->createView()
        ]);

    }

    /**
     * @Route("/{id}", name="utilisateurs_delete", methods={"POST"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Utilisateurs $utilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('utilisateurs_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/export/{id}", name="export_data", methods={"GET"})
     */
    public function export(Request $request,Utilisateurs $utilisateurs, Carte $carte, Cimetiere $cimetiere): Response
    {

        $vairableCSV = "Nom; Prénoms; Nom d\'usage; Date de naissance; Adresse principal; Complément d\'adresse; Ville; Code postal; Crée le; Dernière modification;
        Crée par; Modifier par; Date de fin de validité;\n";

        $vairableCSV .= "$utilisateurs->getNomFamUt();$utilisateurs->getPre_ut();\n";

        $vairableCSV .= " ; ; ;\n";


        return new Response(
            $vairableCSV,
            200,
            [
                'content-Type' => 'application/vnd.ms-excel',
                "Content-disposition" => "attachement; filename= utilisateur{{id}}.csv"
            ]
        );
    }

}
