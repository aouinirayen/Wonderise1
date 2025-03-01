<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Experience;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class FrontOfficeController extends AbstractController
{
    #[Route('/front_office', name: 'app_front_office')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        ExperienceRepository $experienceRepository
    ): Response {
        $experience = new Experience();
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $experience->setIdClient('user'); // À remplacer par l'ID de l'utilisateur connecté
            $entityManager->persist($experience);
            $entityManager->flush();

            $this->addFlash('success', 'Your experience has been shared successfully!');
            return $this->redirectToRoute('app_front_office');
        }

        return $this->render('front_office/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
            'experienceForm' => $form->createView(),
        ]);
    }

    #[Route('/about', name: 'app_front_office_about')]
    public function about(): Response
    {
        return $this->render('front_office/about.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    #[Route('/contact', name: 'app_front_office_contact')]
    public function contact(): Response
    {
        return $this->render('front_office/contact.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    #[Route('/services', name: 'app_front_office_services')]
    public function services(): Response
    {
        return $this->render('front_office/services.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    #[Route('/packages', name: 'app_front_office_packages')]
    public function packages(): Response
    {
        return $this->render('front_office/packages.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }

    #[Route('/blog', name: 'app_front_office_blog')]
    public function blog(): Response
    {
        return $this->render('front_office/blog.html.twig', [
            'controller_name' => 'FrontOfficeController',
        ]);
    }
    
    
}
