<?php

namespace App\Controller;

use App\Entity\Benefit;
use App\Form\BenefitType;
use League\Csv\Writer;
use App\Repository\BenefitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/benefit')]
class BenefitController extends AbstractController
{
    #[Route('/', name: 'app_benefit_index', methods: ['GET'])]
    public function index(BenefitRepository $benefitRepository): Response
    {
        return $this->render('benefit/index.html.twig', [
            'benefits' => $benefitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_benefit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $benefit = new Benefit();
        $form = $this->createForm(BenefitType::class, $benefit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($benefit);
            $entityManager->flush();

            return $this->redirectToRoute('app_benefit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('benefit/new.html.twig', [
            'benefit' => $benefit,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_benefit_export', methods: ['GET'])]
    public function exportEmployees(BenefitRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'Employee', 'Benefit type', 'Coverage details']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getEmployee()->getFirstName().' '. $employee->getEmployee()->getLastName(), $employee->getBenefitType(), $employee->getCoverageDetails()]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="benefits.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_benefit_show', methods: ['GET'])]
    public function show(Benefit $benefit): Response
    {
        return $this->render('benefit/show.html.twig', [
            'benefit' => $benefit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_benefit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Benefit $benefit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BenefitType::class, $benefit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_benefit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('benefit/edit.html.twig', [
            'benefit' => $benefit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_benefit_delete', methods: ['POST'])]
    public function delete(Request $request, Benefit $benefit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$benefit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($benefit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_benefit_index', [], Response::HTTP_SEE_OTHER);
    }
}
