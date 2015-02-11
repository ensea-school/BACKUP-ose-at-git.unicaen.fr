<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class CcActivite extends \Application\Entity\Db\CcActivite implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'code', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fa', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fc', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fcMajorees', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fi', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'libelle', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'referentiel', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoCreateur');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'code', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fa', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fc', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fcMajorees', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'fi', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'libelle', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'referentiel', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\CcActivite' . "\0" . 'histoCreateur');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (CcActivite $proxy) {
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
    public function setCode($code)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCode', array($code));

        return parent::setCode($code);
    }

    /**
     * {@inheritDoc}
     */
    public function getCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCode', array());

        return parent::getCode();
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
    public function getFa()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFa', array());

        return parent::getFa();
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
    public function getFc()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFc', array());

        return parent::getFc();
    }

    /**
     * {@inheritDoc}
     */
    public function setFcMajorees($fcMajorees)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFcMajorees', array($fcMajorees));

        return parent::setFcMajorees($fcMajorees);
    }

    /**
     * {@inheritDoc}
     */
    public function getFcMajorees()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFcMajorees', array());

        return parent::getFcMajorees();
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
    public function getFi()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFi', array());

        return parent::getFi();
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
    public function setReferentiel($referentiel)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setReferentiel', array($referentiel));

        return parent::setReferentiel($referentiel);
    }

    /**
     * {@inheritDoc}
     */
    public function getReferentiel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReferentiel', array());

        return parent::getReferentiel();
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
    public function typeHeuresMatches(\Application\Entity\Db\TypeHeures $typeHeures)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'typeHeuresMatches', array($typeHeures));

        return parent::typeHeuresMatches($typeHeures);
    }

}
