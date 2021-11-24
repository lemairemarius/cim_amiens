<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchForm;
use App\Repository\UtilisateursRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(UtilisateursRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {



        $data = new SearchData();
        $form = $this->createForm(SearchForm::class, $data);
        $form->handleRequest($request);
        $user = $repository->findSearch($data);

        $page = $paginator ->paginate(
            $user,
            $request->query->getInt('page',1),
            8
        );


        return $this->render('search/search.html.twig', [
            'users' => $page,
            'form' => $form->createView()
        ]);
    }
}
