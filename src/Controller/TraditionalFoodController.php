<?php

namespace App\Controller;

use App\Entity\TraditionalFood;
use App\Form\TraditionalFoodType;
use App\Repository\TraditionalFoodRepository;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/traditional-food')]
class TraditionalFoodController extends AbstractController
{
    private $entityManager;
    private $traditionalFoodRepository;
    private $countryRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TraditionalFoodRepository $traditionalFoodRepository,
        CountryRepository $countryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->traditionalFoodRepository = $traditionalFoodRepository;
        $this->countryRepository = $countryRepository;
    }

    #[Route('/', name: 'app_traditional_food_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('traditional_food/index.html.twig', [
            'traditional_foods' => $this->traditionalFoodRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_traditional_food_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $traditionalFood = new TraditionalFood();
        $form = $this->createForm(TraditionalFoodType::class, $traditionalFood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($traditionalFood);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_traditional_food_index');
        }

        return $this->render('traditional_food/form.html.twig', [
            'traditional_food' => $traditionalFood,
            'form' => $form->createView(),
            'title' => 'Create new Traditional Food'
        ]);
    }

    #[Route('/{id}', name: 'app_traditional_food_show', methods: ['GET'])]
    public function show(TraditionalFood $traditionalFood): Response
    {
        return $this->render('traditional_food/show.html.twig', [
            'traditional_food' => $traditionalFood,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_traditional_food_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TraditionalFood $traditionalFood): Response
    {
        $form = $this->createForm(TraditionalFoodType::class, $traditionalFood);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_traditional_food_index');
        }

        return $this->render('traditional_food/form.html.twig', [
            'traditional_food' => $traditionalFood,
            'form' => $form->createView(),
            'title' => 'Edit Traditional Food'
        ]);
    }

    #[Route('/{id}', name: 'app_traditional_food_delete', methods: ['POST'])]
    public function delete(Request $request, TraditionalFood $traditionalFood): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traditionalFood->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($traditionalFood);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_traditional_food_index');
    }

    // API endpoints
    #[Route('/api/traditional-foods', name: 'api_traditional_food_index', methods: ['GET'])]
    public function apiIndex(): Response
    {
        $foods = $this->traditionalFoodRepository->findAll();
        return $this->json($foods, 200, [], ['groups' => 'food:read']);
    }

    #[Route('/api/traditional-foods/{id}', name: 'api_traditional_food_show', methods: ['GET'])]
    public function apiShow(TraditionalFood $traditionalFood): Response
    {
        return $this->json($traditionalFood, 200, [], ['groups' => 'food:read']);
    }

    #[Route('/api/traditional-foods', name: 'api_traditional_food_create', methods: ['POST'])]
    public function apiCreate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $traditionalFood = new TraditionalFood();
        $traditionalFood->setName($data['name'] ?? null);
        $traditionalFood->setImg($data['img'] ?? null);
        $traditionalFood->setDescription($data['description'] ?? null);
        $traditionalFood->setRecipe($data['recipe'] ?? null);

        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $traditionalFood->setCountry($country);
            }
        }

        $this->entityManager->persist($traditionalFood);
        $this->entityManager->flush();

        return $this->json($traditionalFood, 201, [], ['groups' => 'food:read']);
    }

    #[Route('/api/traditional-foods/{id}', name: 'api_traditional_food_update', methods: ['PUT'])]
    public function apiUpdate(Request $request, TraditionalFood $traditionalFood): Response
    {
        $data = json_decode($request->getContent(), true);
        
        if (isset($data['name'])) {
            $traditionalFood->setName($data['name']);
        }
        if (isset($data['img'])) {
            $traditionalFood->setImg($data['img']);
        }
        if (isset($data['description'])) {
            $traditionalFood->setDescription($data['description']);
        }
        if (isset($data['recipe'])) {
            $traditionalFood->setRecipe($data['recipe']);
        }
        if (isset($data['country_id'])) {
            $country = $this->countryRepository->find($data['country_id']);
            if ($country) {
                $traditionalFood->setCountry($country);
            }
        }

        $this->entityManager->flush();

        return $this->json($traditionalFood, 200, [], ['groups' => 'food:read']);
    }

    #[Route('/api/traditional-foods/{id}', name: 'api_traditional_food_delete', methods: ['DELETE'])]
    public function apiDelete(TraditionalFood $traditionalFood): Response
    {
        $this->entityManager->remove($traditionalFood);
        $this->entityManager->flush();

        return $this->json(null, 204);
    }
}
