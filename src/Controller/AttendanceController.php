<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Form\AttendanceType;
use League\Csv\Writer;
use App\Repository\AttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attendance')]
class AttendanceController extends AbstractController
{
    #[Route('/', name: 'app_attendance_index', methods: ['GET'])]
    public function index(AttendanceRepository $attendanceRepository): Response
    {
        return $this->render('attendance/index.html.twig', [
            'attendances' => $attendanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_attendance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attendance = new Attendance();
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendance);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attendance/new.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/export', name: 'app_attendance_export', methods: ['GET'])]
    public function exportEmployees(AttendanceRepository $employeeRepository): Response
    {
        $employees = $employeeRepository->findAll();

        // Create a CSV writer instance
        $csvWriter = Writer::createFromString('');

        // Insert headers into the CSV
        $csvWriter->insertOne(['ID', 'First Name', 'Last Name', 'Check in', 'Check out']);

        // Insert employee data into the CSV
        foreach ($employees as $employee) {
            $csvWriter->insertOne([$employee->getId(), $employee->getEmployee()->getFirstName(), $employee->getEmployee()->getLastName(), $employee->getCheckInDateTime()->format('Y-m-d H:i:s'), $employee->getCheckOutDateTime()->format('Y-m-d H:i:s')]);
        }

        // Set response headers
        $response = new Response($csvWriter->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="attendances.csv"');

        return $response;
    }

    #[Route('/{id}', name: 'app_attendance_show', methods: ['GET'])]
    public function show(Attendance $attendance): Response
    {
        return $this->render('attendance/show.html.twig', [
            'attendance' => $attendance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('attendance/edit.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_delete', methods: ['POST'])]
    public function delete(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($attendance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
    }
}
