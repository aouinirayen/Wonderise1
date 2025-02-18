<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ExperienceRepository $experienceRepository, CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }
} 