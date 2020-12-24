<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ConferenceController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        $conferences = $conferenceRepository->findAll();
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences
        ]);
    }

    // nueva pagina. 
    // Otra opcion es crear un controlador nuevo para cada pagina
    /**
     * @Route("/conference/{id}", name="conference")
     */
    public function show(Request $request, CommentRepository $commentRepository, Conference $conference)
    {
        // symfony busca la conferencia, y aquise buscan los comentarios

        $offset= max(0, $request->query->getInt('offset', 0));;

        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE)
        ]);

    }

}
