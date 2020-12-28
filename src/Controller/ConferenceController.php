<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ConferenceController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(ConferenceRepository $conferenceRepository, SessionInterface $session): Response
    {
        $session->set('prueba2', 'hola mundo');
        dump($session->get('prueba2'));
        $conferences = $conferenceRepository->findAll();
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences
        ]);
    }

    // nueva pagina. 
    // Otra opcion es crear un controlador nuevo para cada pagina
    /**
     * @Route("/conference/{slug}", name="conference")
     */
    public function show(Request $request, CommentRepository $commentRepository, Conference $conference)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setConference($conference);
            $this->entityManager->persist($comment);
            $this->entityManager->flush();
            return $this->redirectToRoute( 'conference', ['slug' => $conference->getSlug()] );
        }
        // symfony busca la conferencia, y aqui se buscan los comentarios

        $offset= max(0, $request->query->getInt('offset', 0));;

        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::PAGINATOR_PER_PAGE,
            'next' => min(count($paginator), $offset + CommentRepository::PAGINATOR_PER_PAGE),
            'comment_form' => $form->createView()
        ]);

    }

}
