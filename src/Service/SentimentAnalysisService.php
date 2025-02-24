<?php

namespace App\Service;

class SentimentAnalysisService
{
    private array $positiveWords = [
        'excellent', 'super', 'génial', 'fantastique', 'incroyable', 
        'magnifique', 'parfait', 'merveilleux', 'extraordinaire',
        'agréable', 'heureux', 'content', 'satisfait', 'recommande',
        'aime', 'adore', 'formidable', 'superbe', 'impressionnant'
    ];

    private array $negativeWords = [
        'mauvais', 'horrible', 'terrible', 'décevant', 'médiocre',
        'nul', 'désagréable', 'déplorable', 'catastrophique', 'déteste',
        'ennuyeux', 'pénible', 'insatisfait', 'déçu', 'éviter',
        'problème', 'difficile', 'pauvre', 'insuffisant'
    ];

    public function analyzeSentiment(string $text): array
    {
        $text = strtolower($text);
        $words = str_word_count($text, 1, 'àáâãäçèéêëìíîïñòóôõöùúûüýÿ');

        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($words as $word) {
            if (in_array($word, $this->positiveWords)) {
                $positiveCount++;
            }
            if (in_array($word, $this->negativeWords)) {
                $negativeCount++;
            }
        }

        $totalWords = count($words);
        $sentimentScore = $totalWords > 0 
            ? ($positiveCount - $negativeCount) / $totalWords 
            : 0;

        return [
            'score' => $sentimentScore,
            'positive_words' => $positiveCount,
            'negative_words' => $negativeCount,
            'total_words' => $totalWords,
            'sentiment' => $this->getSentimentCategory($sentimentScore)
        ];
    }

    private function getSentimentCategory(float $score): string
    {
        if ($score > 0.1) {
            return 'positif';
        } elseif ($score < -0.1) {
            return 'négatif';
        }
        return 'neutre';
    }
} 