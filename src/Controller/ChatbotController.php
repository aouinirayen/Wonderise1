<?php

namespace App\Controller;

use App\Service\BotManFactoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use BotMan\Drivers\Web\WebDriver;

class ChatbotController extends AbstractController
{
    private BotManFactoryService $botManFactory;

    public function __construct(BotManFactoryService $botManFactory)
    {
        $this->botManFactory = $botManFactory;
    }

    #[Route('/chatbot/api', name: 'app_chatbot', methods: ['POST'])]
    public function chatbotApi(Request $request): JsonResponse
    {
        $botman = $this->botManFactory->createBotMan();

        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['message'])) {
            return new JsonResponse(['message' => 'Erreur : message invalide.'], 400);
        }

        $message = $data['message'];
        $response = '';

        // Écouter les commandes spécifiques du bot
        $botman->hears('bonjour', function ($bot) use (&$response) {
            $response = "Bonjour ! Comment puis-je vous aider ?";
            $bot->reply($response);
        });

        $botman->hears('aide', function ($bot) use (&$response) {
            $response = "Je suis un chatbot ! Posez-moi une question.";
            $bot->reply($response);
        });

       
        $botman->hears('.*', function ($bot, $query) use (&$response) {
            $response = "Vous avez dit : " . $query;
            $bot->reply($response);
        });

        $botman->listen();

        if (empty($response)) {
            $response = "Désolé, je ne comprends pas.";
        }

        return new JsonResponse(['message' => $response]);
    }
}
