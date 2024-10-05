<?php

namespace App\Controller;

use App\Entity\TrainingProgram;
use App\Form\TrainingProgramType;
use App\Repository\TrainingProgramRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Writer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/training')]
class TrainingProgramController extends AbstractController
{
    #[Route('/', name: 'app_training_program_index', methods: ['GET'])]
    public function index(TrainingProgramRepository $trainingProgramRepository): Response
    {
        return $this->render('training_program/index.html.twig', [
            'training_programs' => $trainingProgramRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_training_program_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $trainingProgram = new TrainingProgram();
        $form = $this->createForm(TrainingProgramType::class, $trainingProgram);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($trainingProgram);
            $entityManager->flush();

            return $this->redirectToRoute('app_training_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_program/new.html.twig', [
            'training_program' => $trainingProgram,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_training_program_export', methods: ['GET'])]
    public function exportEmployees(TrainingProgramRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'Program Name', 'Description', 'Start Date', 'End Date']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getProgramName(), $employee->getDescription(), $employee->getStartDate()->format('Y-m-d'), $employee->getEndDate()->format('Y-m-d')]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="training_program.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_training_program_show', methods: ['GET'])]
    public function show(TrainingProgram $trainingProgram): Response
    {
        return $this->render('training_program/show.html.twig', [
            'training_program' => $trainingProgram,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_training_program_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TrainingProgram $trainingProgram, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrainingProgramType::class, $trainingProgram);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_training_program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('training_program/edit.html.twig', [
            'training_program' => $trainingProgram,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_training_program_delete', methods: ['POST'])]
    public function delete(Request $request, TrainingProgram $trainingProgram, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trainingProgram->getId(), $request->request->get('_token'))) {
            $entityManager->remove($trainingProgram);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_training_program_index', [], Response::HTTP_SEE_OTHER);
    }
}
