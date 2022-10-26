<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;

class FiltreSorties
{
    /**
     * @var Campus|null
     *
     */
    private $campusFiltre;

    /**
     * @var string|null
     */
    private $motCle;

    /**
     * @var Date|null
     */
    private $dateDebutRech;

    /**
     * @var Date|null
     */
    private $dateFinRech;

    /**
     * @var int|null
     */
    private $organisateurSortie;

    /**
     * @var int|null
     */
    private $inscrit;

    /**
     * @var int|null
     */
    private $nonInscrit;

    /**
     * @var int|null
     */
    private $etatFiltre;

    /**
     * @return Campus|null
     */
    public function getCampusFiltre(): ?Campus
    {
        return $this->campusFiltre;
    }

    /**
     * @param Campus|null $campusFiltre
     * @return FiltreSorties
     */
    public function setCampusFiltre(Campus $campusFiltre): FiltreSorties
    {
        $this->campusFiltre = $campusFiltre;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMotCle(): ?string
    {
        return $this->motCle;
    }

    /**
     * @param string|null $motCle
     * @return FiltreSorties
     */
    public function setMotCle(string $motCle): FiltreSorties
    {
        $this->motCle = $motCle;
        return $this;
    }

    /**
     * @return Date|null
     */
    public function getDateDebutRech(): ?Date
    {
        return $this->dateDebutRech;
    }

    /**
     * @param Date|null $dateDebutRech
     * @return FiltreSorties
     */
    public function setDateDebutRech(Date $dateDebutRech): FiltreSorties
    {
        $this->dateDebutRech = $dateDebutRech;
        return $this;
    }

    /**
     * @return Date|null
     */
    public function getDateFinRech(): ?Date
    {
        return $this->dateFinRech;
    }

    /**
     * @param Date|null $dateFinRech
     * @return FiltreSorties
     */
    public function setDateFinRech(Date $dateFinRech): FiltreSorties
    {
        $this->dateFinRech = $dateFinRech;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrganisateurSortie(): ?int
    {
        return $this->organisateurSortie;
    }

    /**
     * @param int|null $organisateurSortie
     * @return FiltreSorties
     */
    public function setOrganisateurSortie(int $organisateurSortie): FiltreSorties
    {
        $this->organisateurSortie = $organisateurSortie;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInscrit(): ?int
    {
        return $this->inscrit;
    }

    /**
     * @param int|null $inscrit
     * @return FiltreSorties
     */
    public function setInscrit(int $inscrit): FiltreSorties
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNonInscrit(): ?int
    {
        return $this->nonInscrit;
    }

    /**
     * @param int|null $nonInscrit
     * @return FiltreSorties
     */
    public function setNonInscrit(int $nonInscrit): FiltreSorties
    {
        $this->nonInscrit = $nonInscrit;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getEtatFiltre(): ?int
    {
        return $this->etatFiltre;
    }

    /**
     * @param int|null $etatFiltre
     * @return FiltreSorties
     */
    public function setEtatFiltre(int $etatFiltre): FiltreSorties
    {
        $this->etatFiltre = $etatFiltre;
        return $this;
    }


}