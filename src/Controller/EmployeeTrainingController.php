<?php

namespace App\Controller;

use App\Entity\EmployeeTraining;
use App\Form\EmployeeTrainingType;
use League\Csv\Writer;
use App\Repository\EmployeeTrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/employee/training')]
class EmployeeTrainingController extends AbstractController
{
    #[Route('/', name: 'app_employee_training_index', methods: ['GET'])]
    public function index(EmployeeTrainingRepository $employeeTrainingRepository): Response
    {
        return $this->render('employee_training/index.html.twig', [
            'employee_trainings' => $employeeTrainingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_employee_training_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $employeeTraining = new EmployeeTraining();
        $form = $this->createForm(EmployeeTrainingType::class, $employeeTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($employeeTraining);
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee_training/new.html.twig', [
            'employee_training' => $employeeTraining,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_employee_training_export', methods: ['GET'])]
    public function exportEmployees(EmployeeTrainingRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'Employee', 'Training program', 'Completion date']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getEmployee()->getFirstName().' '. $employee->getEmployee()->getLastName(), $employee->getTrainingProgram()->getProgramName(), $employee->getCompletionDate()->format('Y-m-d')]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="employees_training.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_employee_training_show', methods: ['GET'])]
    public function show(EmployeeTraining $employeeTraining): Response
    {
        return $this->render('employee_training/show.html.twig', [
            'employee_training' => $employeeTraining,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_employee_training_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EmployeeTraining $employeeTraining, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmployeeTrainingType::class, $employeeTraining);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_employee_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employee_training/edit.html.twig', [
            'employee_training' => $employeeTraining,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_employee_training_delete', methods: ['POST'])]
    public function delete(Request $request, EmployeeTraining $employeeTraining, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$employeeTraining->getId(), $request->request->get('_token'))) {
            $entityManager->remove($employeeTraining);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_employee_training_index', [], Response::HTTP_SEE_OTHER);
    }
}
