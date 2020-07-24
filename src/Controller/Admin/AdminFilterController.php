<?php

namespace App\Controller\Admin;

use App\Entity\WordFilter;
use App\Form\WordFilterType;
use App\Repository\WordFilterRepository;
use App\Util\AdminUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFilterController extends AbstractController
{
    /**
     * @Route("/admin/wordfilter", name="admin_wordfilter")
     */
    public function wordFilter(WordFilterRepository $wordFilterRepository, Request $request, AdminUtil $adminUtil): Response
    {
        $wordFilter = new WordFilter();
        $wordFilterForm = $this->createForm(WordFilterType::class, $wordFilter);
        $wordFilterForm->handleRequest($request);

        if ($wordFilterForm->isSubmitted() && $wordFilterForm->isValid()) {
            $adminUtil->addBadWord($wordFilter);

            $this->addFlash(
                'success',
                $wordFilter->getBadWord().' is added to the bad words list'
            );
        }

        $wordFilterStrings = $wordFilterRepository->findAll();

        return $this->render('admin/wordfilter.html.twig', [
            'word_filter_strings' => $wordFilterStrings,
            'word_filter_form' => $wordFilterForm->createView(),
        ]);
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
            $wordFilter->getBadWord().' is now removed'
        );

        return $this->redirectToRoute('admin_wordfilter');
    }
}
