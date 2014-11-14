<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ElementPedagogique extends \Application\Entity\Db\ElementPedagogique implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'histoCreation', 'histoDestruction', 'histoModification', 'libelle', 'sourceCode', 'tauxFoad', 'tauxFi', 'tauxFc', 'tauxFa', 'fi', 'fc', 'fa', 'validiteDebut', 'validiteFin', 'id', 'cheminPedagogique', 'service', 'structure', 'periode', 'source', 'histoModificateur', 'histoCreateur', 'histoDestructeur', 'etape', 'elementModulateur', 'hasChanged', '' . "\0" . 'Application\\Entity\\Db\\ElementPedagogique' . "\0" . 'typeIntervention');
        }

        return array('__isInitialized__', 'histoCreation', 'histoDestruction', 'histoModification', 'libelle', 'sourceCode', 'tauxFoad', 'tauxFi', 'tauxFc', 'tauxFa', 'fi', 'fc', 'fa', 'validiteDebut', 'validiteFin', 'id', 'cheminPedagogique', 'service', 'structure', 'periode', 'source', 'histoModificateur', 'histoCreateur', 'histoDestructeur', 'etape', 'elementModulateur', 'hasChanged', '' . "\0" . 'Application\\Entity\\Db\\ElementPedagogique' . "\0" . 'typeIntervention');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ElementPedagogique $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', array());

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function getEtapes($principaleIncluse = true)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEtapes', array($principaleIncluse));

        return parent::getEtapes($principaleIncluse);
    }

    /**
     * {@inheritDoc}
     */
    public function getHasChanged()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHasChanged', array());

        return parent::getHasChanged();
    }

    /**
     * {@inheritDoc}
     */
    public function setHasChanged($hasChanged)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHasChanged', array($hasChanged));

        return parent::setHasChanged($hasChanged);
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoCreation($histoCreation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoCreation', array($histoCreation));

        return parent::setHistoCreation($histoCreation);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoCreation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoCreation', array());

        return parent::getHistoCreation();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoDestruction($histoDestruction)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoDestruction', array($histoDestruction));

        return parent::setHistoDestruction($histoDestruction);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoDestruction()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoDestruction', array());

        return parent::getHistoDestruction();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoModification($histoModification)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoModification', array($histoModification));

        return parent::setHistoModification($histoModification);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoModification()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoModification', array());

        return parent::getHistoModification();
    }

    /**
     * {@inheritDoc}
     */
    public function setLibelle($libelle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLibelle', array($libelle));

        return parent::setLibelle($libelle);
    }

    /**
     * {@inheritDoc}
     */
    public function getLibelle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLibelle', array());

        return parent::getLibelle();
    }

    /**
     * {@inheritDoc}
     */
    public function setSourceCode($sourceCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSourceCode', array($sourceCode));

        return parent::setSourceCode($sourceCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSourceCode', array());

        return parent::getSourceCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setTauxFoad($tauxFoad)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTauxFoad', array($tauxFoad));

        return parent::setTauxFoad($tauxFoad);
    }

    /**
     * {@inheritDoc}
     */
    public function getTauxFoad()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTauxFoad', array());

        return parent::getTauxFoad();
    }

    /**
     * {@inheritDoc}
     */
    public function getFi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFi', array());

        return parent::getFi();
    }

    /**
     * {@inheritDoc}
     */
    public function getFc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFc', array());

        return parent::getFc();
    }

    /**
     * {@inheritDoc}
     */
    public function getFa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFa', array());

        return parent::getFa();
    }

    /**
     * {@inheritDoc}
     */
    public function getTauxFi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTauxFi', array());

        return parent::getTauxFi();
    }

    /**
     * {@inheritDoc}
     */
    public function getTauxFc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTauxFc', array());

        return parent::getTauxFc();
    }

    /**
     * {@inheritDoc}
     */
    public function getTauxFa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTauxFa', array());

        return parent::getTauxFa();
    }

    /**
     * {@inheritDoc}
     */
    public function setFi($fi)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFi', array($fi));

        return parent::setFi($fi);
    }

    /**
     * {@inheritDoc}
     */
    public function setFc($fc)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFc', array($fc));

        return parent::setFc($fc);
    }

    /**
     * {@inheritDoc}
     */
    public function setFa($fa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFa', array($fa));

        return parent::setFa($fa);
    }

    /**
     * {@inheritDoc}
     */
    public function setTauxFi($tauxFi)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTauxFi', array($tauxFi));

        return parent::setTauxFi($tauxFi);
    }

    /**
     * {@inheritDoc}
     */
    public function setTauxFc($tauxFc)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTauxFc', array($tauxFc));

        return parent::setTauxFc($tauxFc);
    }

    /**
     * {@inheritDoc}
     */
    public function setTauxFa($tauxFa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTauxFa', array($tauxFa));

        return parent::setTauxFa($tauxFa);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegimesInscription($inHtml = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegimesInscription', array($inHtml));

        return parent::getRegimesInscription($inHtml);
    }

    /**
     * {@inheritDoc}
     */
    public function setValiditeDebut($validiteDebut)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValiditeDebut', array($validiteDebut));

        return parent::setValiditeDebut($validiteDebut);
    }

    /**
     * {@inheritDoc}
     */
    public function getValiditeDebut()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValiditeDebut', array());

        return parent::getValiditeDebut();
    }

    /**
     * {@inheritDoc}
     */
    public function setValiditeFin($validiteFin)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValiditeFin', array($validiteFin));

        return parent::setValiditeFin($validiteFin);
    }

    /**
     * {@inheritDoc}
     */
    public function getValiditeFin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValiditeFin', array());

        return parent::getValiditeFin();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStructure', array($structure));

        return parent::setStructure($structure);
    }

    /**
     * {@inheritDoc}
     */
    public function getStructure()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStructure', array());

        return parent::getStructure();
    }

    /**
     * {@inheritDoc}
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeriode', array($periode));

        return parent::setPeriode($periode);
    }

    /**
     * {@inheritDoc}
     */
    public function getPeriode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeriode', array());

        return parent::getPeriode();
    }

    /**
     * {@inheritDoc}
     */
    public function setSource(\Application\Entity\Db\Source $source = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSource', array($source));

        return parent::setSource($source);
    }

    /**
     * {@inheritDoc}
     */
    public function getSource()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSource', array());

        return parent::getSource();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoModificateur', array($histoModificateur));

        return parent::setHistoModificateur($histoModificateur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoModificateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoModificateur', array());

        return parent::getHistoModificateur();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoCreateur', array($histoCreateur));

        return parent::setHistoCreateur($histoCreateur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoCreateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoCreateur', array());

        return parent::getHistoCreateur();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoDestructeur', array($histoDestructeur));

        return parent::setHistoDestructeur($histoDestructeur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoDestructeur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoDestructeur', array());

        return parent::getHistoDestructeur();
    }

    /**
     * {@inheritDoc}
     */
    public function setEtape(\Application\Entity\Db\Etape $etape = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEtape', array($etape));

        return parent::setEtape($etape);
    }

    /**
     * {@inheritDoc}
     */
    public function getEtape()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEtape', array());

        return parent::getEtape();
    }

    /**
     * {@inheritDoc}
     */
    public function addCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addCheminPedagogique', array($cheminPedagogique));

        return parent::addCheminPedagogique($cheminPedagogique);
    }

    /**
     * {@inheritDoc}
     */
    public function removeCheminPedagogique(\Application\Entity\Db\CheminPedagogique $cheminPedagogique)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeCheminPedagogique', array($cheminPedagogique));

        return parent::removeCheminPedagogique($cheminPedagogique);
    }

    /**
     * {@inheritDoc}
     */
    public function getCheminPedagogique()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCheminPedagogique', array());

        return parent::getCheminPedagogique();
    }

    /**
     * {@inheritDoc}
     */
    public function addElementModulateur(\Application\Entity\Db\ElementModulateur $elementModulateur)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addElementModulateur', array($elementModulateur));

        return parent::addElementModulateur($elementModulateur);
    }

    /**
     * {@inheritDoc}
     */
    public function removeElementModulateur(\Application\Entity\Db\ElementModulateur $elementModulateur)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeElementModulateur', array($elementModulateur));

        return parent::removeElementModulateur($elementModulateur);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementModulateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElementModulateur', array());

        return parent::getElementModulateur();
    }

    /**
     * {@inheritDoc}
     */
    public function addService(\Application\Entity\Db\Service $service)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addService', array($service));

        return parent::addService($service);
    }

    /**
     * {@inheritDoc}
     */
    public function removeService(\Application\Entity\Db\Service $service)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeService', array($service));

        return parent::removeService($service);
    }

    /**
     * {@inheritDoc}
     */
    public function getService()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getService', array());

        return parent::getService();
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeIntervention()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeIntervention', array());

        return parent::getTypeIntervention();
    }

}
