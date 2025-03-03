<?php

namespace App\Controller;

use App\Entity\Selebrity;
use App\Form\SelebrityType;
use App\Repository\SelebrityRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/celebrity')]
class SelebrityController extends AbstractController
{
    private $entityManager;
    private $celebrityRepository;
    private $countryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        SelebrityRepository $celebrityRepository,
        CountryRepository $countryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->celebrityRepository = $celebrityRepository;
        $this->countryRepository = $countryRepository;
    }

    #[Route('/', name: 'app_celebrity_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('celebrity/index.html.twig', [
            'celebrities' => $this->celebrityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_celebrity_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $celebrity = new Selebrity();
        $form = $this->createForm(SelebrityType::class, $celebrity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($celebrity);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_celebrity_index');
        }

        return $this->render('celebrity/form.html.twig', [
            'celebrity' => $celebrity,
            'form' => $form->createView(),
            'title' => 'Create new Celebrity'
        ]);
    }

    #[Route('/{id}', name: 'app_celebrity_show', methods: ['GET'])]
    public function show(Selebrity $celebrity): Response
    {
        return $this->render('celebrity/show.html.twig', [
            'celebrity' => $celebrity,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_celebrity_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Selebrity $celebrity): Response
    {
        $form = $this->createForm(SelebrityType::class, $celebrity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_celebrity_index');
        }

        return $this->render('celebrity/form.html.twig', [
            'celebrity' => $celebrity,
            'form' => $form->createView(),
            'title' => 'Edit Celebrity'
        ]);
    }

    #[Route('/{id}', name: 'app_celebrity_delete', methods: ['POST'])]
    public function delete(Request $request, Selebrity $celebrity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$celebrity->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($celebrity);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_celebrity_index');
    }

    // API endpoints
    #[Route('/api/celebrities', name: 'api_celebrity_index', methods: ['GET'])]
    public function apiIndex(): Response
    {
        $celebrities = $this->celebrityRepository->findAll();
        return $this->json($celebrities, 200, [], ['groups' => 'celebrity:read']);
    }

    #[Route('/api/celebrities/{id}', name: 'api_celebrity_show', methods: ['GET'])]
    public function apiShow(Selebrity $celebrity): Response
    {
        return $this->json($celebrity, 200, [], ['groups' => 'celebrity:read']);
    }

    #[Route('/api/celebrities', name: 'api_celebrity_create', methods: ['POST'])]
    public function apiCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $celebrity = new Selebrity();
        $celebrity->setName($data['name'] ?? null);
        $celebrity->setImg($data['img'] ?? null);
        $celebrity->setDescription($data['description'] ?? null);
        $celebrity->setWork($data['work'] ?? null);

        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $celebrity->setCountry($country);
            }
        }

        $this->entityManager->persist($celebrity);
        $this->entityManager->flush();

        return $this->json($celebrity, 201, [], ['groups' => 'celebrity:read']);
    }

    #[Route('/api/celebrities/{id}', name: 'api_celebrity_update', methods: ['PUT'])]
    public function apiUpdate(Request $request, Selebrity $celebrity): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $celebrity->setName($data['name']);
        }
        if (isset($data['img'])) {
            $celebrity->setImg($data['img']);
        }
        if (isset($data['description'])) {
            $celebrity->setDescription($data['description']);
        }
        if (isset($data['work'])) {
            $celebrity->setWork($data['work']);
        }
        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $celebrity->setCountry($country);
            }
        }

        $this->entityManager->flush();

        return $this->json($celebrity, 200, [], ['groups' => 'celebrity:read']);
    }

    #[Route('/api/celebrities/{id}', name: 'api_celebrity_delete', methods: ['DELETE'])]
    public function apiDelete(Selebrity $celebrity): Response
    {
        $this->entityManager->remove($celebrity);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}