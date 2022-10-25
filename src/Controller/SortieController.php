<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\AnnulationSortieType;
use App\Form\SortieType;
use App\Entity\Participant;
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
     * @Route("/", name="app_sortie_index", methods={"GET"})
     */
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
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
        if(!$sortie){
            throw $this->createNotFoundException('Sortie Inconnu!');
        }
        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
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
        dump($participant);
        $sortie->addParticipant($participant);
        dump($sortie);
        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Vous etes inscrit à la Sortie!');
        return $this->render('sortie/index.html.twig', [
        'sorties' => $sortieRepository->findAll(),
    ]);

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
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
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
