<?php

namespace App\Controller;

use App\Entity\FiltreSorties;
use App\Entity\Sortie;
use App\Form\AnnulationSortieType;
use App\Form\FiltreSortiesType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/", name="app_sortie_index", methods={"GET", "POST"})
     */
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $filtres = new FiltreSorties();
        $form = $this->createForm(FiltreSortiesType::class, $filtres);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $sorties = $sortieRepository->findFiltreSorties($filtres);
            return $this->renderForm('sortie/index.html.twig', [
                'sorties' => $sorties,
                'filtreSorties' => $form
                ]);
        }

        return $this->renderForm('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
            'filtreSorties' => $form
        ]);
    }

    /**
     * @Route("/new", name="app_sortie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SortieRepository $sortieRepository,EtatRepository $etatRepository, ParticipantRepository $participantRepository): Response
    {
        $sortie = new Sortie();
        $particiCrea = $this->getUser();
        $etatSortie = $etatRepository->find(1);
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        $sortie->setOrganisateur($particiCrea);
        $sortie->addParticipant($particiCrea);
        $sortie->setSiteOrganisateur($particiCrea->getCampus());
        $sortie->setEtat($etatSortie);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sortie_show", methods={"GET"})
     */
    public function show(Sortie $sortie): Response
    {
        $listParticip = $sortie->getParticipantSortie();
        dump($listParticip);
        if(!$sortie){
            throw $this->createNotFoundException('Sortie Inconnu!');
        }
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'listParticip' => $listParticip
        ]);
    }

    /**
     * @Route("/follow/{id}", name="app_sortie_follow")
     */
    public function follow(Sortie $sortie,SortieRepository $sortieRepository,
                            EntityManagerInterface $em
    ): Response
    {
        if(!$sortie){
            throw $this->createNotFoundException('Sortie Inconnu!');
        }
        $participant= $this->getUser();
        $sortie->addParticipant($participant);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous etes inscrit à la Sortie!');
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/unFollow/{id}", name="app_sortie_unFollow")
     */
    public function unFollow(SortieRepository $sortieRepository, Sortie $sortie,
                           EntityManagerInterface $em): Response
    {
        if (!$sortie) {
            throw $this->createNotFoundException('Sortie Inconnu!');
        }
        $participant= $this->getUser();
        $sortie->removeParticipant($participant);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous êtes désinscrits à la Sortie!');
        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/edit", name="app_sortie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sortie_delete", methods={"POST"})
     */
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie, true);
        }

        return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cancel/{id}", name="app_sortie_cancel", methods={"GET", "POST"})
     */
    public function cancel(Request $request, Sortie $sortie, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $etat = $etatRepository->find(5);
        $formCancel = $this->createForm(AnnulationSortieType::class, $sortie);
        $formCancel->handleRequest($request);
        $sortie->setEtat($etat);
        if ($formCancel->isSubmitted()) {
            $sortieRepository->add($sortie, true);
            return $this->redirectToRoute('app_sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/cancel.html.twig', [
            'sortie' => $sortie,
            'formCancel' => $formCancel
        ]);
    }



}
