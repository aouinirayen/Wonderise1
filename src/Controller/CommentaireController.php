<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Entity\Experience;
use App\Repository\CommentaireRepository;
use App\Service\SentimentAnalysisService;

#[Route('/commentaire')]
class CommentaireController extends AbstractController
{
    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/commentaire/{id}/edit', name: 'commentaire_edit', methods: ['POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('edit'.$commentaire->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $contenu = $request->request->get('contenu');
        if (!empty($contenu)) {
            $commentaire->setContenu($contenu);
            $commentaire->setDateModification(new \DateTime());
            
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire modifié avec succès !');
        }

        return $this->redirectToRoute('app_experience_show', [
            'id' => $commentaire->getExperience()->getId()
        ]);
    }

    #[Route('/commentaire/{id}/delete', name: 'commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$commentaire->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Token CSRF invalide');
        }

        $experienceId = $commentaire->getExperience()->getId();
        
        $entityManager->remove($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire supprimé avec succès !');

        return $this->redirectToRoute('app_experience_show', ['id' => $experienceId]);
    }

    #[Route('/experience/{id}/comment', name: 'commentaire_add', methods: ['POST'])]
    public function addComment(
        Request $request, 
        Experience $experience, 
        EntityManagerInterface $entityManager,
        SentimentAnalysisService $sentimentAnalyzer
    ): Response {
        $commentaire = new Commentaire();
        $commentaire->setExperience($experience);
        
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Analyser le sentiment du commentaire
            $sentiment = $sentimentAnalyzer->analyzeSentiment($commentaire->getContenu());
            
            // Sauvegarder le sentiment et le score
            $commentaire->setSentiment($sentiment['sentiment']);
            $commentaire->setSentimentScore($sentiment['score']);
            
            // Ajouter un message d'avertissement si nécessaire
            if ($sentiment['sentiment'] === 'négatif') {
                $this->addFlash('warning', 'Votre commentaire semble négatif. Merci de rester constructif.');
            }

            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Votre commentaire a été ajouté avec succès !');
            return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
    }
}
