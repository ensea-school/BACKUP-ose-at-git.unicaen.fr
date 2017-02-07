<?php

namespace Application\Entity\Chargens;

use Application\Provider\Chargens\ChargensProvider;

class Lien
{
    /**
     * @var ChargensProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $data;



    public function __construct(ChargensProvider $provider, array $data)
    {
        $this->provider = $provider;
        $this->data     = $data;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->data['ID'];
    }



    /**
     * @param bool $object
     *
     * @return Noeud|int
     */
    public function getNoeudSup($object = true)
    {
        $id = (int)$this->data['NOEUD_SUP_ID'];

        return $object ? $this->provider->getNoeud($id) : $id;
    }



    /**
     * @param bool $object
     *
     * @return Noeud|int
     */
    public function getNoeudInf($object = true)
    {
        $id = (int)$this->data['NOEUD_INF_ID'];

        return $object ? $this->provider->getNoeud($id) : $id;
    }



    /**
     * @return bool
     */
    public function isActif()
    {
        return $this->provider->getScenarioLien($this)->isActif();
    }



    /**
     * @param bool $actif
     *
     * @return Lien
     */
    public function setActif($actif)
    {
        $this->provider->getScenarioLien($this)->setActif($actif);

        return $this;
    }



    /**
     * @return float
     */
    public function getPoids()
    {
        return $this->provider->getScenarioLien($this)->getPoids();
    }



    /**
     * @param float $poids
     *
     * @return Lien
     */
    public function setPoids($poids)
    {
        $this->provider->getScenarioLien($this)->setPoids($poids);

        return $this;
    }



    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'    => $this->getId(),
            'noeud-sup' => $this->getNoeudSup(false),
            'noeud-inf' => $this->getNoeudInf(false),
            'actif' => $this->isActif(),
            'poids' => $this->getPoids(),
        ];
    }
}