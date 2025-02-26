<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/country')]
class CountryController extends AbstractController
{
    private $entityManager;
    private $countryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        CountryRepository $countryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
    }

    #[Route('/', name: 'app_country_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('country/index.html.twig', [
            'countries' => $this->countryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_country_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($country);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_country_index');
        }

        return $this->render('country/form.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
            'title' => 'Create new Country'
        ]);
    }

    #[Route('/{id}', name: 'app_country_show', methods: ['GET'])]
    public function show(Country $country): Response
    {
        return $this->render('country/show.html.twig', [
            'country' => $country,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_country_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Country $country): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_country_index');
        }

        return $this->render('country/form.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
            'title' => 'Edit Country'
        ]);
    }

    #[Route('/{id}', name: 'app_country_delete', methods: ['POST'])]
    public function delete(Request $request, Country $country): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($country);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_country_index');
    }

    // API endpoints
    #[Route('/api/countries', name: 'api_country_index', methods: ['GET'])]
    public function apiIndex(): Response
    {
        $countries = $this->countryRepository->findAll();
        return $this->json($countries, 200, [], ['groups' => 'country:read']);
    }

    #[Route('/api/countries/{id}', name: 'api_country_show', methods: ['GET'])]
    public function apiShow(Country $country): Response
    {
        return $this->json($country, 200, [], ['groups' => 'country:read']);
    }

    #[Route('/api/countries', name: 'api_country_create', methods: ['POST'])]
    public function apiCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $country = new Country();
        $country->setName($data['name'] ?? null);
        $country->setImg($data['img'] ?? null);
        $country->setDescription($data['description'] ?? null);

        $this->entityManager->persist($country);
        $this->entityManager->flush();

        return $this->json($country, 201, [], ['groups' => 'country:read']);
    }

    #[Route('/api/countries/{id}', name: 'api_country_update', methods: ['PUT'])]
    public function apiUpdate(Request $request, Country $country): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $country->setName($data['name']);
        }
        if (isset($data['img'])) {
            $country->setImg($data['img']);
        }
        if (isset($data['description'])) {
            $country->setDescription($data['description']);
        }

        $this->entityManager->flush();

        return $this->json($country, 200, [], ['groups' => 'country:read']);
    }

    #[Route('/api/countries/{id}', name: 'api_country_delete', methods: ['DELETE'])]
    public function apiDelete(Country $country): Response
    {
        $this->entityManager->remove($country);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
