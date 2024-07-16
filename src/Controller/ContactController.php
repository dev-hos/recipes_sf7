<?php

namespace App\Controller;

use App\DTO\ContactDto;
use App\Form\ContactType;
use App\Services\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function contact(
        Request $request,
        ContactDto $contactDto,
        SendMailService $mailService
    ): Response
    {
        $form = $this->createForm(ContactType::class, $contactDto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = [
                'name' => $form->get('name')->getData(),
                'mail' => $form->get('email')->getData(),
                'message' => $form->get('message')->getData(),
            ];
            try {
                $mailService->sendEmail(
                    $contactDto->email,
                    $contactDto->services,
                    'Nouveau contact',
                    'contact',
                    compact('data')
                );
                $this->addFlash('success', 'Email envoyé avec succès');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Impossible d\'envoyer votre email !');
            }


            return $this->redirectToRoute('app_contact');

        }
        return $this->render('contact/index.html.twig', [
            'form' => $form,
        ]);
    }
}
