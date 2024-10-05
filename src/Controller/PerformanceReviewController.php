<?php

namespace App\Controller;

use App\Entity\PerformanceReview;
use App\Form\PerformanceReviewType;
use League\Csv\Writer;
use App\Repository\PerformanceReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/performance')]
class PerformanceReviewController extends AbstractController
{
    #[Route('/', name: 'app_performance_review_index', methods: ['GET'])]
    public function index(PerformanceReviewRepository $performanceReviewRepository): Response
    {
        return $this->render('performance_review/index.html.twig', [
            'performance_reviews' => $performanceReviewRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_performance_review_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $performanceReview = new PerformanceReview();
        $form = $this->createForm(PerformanceReviewType::class, $performanceReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($performanceReview);
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance_review/new.html.twig', [
            'performance_review' => $performanceReview,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_performance_review_export', methods: ['GET'])]
    public function exportEmployees(PerformanceReviewRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'Employee', 'Reviewer', 'Rating', 'Coment', 'Review Date']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getEmployee()->getFirstName().' '. $employee->getEmployee()->getLastName(), $employee->getReviewer()->getFirstName().' '. $employee->getReviewer()->getLastName(), $employee->getRating(), $employee->getComment(), $employee->getReviewDate()->format('Y-m-d')]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="performance_reviews.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_performance_review_show', methods: ['GET'])]
    public function show(PerformanceReview $performanceReview): Response
    {
        return $this->render('performance_review/show.html.twig', [
            'performance_review' => $performanceReview,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_performance_review_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PerformanceReview $performanceReview, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PerformanceReviewType::class, $performanceReview);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_performance_review_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('performance_review/edit.html.twig', [
            'performance_review' => $performanceReview,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_performance_review_delete', methods: ['POST'])]
    public function delete(Request $request, PerformanceReview $performanceReview, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$performanceReview->getId(), $request->request->get('_token'))) {
            $entityManager->remove($performanceReview);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_performance_review_index', [], Response::HTTP_SEE_OTHER);
    }
}
