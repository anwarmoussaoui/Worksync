<?php

namespace App\Controller;

use App\Entity\Recruitment;
use App\Form\RecruitmentType;
use App\Repository\RecruitmentRepository;
use League\Csv\Writer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/recruitment')]
class RecruitmentController extends AbstractController
{
    #[Route('/', name: 'app_recruitment_index', methods: ['GET'])]
    public function index(RecruitmentRepository $recruitmentRepository): Response
    {
        return $this->render('recruitment/index.html.twig', [
            'recruitments' => $recruitmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recruitment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recruitment = new Recruitment();
        $form = $this->createForm(RecruitmentType::class, $recruitment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recruitment);
            $entityManager->flush();

            return $this->redirectToRoute('app_recruitment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recruitment/new.html.twig', [
            'recruitment' => $recruitment,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_recruitment_export', methods: ['GET'])]
    public function exportEmployees(RecruitmentRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'Employee', 'Job Title', 'Status']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getEmployee()->getFirstName().' '. $employee->getEmployee()->getLastName(), $employee->getJob()->getTitle(), $employee->getStatus()]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="recruitment.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_recruitment_show', methods: ['GET'])]
    public function show(Recruitment $recruitment): Response
    {
        return $this->render('recruitment/show.html.twig', [
            'recruitment' => $recruitment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recruitment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recruitment $recruitment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecruitmentType::class, $recruitment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recruitment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recruitment/edit.html.twig', [
            'recruitment' => $recruitment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recruitment_delete', methods: ['POST'])]
    public function delete(Request $request, Recruitment $recruitment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recruitment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recruitment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recruitment_index', [], Response::HTTP_SEE_OTHER);
    }
}
