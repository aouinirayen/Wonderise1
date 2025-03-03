<?php

namespace App\Controller;

use App\Entity\Monument;
use App\Form\MonumentType;
use App\Repository\MonumentRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/monument')]
class MonumentController extends AbstractController
{
    private $entityManager;
    private $monumentRepository;
    private $countryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        MonumentRepository $monumentRepository,
        CountryRepository $countryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->monumentRepository = $monumentRepository;
        $this->countryRepository = $countryRepository;
    }

    #[Route('/', name: 'app_monument_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('monument/index.html.twig', [
            'monuments' => $this->monumentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_monument_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $monument = new Monument();
        $form = $this->createForm(MonumentType::class, $monument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($monument);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_monument_index');
        }

        return $this->render('monument/form.html.twig', [
            'monument' => $monument,
            'form' => $form->createView(),
            'title' => 'Create new Monument'
        ]);
    }

    #[Route('/{id}', name: 'app_monument_show', methods: ['GET'])]
    public function show(Monument $monument): Response
    {
        return $this->render('monument/show.html.twig', [
            'monument' => $monument,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_monument_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Monument $monument): Response
    {
        $form = $this->createForm(MonumentType::class, $monument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_monument_index');
        }

        return $this->render('monument/form.html.twig', [
            'monument' => $monument,
            'form' => $form->createView(),
            'title' => 'Edit Monument'
        ]);
    }

    #[Route('/{id}', name: 'app_monument_delete', methods: ['POST'])]
    public function delete(Request $request, Monument $monument): Response
    {
        if ($this->isCsrfTokenValid('delete'.$monument->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($monument);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_monument_index');
    }

    // API endpoints
    #[Route('/api/monuments', name: 'api_monument_index', methods: ['GET'])]
    public function apiIndex(): Response
    {
        $monuments = $this->monumentRepository->findAll();
        return $this->json($monuments, 200, [], ['groups' => 'monument:read']);
    }

    #[Route('/api/monuments/{id}', name: 'api_monument_show', methods: ['GET'])]
    public function apiShow(Monument $monument): Response
    {
        return $this->json($monument, 200, [], ['groups' => 'monument:read']);
    }

    #[Route('/api/monuments', name: 'api_monument_create', methods: ['POST'])]
    public function apiCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $monument = new Monument();
        $monument->setName($data['name'] ?? null);
        $monument->setImg($data['img'] ?? null);
        $monument->setDescription($data['description'] ?? null);

        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $monument->setCountry($country);
            }
        }

        $this->entityManager->persist($monument);
        $this->entityManager->flush();

        return $this->json($monument, 201, [], ['groups' => 'monument:read']);
    }

    #[Route('/api/monuments/{id}', name: 'api_monument_update', methods: ['PUT'])]
    public function apiUpdate(Request $request, Monument $monument): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $monument->setName($data['name']);
        }
        if (isset($data['img'])) {
            $monument->setImg($data['img']);
        }
        if (isset($data['description'])) {
            $monument->setDescription($data['description']);
        }
        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $monument->setCountry($country);
            }
        }

        $this->entityManager->flush();

        return $this->json($monument, 200, [], ['groups' => 'monument:read']);
    }

    #[Route('/api/monuments/{id}', name: 'api_monument_delete', methods: ['DELETE'])]
    public function apiDelete(Monument $monument): Response
    {
        $this->entityManager->remove($monument);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
