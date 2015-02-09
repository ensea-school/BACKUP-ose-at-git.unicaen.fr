<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Structure extends \Application\Entity\Db\Structure implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'histoCreation', 'histoDestruction', 'histoModification', 'libelleCourt', 'libelleLong', 'niveau', 'sourceCode', 'contactPj', 'validiteDebut', 'validiteFin', 'id', 'source', 'type', 'histoModificateur', 'histoDestructeur', 'etablissement', 'histoCreateur', 'parente', 'structureNiv2', 'elementPedagogique', 'service', 'centreCout');
        }

        return array('__isInitialized__', 'histoCreation', 'histoDestruction', 'histoModification', 'libelleCourt', 'libelleLong', 'niveau', 'sourceCode', 'contactPj', 'validiteDebut', 'validiteFin', 'id', 'source', 'type', 'histoModificateur', 'histoDestructeur', 'etablissement', 'histoCreateur', 'parente', 'structureNiv2', 'elementPedagogique', 'service', 'centreCout');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Structure $proxy) {
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
    public function setLibelleCourt($libelleCourt)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLibelleCourt', array($libelleCourt));

        return parent::setLibelleCourt($libelleCourt);
    }

    /**
     * {@inheritDoc}
     */
    public function getLibelleCourt()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLibelleCourt', array());

        return parent::getLibelleCourt();
    }

    /**
     * {@inheritDoc}
     */
    public function setLibelleLong($libelleLong)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLibelleLong', array($libelleLong));

        return parent::setLibelleLong($libelleLong);
    }

    /**
     * {@inheritDoc}
     */
    public function getLibelleLong()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLibelleLong', array());

        return parent::getLibelleLong();
    }

    /**
     * {@inheritDoc}
     */
    public function setNiveau($niveau)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNiveau', array($niveau));

        return parent::setNiveau($niveau);
    }

    /**
     * {@inheritDoc}
     */
    public function getNiveau()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNiveau', array());

        return parent::getNiveau();
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
    public function setContactPj($contactPj)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContactPj', array($contactPj));

        return parent::setContactPj($contactPj);
    }

    /**
     * {@inheritDoc}
     */
    public function getContactPj()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContactPj', array());

        return parent::getContactPj();
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
    public function setType(\Application\Entity\Db\TypeStructure $type = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setType', array($type));

        return parent::setType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', array());

        return parent::getType();
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
    public function setEtablissement(\Application\Entity\Db\Etablissement $etablissement = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEtablissement', array($etablissement));

        return parent::setEtablissement($etablissement);
    }

    /**
     * {@inheritDoc}
     */
    public function getEtablissement()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEtablissement', array());

        return parent::getEtablissement();
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
    public function setParente(\Application\Entity\Db\Structure $parente = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParente', array($parente));

        return parent::setParente($parente);
    }

    /**
     * {@inheritDoc}
     */
    public function getParente()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParente', array());

        return parent::getParente();
    }

    /**
     * {@inheritDoc}
     */
    public function setParenteNiv2(\Application\Entity\Db\Structure $structureNiv2 = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setParenteNiv2', array($structureNiv2));

        return parent::setParenteNiv2($structureNiv2);
    }

    /**
     * {@inheritDoc}
     */
    public function getParenteNiv2()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getParenteNiv2', array());

        return parent::getParenteNiv2();
    }

    /**
     * {@inheritDoc}
     */
    public function addElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addElementPedagogique', array($elementPedagogique));

        return parent::addElementPedagogique($elementPedagogique);
    }

    /**
     * {@inheritDoc}
     */
    public function removeElementPedagogique(\Application\Entity\Db\ElementPedagogique $elementPedagogique)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeElementPedagogique', array($elementPedagogique));

        return parent::removeElementPedagogique($elementPedagogique);
    }

    /**
     * {@inheritDoc}
     */
    public function getElementPedagogique()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getElementPedagogique', array());

        return parent::getElementPedagogique();
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
    public function addCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addCentreCout', array($centreCout));

        return parent::addCentreCout($centreCout);
    }

    /**
     * {@inheritDoc}
     */
    public function removeCentreCout(\Application\Entity\Db\CentreCout $centreCout)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeCentreCout', array($centreCout));

        return parent::removeCentreCout($centreCout);
    }

    /**
     * {@inheritDoc}
     */
    public function getCentreCout()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCentreCout', array());

        return parent::getCentreCout();
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
    public function getSourceToString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSourceToString', array());

        return parent::getSourceToString();
    }

    /**
     * {@inheritDoc}
     */
    public function estFilleDeLaStructureDeNiv2(\Application\Entity\Db\Structure $structureDeNiv2)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estFilleDeLaStructureDeNiv2', array($structureDeNiv2));

        return parent::estFilleDeLaStructureDeNiv2($structureDeNiv2);
    }

}
