# Tableau de Bord de Statistiques des Réclamations

## Description
Ce projet est un tableau de bord statistique pour visualiser les réclamations les plus fréquentes dans une application Symfony. Il utilise Google Charts pour créer un graphique interactif en 3D.

## Fonctionnalités
- Affichage des types de réclamations les plus fréquents avec un graphique en colonnes 3D
- Tableau récapitulatif des données
- Interface utilisateur moderne et réactive
- API JSON pour récupérer les données de statistiques

## Structure Technique
- **Controller**: `StatistiquesController` gérant l'affichage et l'API
- **Service**: `StatistiquesService` récupérant les données via Doctrine
- **Modèle**: Utilise l'entité `Reclamation` existante
- **Vue**: Templates Twig avec séparation du HTML et du JavaScript

## Technologies Utilisées
- Symfony 6.x
- Doctrine ORM
- Google Charts API
- jQuery et Bootstrap

## Configuration
1. Assurez-vous que les entités de votre base de données sont correctement configurées
2. Vérifiez que le service `StatistiquesService` est correctement injecté dans le contrôleur
3. Les fichiers JavaScript sont dans le dossier `public/js/`

## Routes
- `/admin/statistiques` - Page d'affichage du tableau de bord
- `/admin/api/statistiques/reclamations-frequentes` - API JSON pour les données de réclamations

## Utilisation
Accédez à la page d'administration des statistiques pour voir le graphique des réclamations les plus fréquentes. Le graphique est interactif et permet de visualiser facilement les tendances.

## Améliorations Possibles
- Ajout de filtres par date pour affiner l'analyse
- Implémentation d'autres types de statistiques (par statut, par utilisateur, etc.)
- Export des données au format CSV ou PDF
- Cache des requêtes statistiques pour améliorer les performances
