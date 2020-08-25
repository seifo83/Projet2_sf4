<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Label;
use App\Entity\Record;
use App\Repository\ArtistRepository;
use App\Repository\LabelRepository;
use App\Repository\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class RecordController extends AbstractController
{
    /**
     * liste des artistes
     * @Route("/artist", name="artist_list")
     */
    public function index(ArtistRepository $repository)
    {
        return $this->render('record/artist_list.html.twig', [
            'artist_list' => $repository->findAll(),
        ]);
    }



    /**
     * Page d'un artiste
     * @Route("/artist/{id}", name="artist_page")
     */
    public function artistPage(Artist $artist)
    {
        //dd($artist);
        return $this->render('record/artist_page.html.twig', [
            'artist' => $artist

        ]);

    }

     /**
     * liste des albums
     * @Route("/record", name="record_list")
     */
    public function recordList(RecordRepository $repository)
    {
        return $this->render('record/record_list.html.twig', [
            'record_list' => $repository->findAll(),
        ]);
    }



    /**
     * Page d'un album
     * @Route("/record/{id}", name="record_page")
     */
    public function  recordPage(Record $record)
    {
        return $this->render('record/record_page.html.twig', [
            'record' => $record
        ]);
    }


    /**
     * Nouveaux albums
     * @Route("/news", name="record_news")
     * @IsGranted("ROLE_ADMIN")
     */
    public function recordNews(RecordRepository $repository)
    {
        /**
         * on veut récuperer albums sortis il y a  moins d'un moins
         * requete:
         * Select *
         * From record
         * where releasedAt  >= $date   => release et la date de sorti il doit superieur aà un moins 
         * on peut programer $date  pour etre modifiable est etre calculer a 30 jours
         * 
         * => pour cela on creer une méthode finfNews() dans recordRepository.php
         */


        return $this->render('record/record_news.html.twig', [
            'record_news' => $repository->findNews(),
        ]);

    }


    /**
     * liste Label
     * @Route("/label", name="label_list")
     */
    public function labelList(LabelRepository $repository)
    {
        return $this->render('record/label_list.html.twig', [
            'label_list' => $repository->findAll(),
        ]);
    }

    /**
     * Page label
     * @Route("/label/{id}", name="label_page")
     */
    public function labelPage(Label $label)
    {
        //dd($artist);
        return $this->render('record/label_page.html.twig', [
            'label' => $label

        ]);

    }





}
