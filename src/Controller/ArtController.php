<?php

namespace App\Controller;

use App\Entity\Art;
use App\Form\ArtType;
use App\Repository\ArtRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/art')]
class ArtController extends AbstractController
{
    private $entityManager;
    private $artRepository;
    private $countryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ArtRepository $artRepository,
        CountryRepository $countryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->artRepository = $artRepository;
        $this->countryRepository = $countryRepository;
    }

    #[Route('/', name: 'app_art_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('art/index.html.twig', [
            'arts' => $this->artRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_art_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $art = new Art();
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($art);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_art_index');
        }

        return $this->render('art/form.html.twig', [
            'art' => $art,
            'form' => $form->createView(),
            'title' => 'Create new Art'
        ]);
    }

    #[Route('/{id}', name: 'app_art_show', methods: ['GET'])]
    public function show(Art $art): Response
    {
        return $this->render('art/show.html.twig', [
            'art' => $art,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_art_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Art $art): Response
    {
        $form = $this->createForm(ArtType::class, $art);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_art_index');
        }

        return $this->render('art/form.html.twig', [
            'art' => $art,
            'form' => $form->createView(),
            'title' => 'Edit Art'
        ]);
    }

    #[Route('/{id}', name: 'app_art_delete', methods: ['POST'])]
    public function delete(Request $request, Art $art): Response
    {
        if ($this->isCsrfTokenValid('delete'.$art->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($art);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_art_index');
    }

    // API endpoints
    #[Route('/api/arts', name: 'api_art_index', methods: ['GET'])]
    public function apiIndex(): Response
    {
        $arts = $this->artRepository->findAll();
        return $this->json($arts, 200, [], ['groups' => 'art:read']);
    }

    #[Route('/api/arts/{id}', name: 'api_art_show', methods: ['GET'])]
    public function apiShow(Art $art): Response
    {
        return $this->json($art, 200, [], ['groups' => 'art:read']);
    }

    #[Route('/api/arts', name: 'api_art_create', methods: ['POST'])]
    public function apiCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $art = new Art();
        $art->setName($data['name'] ?? null);
        $art->setImg($data['img'] ?? null);
        $art->setDescription($data['description'] ?? null);

        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $art->setCountry($country);
            }
        }

        $this->entityManager->persist($art);
        $this->entityManager->flush();

        return $this->json($art, 201, [], ['groups' => 'art:read']);
    }

    #[Route('/api/arts/{id}', name: 'api_art_update', methods: ['PUT'])]
    public function apiUpdate(Request $request, Art $art): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $art->setName($data['name']);
        }
        if (isset($data['img'])) {
            $art->setImg($data['img']);
        }
        if (isset($data['description'])) {
            $art->setDescription($data['description']);
        }
        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $art->setCountry($country);
            }
        }

        $this->entityManager->flush();

        return $this->json($art, 200, [], ['groups' => 'art:read']);
    }

    #[Route('/api/arts/{id}', name: 'api_art_delete', methods: ['DELETE'])]
    public function apiDelete(Art $art): Response
    {
        $this->entityManager->remove($art);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}