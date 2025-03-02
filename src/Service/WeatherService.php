<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private $httpClient;
    private $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    private function translateWeatherDescription(string $description): string
    {
        $translations = [
            'clear sky' => 'Ciel dégagé',
            'few clouds' => 'Quelques nuages',
            'scattered clouds' => 'Nuages épars',
            'broken clouds' => 'Nuageux',
            'shower rain' => 'Averses',
            'rain' => 'Pluie',
            'thunderstorm' => 'Orage',
            'snow' => 'Neige',
            'mist' => 'Brume',
            'overcast clouds' => 'Couvert',
            'light rain' => 'Pluie légère',
            'moderate rain' => 'Pluie modérée',
            'heavy rain' => 'Forte pluie',
            'light snow' => 'Neige légère',
            'heavy snow' => 'Forte neige',
            'drizzle' => 'Bruine',
            'fog' => 'Brouillard',
            'haze' => 'Brume sèche',
            'dust' => 'Poussière',
            'sand' => 'Sable',
            'smoke' => 'Fumée'
        ];

        return $translations[strtolower($description)] ?? $description;
    }

    private function getWeatherIcon(string $iconCode): string
    {
        $iconMapping = [
            '01d' => 'sun', // clear sky day
            '01n' => 'moon', // clear sky night
            '02d' => 'cloud-sun', // few clouds day
            '02n' => 'cloud-moon', // few clouds night
            '03d' => 'cloud', // scattered clouds
            '03n' => 'cloud',
            '04d' => 'cloud', // broken clouds
            '04n' => 'cloud',
            '09d' => 'cloud-rain', // shower rain
            '09n' => 'cloud-rain',
            '10d' => 'cloud-sun-rain', // rain day
            '10n' => 'cloud-moon-rain', // rain night
            '11d' => 'bolt', // thunderstorm
            '11n' => 'bolt',
            '13d' => 'snowflake', // snow
            '13n' => 'snowflake',
            '50d' => 'smog', // mist
            '50n' => 'smog'
        ];

        return $iconMapping[$iconCode] ?? 'question';
    }

    public function getWeatherForCity(string $city): array
    {
        try {
            $response = $this->httpClient->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
                'query' => [
                    'q' => $city,
                    'appid' => $this->apiKey,
                    'units' => 'metric',
                    'lang' => 'fr'
                ]
            ]);

            $data = $response->toArray();

            if (isset($data['main'], $data['weather'][0])) {
                return [
                    'success' => true,
                    'temperature' => round($data['main']['temp']),
                    'description' => $this->translateWeatherDescription($data['weather'][0]['description']),
                    'icon' => $this->getWeatherIcon($data['weather'][0]['icon']),
                    'humidity' => $data['main']['humidity'],
                    'wind' => round($data['wind']['speed'] * 3.6), // Conversion de m/s en km/h
                    'location' => $data['name']
                ];
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner des données par défaut
            return [
                'success' => false,
                'temperature' => 20,
                'description' => 'Information temporairement indisponible',
                'icon' => 'question',
                'humidity' => 60,
                'wind' => 10,
                'location' => $city
            ];
        }
    }
}
