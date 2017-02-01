<?php

namespace Application\Entity\Charge;

use Application\Provider\Charge\ChargeProvider;

class Noeud
{
    /**
     * @var ChargeProvider
     */
    private $provider;

    /**
     * @var array
     */
    private $data;



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
     * @return Lien[]
     */
    public function getLiensInf()
    {
        return $this->provider->getLiensByNoeudSup($this);
    }



    /**
     * @return Lien[]
     */
    public function getLiensSup()
    {
        return $this->provider->getLiensByNoeudInf($this);
    }



    /**
     * @param bool $object
     *
     * @return \Application\Entity\Db\ElementPedagogique|int
     */
    public function getEtape($object = true)
    {
        $id = (int)$this->data['ETAPE_ID'];

        return $object ? $this->provider->getEtape($id) : $id;
    }



    /**
     * @param bool $object
     *
     * @return \Application\Entity\Db\ElementPedagogique|int
     */
    public function getElementPedagogique($object = true)
    {
        $id = (int)$this->data['ELEMENT_PEDAGOGIQUE_ID'];

        return $object ? $this->provider->getElementPedagogique($id) : $id;
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->data['CODE'];
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->data['LIBELLE'];
    }



    /**
     * @return integer
     */
    public function getGroupes()
    {
        return $this->provider->getScenarioNoeud($this)->getGroupes();
    }



    /**
     * @param integer $groupes
     *
     * @return Noeud
     */
    public function setGroupes($groupes)
    {
        $this->provider->getScenarioNoeud($this)->setGroupes($groupes);

        return $this;
    }



    /**
     * @return int
     */
    public function getChoixMinimum()
    {
        return $this->provider->getScenarioNoeud($this)->getChoixMinimum();
    }



    /**
     * @param int $choixMinimum
     */
    public function setChoixMinimum($choixMinimum)
    {
        $this->provider->getScenarioNoeud($this)->setChoixMinimum($choixMinimum);

        return $this;
    }



    /**
     * @return int
     */
    public function getChoixMaximum()
    {
        return $this->provider->getScenarioNoeud($this)->getChoixMaximum();
    }



    /**
     * @param int $choixMaximum
     */
    public function setChoixMaximum($choixMaximum)
    {
        $this->provider->getScenarioNoeud($this)->setChoixMinimum($choixMaximum);

        return $this;
    }



    /**
     * @return float
     */
    public function getAssiduite()
    {
        return $this->provider->getScenarioNoeud($this)->getAssiduite();
    }



    /**
     * @param float $assiduite
     */
    public function setAssiduite($assiduite)
    {
        $this->provider->getScenarioNoeud($this)->setAssiduite($assiduite);

        return $this;
    }



    /**
     * @param TypeHeures|integer|null $typeHeures
     *
     * @return float
     */
    public function getEffectif($typeHeures = null)
    {
        return $this->provider->getScenarioNoeud($this)->getEffectif($typeHeures);
    }



    /**
     * @param TypeHeures|integer $typeHeures
     * @param float              $effectif
     *
     * @return $this
     */
    public function setEffectif($typeHeures, $effectif)
    {
        $this->provider->getScenarioNoeud($this)->setEffectif($typeHeures, $effectif);

        return $this;
    }



    /**
     * @param TypeIntervention|integer|null $typeIntervention
     *
     * @return integer
     */
    public function getSeuilOuverture($typeIntervention = null)
    {
        return $this->provider->getScenarioNoeud($this)->getSeuilOuverture($typeIntervention);
    }



    /**
     * @param TypeIntervention|integer $typeIntervention
     * @param integer                  $seuilOuverture
     *
     * @return $this
     */
    public function setSeuilOuverture($typeIntervention, $seuilOuverture)
    {
        $this->provider->getScenarioNoeud($this)->setSeuilOuverture($typeIntervention, $seuilOuverture);

        return $this;
    }



    /**
     * @param TypeIntervention|integer|null $typeIntervention
     *
     * @return integer
     */
    public function getSeuilDedoublement($typeIntervention = null)
    {
        return $this->provider->getScenarioNoeud($this)->getSeuilDedoublement($typeIntervention);
    }



    /**
     * @param TypeIntervention|integer $typeIntervention
     * @param integer                  $seuilDedoublement
     *
     * @return $this
     */
    public function setSeuilDedoublement($typeIntervention, $seuilDedoublement)
    {
        $this->provider->getScenarioNoeud($this)->setSeuilDedoublement($typeIntervention, $seuilDedoublement);

        return $this;
    }



    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id'                  => $this->getId(),
            'code'                => $this->getCode(),
            'libelle'             => $this->getLibelle(),
            'choix-minimum'       => $this->getChoixMinimum(),
            'choix-maximum'       => $this->getChoixMaximum(),
            'groupes'             => $this->getGroupes(),
            'assiduite'           => $this->getAssiduite(),
            'effectifs'           => $this->getEffectif(),
            'seuils-ouverture'    => $this->getSeuilOuverture(),
            'seuils-dedoublement' => $this->getSeuilDedoublement(),
            'etape'               => $this->getEtape(false),
            'element-pedagogique' => $this->getElementPedagogique(false),
        ];
    }
}