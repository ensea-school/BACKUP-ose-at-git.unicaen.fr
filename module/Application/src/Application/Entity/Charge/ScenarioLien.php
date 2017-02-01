<?php

namespace Application\Entity\Charge;

use Application\Provider\Charge\ChargeProvider;

class ScenarioLien
{
    /**
     * @var ChargeProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $changes = [];



    public function __construct(ChargeProvider $provider, array $data)
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
     * @return Lien|int
     */
    public function getLien($object = true)
    {
        $id = (int)$this->data['LIEN_ID'];

        return $object ? $this->provider->getLien($id) : $id;
    }



    /**
     * @param bool $object
     *
     * @return \Application\Entity\Db\Scenario|int
     */
    public function getScenario($object = true)
    {
        $id = (int)$this->data['SCENARIO_ID'];

        return $object ? $this->provider->getScenario($id) : $id;
    }



    /**
     * @return boolean
     */
    public function isActif()
    {
        return $this->data['ACTIF'] == '1';
    }



    /**
     * @param boolean $actif
     *
     * @return $this
     */
    public function setActif($actif)
    {
        $this->data['ACTIF']    = $actif ? '1' : '0';
        $this->changes['ACTIF'] = $this->data['ACTIF'];

        return $this;
    }



    /**
     * @return float
     */
    public function getPoids()
    {
        return (float)$this->data['POIDS'];
    }



    /**
     * @param $poids
     *
     * @return $this
     */
    public function setPoids($poids)
    {
        $this->data['POIDS']    = (string)(float)$poids;
        $this->changes['POIDS'] = $this->data['POIDS'];

        return $this;
    }
}