<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class VolumeHoraire extends \Application\Entity\Db\VolumeHoraire implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'heures', 'histoCreation', 'histoDestruction', 'histoModification', 'validiteDebut', 'validiteFin', 'id', 'histoDestructeur', 'histoModificateur', 'histoCreateur', 'service', 'motifNonPaiement', 'periode', 'typeIntervention', 'typeVolumeHoraire', 'contrat', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraire' . "\0" . 'validation', 'remove');
        }

        return array('__isInitialized__', 'heures', 'histoCreation', 'histoDestruction', 'histoModification', 'validiteDebut', 'validiteFin', 'id', 'histoDestructeur', 'histoModificateur', 'histoCreateur', 'service', 'motifNonPaiement', 'periode', 'typeIntervention', 'typeVolumeHoraire', 'contrat', '' . "\0" . 'Application\\Entity\\Db\\VolumeHoraire' . "\0" . 'validation', 'remove');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (VolumeHoraire $proxy) {
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
    public function setRemove($remove)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRemove', array($remove));

        return parent::setRemove($remove);
    }

    /**
     * {@inheritDoc}
     */
    public function getRemove()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRemove', array());

        return parent::getRemove();
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
    public function setService(\Application\Entity\Db\Service $service = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setService', array($service));

        return parent::setService($service);
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
    public function setMotifNonPaiement(\Application\Entity\Db\MotifNonPaiement $motifNonPaiement = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMotifNonPaiement', array($motifNonPaiement));

        return parent::setMotifNonPaiement($motifNonPaiement);
    }

    /**
     * {@inheritDoc}
     */
    public function getMotifNonPaiement()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMotifNonPaiement', array());

        return parent::getMotifNonPaiement();
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
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypeIntervention', array($typeIntervention));

        return parent::setTypeIntervention($typeIntervention);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeIntervention()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeIntervention', array());

        return parent::getTypeIntervention();
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
    public function setContrat(\Application\Entity\Db\Contrat $contrat = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContrat', array($contrat));

        return parent::setContrat($contrat);
    }

    /**
     * {@inheritDoc}
     */
    public function getContrat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContrat', array());

        return parent::getContrat();
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
