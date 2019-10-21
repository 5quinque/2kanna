<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BannedRepository;
use App\Entity\Banned;
use App\Form\BannedType;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(BannedRepository $bannedRepository, Request $request)
    {
        $banned = new Banned();
        $bannedForm = $this->createForm(BannedType::class, $banned);
        $bannedForm->handleRequest($request);
        $bannedIPs = $bannedRepository->findAll();

        if ($bannedForm->isSubmitted() && $bannedForm->isValid()) {
            $this->banIP($banned);
        }

        return $this->render('admin/index.html.twig', [
            'banned_ips' => $bannedIPs,
            'banned_form' => $bannedForm->createView(),
        ]);
    }

    public function banIP(Banned $banned)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($banned);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $banned->getIpAddress() . " is now banned :)"
        );
    }
}
