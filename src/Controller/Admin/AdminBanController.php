<?php

namespace App\Controller\Admin;

use App\Entity\Banned;
use App\Form\BannedType;
use App\Repository\BannedRepository;
use App\Util\AdminUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBanController extends AbstractController
{
    /**
     * @Route("/admin/bans", name="admin_banned")
     */
    public function banned(BannedRepository $bannedRepository, Request $request, AdminUtil $adminUtil): Response
    {
        $banned = new Banned();
        $bannedForm = $this->createForm(BannedType::class, $banned);
        $bannedForm->handleRequest($request);

        if ($bannedForm->isSubmitted() && $bannedForm->isValid()) {
            $adminUtil->banIP($banned);

            $this->addFlash(
                'success',
                $banned->getIpAddress().' is now banned :)'
            );
        }

        $bannedIPs = $bannedRepository->findAll();

        return $this->render('admin/banned/index.html.twig', [
            'banned_ips' => $bannedIPs,
            'banned_form' => $bannedForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/unban/{id}", name="admin_unban", methods={"DELETE"})
     */
    public function unban(Request $request, Banned $banned): Response
    {
        if ($this->isCsrfTokenValid('delete'.$banned->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($banned);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            $banned->getIpAddress().' is now unbanned'
        );

        return $this->redirectToRoute('admin_banned');
    }
}
