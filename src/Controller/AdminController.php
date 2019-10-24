<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\BannedRepository;
use App\Entity\Banned;
use App\Entity\WordFilter;
use App\Form\BannedType;
use App\Form\WordFilterType;
use App\Repository\WordFilterRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index(BannedRepository $bannedRepository, WordFilterRepository $wordFilterRepository): Response
    {
        $bannedCount = $bannedRepository->countEntities();
        $wordFilterCount = $wordFilterRepository->countEntities();

        return $this->render('admin/index.html.twig', [
            'banned_count' => $bannedCount,
            'word_filter_count' => $wordFilterCount,
        ]);
    }

    /**
     * @Route("/admin/wordfilter", name="admin_wordfilter")
     */
    public function wordFilter(WordFilterRepository $wordFilterRepository, Request $request): Response
    {
        $wordFilter = new WordFilter();
        $wordFilterForm = $this->createForm(WordFilterType::class, $wordFilter);
        $wordFilterForm->handleRequest($request);

        $wordFilterStrings = $wordFilterRepository->findAll();

        if ($wordFilterForm->isSubmitted() && $wordFilterForm->isValid()) {
            $this->addBadWord($wordFilter);
        }

        return $this->render('admin/wordfilter.html.twig', [
            'word_filter_strings' => $wordFilterStrings,
            'word_filter_form' => $wordFilterForm->createView()
        ]);
    }

    /**
     * @Route("/admin/bans", name="admin_banned")
     */
    public function banned(BannedRepository $bannedRepository, Request $request): Response
    {
        $banned = new Banned();
        $bannedForm = $this->createForm(BannedType::class, $banned);
        $bannedForm->handleRequest($request);
        $bannedIPs = $bannedRepository->findAll();

        if ($bannedForm->isSubmitted() && $bannedForm->isValid()) {
            $this->banIP($banned);
        }

        return $this->render('admin/banned.html.twig', [
            'banned_ips' => $bannedIPs,
            'banned_form' => $bannedForm->createView()
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
            $banned->getIpAddress() . " is now unbanned"
        );

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/removefilter/{id}", name="admin_removefilter", methods={"DELETE"})
     */
    public function removeFilter(Request $request, WordFilter $wordFilter)
    {
        if ($this->isCsrfTokenValid('delete'.$wordFilter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($wordFilter);
            $entityManager->flush();
        }

        $this->addFlash(
            'success',
            $wordFilter->getBadWord() . " is now removed"
        );

        return $this->redirectToRoute('admin');
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

    public function addBadWord(WordFilter $wordFilter)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($wordFilter);
        $entityManager->flush();

        $this->addFlash(
            'success',
            $wordFilter->getBadWord() . " is added to the bad words list"
        );
    }
}
