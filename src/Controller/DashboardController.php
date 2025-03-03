<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(
        Request $request,
        ExperienceRepository $experienceRepository,
        CommentaireRepository $commentaireRepository,
        PaginatorInterface $paginator
    ): Response {
        // Get all experiences and paginate them
        $experiencesQuery = $experienceRepository->createQueryBuilder('e')
            ->orderBy('e.dateCreation', 'DESC')
            ->getQuery();

        $experiences = $paginator->paginate(
            $experiencesQuery,
            $request->query->getInt('page', 1),
            10 // Number of items per page
        );

        // Get all comments and paginate them
        $commentsQuery = $commentaireRepository->createQueryBuilder('c')
            ->orderBy('c.dateCreation', 'DESC')
            ->getQuery();

        $commentaires = $paginator->paginate(
            $commentsQuery,
            $request->query->getInt('comments_page', 1),
            5 // Number of comments per page
        );

        // Get statistics
        $totalExperiences = $experienceRepository->count([]);
        $totalComments = $commentaireRepository->count([]);
        $avgCommentsPerExperience = $totalExperiences > 0 ? round($totalComments / $totalExperiences, 1) : 0;
        $newExperiencesToday = $experienceRepository->count([
            'dateCreation' => new \DateTime('today')
        ]);

        return $this->render('dashboard/index.html.twig', [
            'experiences' => $experiences,
            'commentaires' => $commentaires,
            'totalExperiences' => $totalExperiences,
            'totalComments' => $totalComments,
            'avgCommentsPerExperience' => $avgCommentsPerExperience,
            'newExperiencesToday' => $newExperiencesToday,
        ]);
    }
}