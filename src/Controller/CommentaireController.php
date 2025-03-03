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
    private $sentimentAnalyzer;

    public function __construct(SentimentAnalysisService $sentimentAnalyzer)
    {
        $this->sentimentAnalyzer = $sentimentAnalyzer;
    }

    #[Route('/', name: 'app_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        return $this->render('commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'commentaire_edit', methods: ['POST'])]
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('edit'.$commentaire->getId(), $submittedToken)) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('app_experience_show', [
                'id' => $commentaire->getExperience()->getId()
            ]);
        }

        $contenu = $request->request->get('contenu');
        if (!empty($contenu)) {
            $commentaire->setContenu($contenu);
            $commentaire->setDateModification(new \DateTime());
            
            // Re-analyze sentiment on edit
            $sentiment = $this->sentimentAnalyzer->analyzeSentiment($contenu);
            $commentaire->setSentiment($sentiment['sentiment']);
            $commentaire->setSentimentScore($sentiment['score']);
            
            $entityManager->flush();
            $this->addFlash('success', 'Comment modified successfully!');
        }

        return $this->redirectToRoute('app_experience_show', [
            'id' => $commentaire->getExperience()->getId()
        ]);
    }

    #[Route('/{id}/delete', name: 'commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete'.$commentaire->getId(), $submittedToken)) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $experienceId = $commentaire->getExperience()->getId();
        
        $entityManager->remove($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Comment deleted successfully!');

        return $this->redirectToRoute('app_experience_show', ['id' => $experienceId]);
    }

    #[Route('/experience/{id}/comment', name: 'commentaire_add', methods: ['POST'])]
    public function addComment(
        Request $request, 
        Experience $experience, 
        EntityManagerInterface $entityManager
    ): Response {
        $commentaire = new Commentaire();
        $commentaire->setExperience($experience);
        
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set creation date
            $commentaire->setDateCreation(new \DateTime());
            $commentaire->setDateModification(new \DateTime());

            // Analyze sentiment
            $sentiment = $this->sentimentAnalyzer->analyzeSentiment($commentaire->getContenu());
            
            // Set sentiment data
            $commentaire->setSentiment($sentiment['sentiment']);
            $commentaire->setSentimentScore($sentiment['score']);
            
            // Add warning for negative comments
            if ($sentiment['sentiment'] === 'négatif') {
                $this->addFlash('warning', 'Your comment appears to be negative. Please keep it constructive.');
            }

            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment has been added successfully!');
            return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
        }

        // If form is not valid, add error message
        foreach ($form->getErrors(true) as $error) {
            $this->addFlash('error', $error->getMessage());
        }

        return $this->redirectToRoute('app_experience_show', ['id' => $experience->getId()]);
    }
}
