<?php
// src/Service/BotManFactoryService.php
namespace App\Service;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Cache\SymfonyCache;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\BotManFactory;
use Symfony\Contracts\Cache\CacheInterface;
use Psr\Log\LoggerInterface;

class BotManFactoryService
{
    private CacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(CacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    public function createBotMan(): BotMan
    {
        // Charger le driver Web
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        $config = [
            'botman' => [
                'conversation_cache_time' => 30, // Cache de conversation
            ],
        ];

        $botman = BotManFactory::create($config, new SymfonyCache($this->cache));

        // Logger pour voir les messages reçus
        $botman->hears('.*', function ($bot, $query) {
            $bot->reply("Vous avez dit : " . $query);
        });

        return $botman;
    }
}
