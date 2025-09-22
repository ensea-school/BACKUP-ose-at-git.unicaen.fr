<?php

namespace Chargens\Entity;

use Chargens\Entity\Db\Scenario;
use Chargens\Provider\ChargensProvider;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeIntervention;

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
     * @var integer
     */
    private $structure = null;

    /**
     * @var TypeIntervention[]
     */
    private $typeIntervention = [];

    /**
     * @var array
     */
    private $scenarioNoeud = [];

    /**
     * @var array
     */
    private $seuilParDefaut = [];

    /**
     * @var float[]
     */
    private $heures = [];

    /**
     * @var float[]
     */
    private $hetd = [];

    /**
     * @var integer
     */
    private $nbLiensSup;

    /**
     * @var integer
     */
    private $nbLiensInf;

    /**
     * @var boolean
     */
    private $canEditAssiduite = false;

    /**
     * @var boolean
     */
    private $canEditSeuils = false;

    /**
     * @var boolean
     */
    private $canEditEffectifs = false;



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
        return $object ? $this->provider->getEntities()->get(Etape::class, $this->etape) : $this->etape;
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
        return $object ? $this->provider->getEntities()->get(ElementPedagogique::class, $this->elementPedagogique) : $this->elementPedagogique;
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
     * @param bool $object
     *
     * @return Structure|int
     */
    public function getStructure($object = true)
    {
        return $object ? $this->provider->getEntities()->get(Structure::class, $this->structure) : $this->structure;
    }



    /**
     * @param Structure|int $structure
     *
     * @return Noeud
     */
    public function setStructure($structure)
    {
        if ($structure instanceof Structure) {
            $structure = $structure->getId();
        }
        $this->structure = $structure;

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
    public function hasScenarioNoeud(?Scenario $scenario = null)
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
    public function getScenarioNoeud(?Scenario $scenario = null)
    {
        if (!$scenario) {
            $scenario = $this->provider->getScenario();
        }

        if (!$scenario) {
            throw new \Exception('Le scénario n\'a pas été défini');
        }

        if (!$this->hasScenarioNoeud($scenario)) {
            $scenarioNoeud = new ScenarioNoeud($this, $scenario);
            $this->addScenarioNoeud($scenarioNoeud);
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
    public function removeScenarioNoeud(?Scenario $scenario = null)
    {
        if ($scenario) {
            unset($this->scenarioNoeud[$scenario->getId()]);
        } else {
            $this->scenarioNoeud = [];
        }

        return $this;
    }



    /**
     * @param Scenario|int         $scenario
     * @param TypeIntervention|int $typeIntervention
     *
     * @return bool
     */
    public function hasSeuilParDefaut($scenario, $typeIntervention = null)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();
        if ($typeIntervention instanceof TypeIntervention) $typeIntervention = $typeIntervention->getId();

        if ($typeIntervention) {
            return isset($this->seuilParDefaut[$scenario][$typeIntervention]);
        } else {
            return isset($this->seuilParDefaut[$scenario]);
        }
    }



    /**
     * @param Scenario|int              $scenario
     * @param TypeIntervention|int|null $typeIntervention
     *
     * @return array|integer|null
     */
    public function getSeuilParDefaut($scenario, $typeIntervention = null)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();
        if ($typeIntervention instanceof TypeIntervention) $typeIntervention = $typeIntervention->getId();

        if ($this->hasSeuilParDefaut($scenario, $typeIntervention)) {
            if ($typeIntervention) {
                return $this->seuilParDefaut[$scenario][$typeIntervention];
            } else {
                return $this->seuilParDefaut[$scenario];
            }
        } else {
            return $typeIntervention ? null : [];
        }
    }



    /**
     * @param Scenario|int         $scenario
     * @param TypeIntervention|int $typeIntervention
     * @param integer|null         $seuilParDefaut
     *
     * @return $this
     */
    public function setSeuilParDefaut($scenario, $typeIntervention, $seuilParDefaut)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();
        if ($typeIntervention instanceof TypeIntervention) $typeIntervention = $typeIntervention->getId();

        $this->seuilParDefaut[$scenario][$typeIntervention] = $seuilParDefaut;

        return $this;
    }



    /**
     * @param Scenario|int $scenario
     *
     * @return array|float|null
     */
    public function getHeures($scenario)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();

        if ($scenario) {
            if (isset($this->heures[$scenario])) {
                return $this->heures[$scenario];
            } else {
                return null;
            }
        } else {
            return $this->heures;
        }
    }



    /**
     * @param Scenario|int $scenario
     * @param float|null   $heures
     *
     * @return $this
     */
    public function setHeures($scenario, $heures)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();

        $this->heures[$scenario] = $heures;

        return $this;
    }



    /**
     * @param Scenario|int $scenario
     *
     * @return array|float|null
     */
    public function getHetd($scenario)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();

        if ($scenario) {
            if (isset($this->hetd[$scenario])) {
                return $this->hetd[$scenario];
            } else {
                return null;
            }
        } else {
            return $this->hetd;
        }
    }



    /**
     * @param Scenario|int $scenario
     * @param float|null   $hetd
     *
     * @return $this
     */
    public function setHetd($scenario, $hetd)
    {
        if ($scenario instanceof Scenario) $scenario = $scenario->getId();

        $this->hetd[$scenario] = $hetd;

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
     * @return bool
     */
    public function isCanEditAssiduite()
    {
        return $this->canEditAssiduite;
    }



    /**
     * @param bool $canEditAssiduite
     *
     * @return Noeud
     */
    public function setCanEditAssiduite($canEditAssiduite)
    {
        $this->canEditAssiduite = $canEditAssiduite;

        return $this;
    }



    /**
     * @return bool
     */
    public function isCanEditSeuils()
    {
        return $this->canEditSeuils;
    }



    /**
     * @param bool $canEditSeuils
     *
     * @return Noeud
     */
    public function setCanEditSeuils($canEditSeuils)
    {
        $this->canEditSeuils = $canEditSeuils;

        return $this;
    }



    /**
     * @return bool
     */
    public function isCanEditEffectifs()
    {
        return $this->canEditEffectifs;
    }



    /**
     * @param bool $canEditEffectifs
     *
     * @return Noeud
     */
    public function setCanEditEffectifs($canEditEffectifs)
    {
        $this->canEditEffectifs = $canEditEffectifs;

        return $this;
    }

}