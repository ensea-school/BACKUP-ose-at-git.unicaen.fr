<?php

namespace Application\Entity\Chargens;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\Scenario;
use Application\Entity\Db\TypeIntervention;
use Application\Provider\Chargens\ChargensProvider;

class Noeud
{
    /**
     * @var ChargensProvider
     */
    private $provider;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var boolean
     */
    private $liste = false;

    /**
     * @var integer
     */
    private $etape = null;

    /**
     * @var integer
     */
    private $elementPedagogique = null;

    /**
     * @var TypeIntervention[]
     */
    private $typeIntervention = [];

    /**
     * @var array
     */
    private $scenarioNoeud = [];

    /**
     * @var integer
     */
    private $nbLiensSup;

    /**
     * @var integer
     */
    private $nbLiensInf;



    /**
     * Noeud constructor.
     *
     * @param ChargensProvider $provider
     */
    public function __construct(ChargensProvider $provider)
    {
        $this->provider = $provider;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return Noeud
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Noeud
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return Noeud
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return bool
     */
    public function isListe()
    {
        return $this->liste;
    }



    /**
     * @param bool $liste
     *
     * @return Noeud
     */
    public function setListe($liste)
    {
        $this->liste = $liste;

        return $this;
    }



    /**
     * @param bool $object
     *
     * @return Etape|int
     */
    public function getEtape($object = true)
    {
        return $object ? $this->provider->getEtape($this->etape) : $this->etape;
    }



    /**
     * @param Etape|int $etape
     *
     * @return Noeud
     */
    public function setEtape($etape)
    {
        if ($etape instanceof Etape) {
            $etape = $etape->getId();
        }
        $this->etape = $etape;

        return $this;
    }



    /**
     * @param bool $object
     *
     * @return ElementPedagogique|int
     */
    public function getElementPedagogique($object = true)
    {
        return $object ? $this->provider->getElementPedagogique($this->elementPedagogique) : $this->elementPedagogique;
    }



    /**
     * @param ElementPedagogique|int $elementPedagogique
     *
     * @return Noeud
     */
    public function setElementPedagogique($elementPedagogique)
    {
        if ($elementPedagogique instanceof ElementPedagogique) {
            $elementPedagogique = $elementPedagogique->getId();
        }
        $this->elementPedagogique = $elementPedagogique;

        return $this;
    }



    /**
     * @return TypeIntervention[]
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     * @param TypeIntervention|int $typeIntervention
     *
     * @return $this
     * @throws \Exception
     */
    public function addTypeIntervention($typeIntervention)
    {
        if (!$typeIntervention instanceof TypeIntervention) {
            $typeIntervention = $this->provider->getEntities()->get(TypeIntervention::class, $typeIntervention);
        }

        if (!$typeIntervention) {
            throw new \Exception('Type d\'intervention inconnu');
        }

        if (!array_key_exists($typeIntervention->getId(), $this->typeIntervention)) {
            $this->typeIntervention[$typeIntervention->getId()] = $typeIntervention;
        }

        return $this;
    }



    /**
     * @param TypeIntervention|int|null $typeIntervention
     *
     * @return $this
     * @throws \Exception
     */
    public function removeTypeIntervention($typeIntervention)
    {
        if (!$typeIntervention) {
            $this->typeIntervention = [];

            return $this;
        }

        if (!$typeIntervention instanceof TypeIntervention) {
            $typeIntervention = $this->provider->getEntities()->get(TypeIntervention::class, $typeIntervention);
        }

        if (!$typeIntervention) {
            throw new \Exception('Type d\'intervention inconnu');
        }

        if (array_key_exists($typeIntervention->getId(), $this->typeIntervention)) {
            unset($this->typeIntervention[$typeIntervention->getId()]);
        }

        return $this;
    }



    /**
     * @param Scenario|null $scenario
     *
     * @return bool
     */
    public function hasScenarioNoeud(Scenario $scenario = null)
    {
        if (!$scenario) {
            $scenario = $this->provider->getScenario();
        }

        if (!$scenario) {
            throw new \Exception('Le scénario n\'a pas été défini');
        }

        return array_key_exists($scenario->getId(), $this->scenarioNoeud);
    }



    /**
     * @param Scenario|null $scenario
     *
     * @return ScenarioNoeud
     */
    public function getScenarioNoeud(Scenario $scenario = null)
    {
        if (!$scenario) {
            $scenario = $this->provider->getScenario();
        }

        if (!$scenario) {
            throw new \Exception('Le scénario n\'a pas été défini');
        }

        if (!array_key_exists($scenario->getId(), $this->scenarioNoeud)) {
            $this->scenarioNoeud[$scenario->getId()] = new ScenarioNoeud($this, $scenario);
        }

        return $this->scenarioNoeud[$scenario->getId()];
    }



    /**
     * @param ScenarioNoeud $scenarioNoeud
     *
     * @return $this
     * @throws \Exception
     */
    public function addScenarioNoeud(ScenarioNoeud $scenarioNoeud)
    {
        if (!$scenarioNoeud->getScenario()) {
            throw new \Exception('Le scénario du noeud n\'a pas été défini');
        }

        $scenarioNoeud->setNoeud($this);

        $this->scenarioNoeud[$scenarioNoeud->getScenario()->getId()] = $scenarioNoeud;

        return $this;
    }



    /**
     * @return $this
     */
    public function removeScenarioNoeud(Scenario $scenario = null)
    {
        if ($scenario) {
            unset($this->scenarioNoeud[$scenario->getId()]);
        } else {
            $this->scenarioNoeud = [];
        }

        return $this;
    }



    /**
     * @return Lien[]
     */
    public function getLiensInf()
    {
        return $this->provider->getLiens()->getLiensByNoeudSup($this);
    }



    /**
     * @return Lien[]
     */
    public function getLiensSup()
    {
        return $this->provider->getLiens()->getLiensByNoeudInf($this);
    }



    /**
     * @return int
     */
    public function getNbLiensSup()
    {
        return $this->nbLiensSup;
    }



    /**
     * @param int $nbLiensSup
     *
     * @return Noeud
     */
    public function setNbLiensSup($nbLiensSup)
    {
        $this->nbLiensSup = $nbLiensSup;

        return $this;
    }



    /**
     * @return int
     */
    public function getNbLiensInf()
    {
        return $this->nbLiensInf;
    }



    /**
     * @param int $nbLiensInf
     *
     * @return Noeud
     */
    public function setNbLiensInf($nbLiensInf)
    {
        $this->nbLiensInf = $nbLiensInf;

        return $this;
    }



    /**
     * @param array $data
     *
     * @return $this
     */
    public function fromArray(array $data)
    {
        if (array_key_exists('assiduite', $data)) {
            $assiduite = (float)$data['assiduite'];
            if ($assiduite != $this->getAssiduite()) {
                $this->setAssiduite($assiduite);
            }
        }

        if (array_key_exists('effectifs', $data)) {
            $effectifs = (array)$data['effectifs'];
            foreach ($effectifs as $typeHeures => $effectif) {
                $effectif = (int)$effectif;
                if ($effectif != $this->getEffectif($typeHeures)) {
                    $this->setEffectif($typeHeures, $effectif);
                }
            }
        }

        if (array_key_exists('seuils-ouverture', $data)) {
            $seuilsOuverture = (array)$data['seuils-ouverture'];
            foreach ($seuilsOuverture as $typeIntervention => $seuilOuverture) {
                $seuilOuverture = (int)$seuilOuverture;
                if ($seuilOuverture != $this->getSeuilOuverture($typeIntervention)) {
                    $this->setSeuilOuverture($typeIntervention, $seuilOuverture);
                }
            }
        }

        if (array_key_exists('seuils-dedoublement', $data)) {
            $seuilsDedoublement = (array)$data['seuils-dedoublement'];
            foreach ($seuilsDedoublement as $typeIntervention => $seuilDedoublement) {
                $seuilDedoublement = (int)$seuilDedoublement;
                if ($seuilDedoublement != $this->getSeuilDedoublement($typeIntervention)) {
                    $this->setSeuilDedoublement($typeIntervention, $seuilDedoublement);
                }
            }
        }

        return $this;
    }
}