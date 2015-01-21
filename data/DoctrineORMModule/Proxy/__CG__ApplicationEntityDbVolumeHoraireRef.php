<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class VolumeHoraireRef extends \Application\Entity\Db\VolumeHoraireRef implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'heures', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'typeVolumeHoraire', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoCreateur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'serviceReferentiel', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'validation');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'heures', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'typeVolumeHoraire', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'histoCreateur', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'serviceReferentiel', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraireRef' . "\0" . 'validation');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (VolumeHoraireRef $proxy) {
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
    public function setHeures($heures)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHeures', array($heures));

        return parent::setHeures($heures);
    }

    /**
     * {@inheritDoc}
     */
    public function getHeures()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHeures', array());

        return parent::getHeures();
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
    public function setTypeVolumeHoraire(\Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypeVolumeHoraire', array($typeVolumeHoraire));

        return parent::setTypeVolumeHoraire($typeVolumeHoraire);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeVolumeHoraire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeVolumeHoraire', array());

        return parent::getTypeVolumeHoraire();
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
    public function setServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setServiceReferentiel', array($serviceReferentiel));

        return parent::setServiceReferentiel($serviceReferentiel);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceReferentiel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getServiceReferentiel', array());

        return parent::getServiceReferentiel();
    }

    /**
     * {@inheritDoc}
     */
    public function addValidation(\Application\Entity\Db\Validation $validation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addValidation', array($validation));

        return parent::addValidation($validation);
    }

    /**
     * {@inheritDoc}
     */
    public function removeValidation(\Application\Entity\Db\Validation $validation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeValidation', array($validation));

        return parent::removeValidation($validation);
    }

    /**
     * {@inheritDoc}
     */
    public function getValidation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValidation', array());

        return parent::getValidation();
    }

}
