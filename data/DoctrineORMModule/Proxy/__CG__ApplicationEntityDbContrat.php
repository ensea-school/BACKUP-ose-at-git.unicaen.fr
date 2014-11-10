<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Contrat extends \Application\Entity\Db\Contrat implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'typeContrat', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoCreateur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'intervenant', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'volumeHoraire', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'structure', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'validation', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'numeroAvenant', 'contrat', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'dateRetourSigne', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'fichier');
        }

        return array('__isInitialized__', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoCreation', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoDestruction', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoModification', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'typeContrat', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoModificateur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoDestructeur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'histoCreateur', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'intervenant', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'volumeHoraire', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'structure', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'validation', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'numeroAvenant', 'contrat', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'dateRetourSigne', '' . "\0" . 'Application\\Entity\\Db\\Contrat' . "\0" . 'fichier');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Contrat $proxy) {
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
    public function toString($avecArticle = false, $deLe = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'toString', array($avecArticle, $deLe));

        return parent::toString($avecArticle, $deLe);
    }

    /**
     * {@inheritDoc}
     */
    public function getReference()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getReference', array());

        return parent::getReference();
    }

    /**
     * {@inheritDoc}
     */
    public function estUnAvenant()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estUnAvenant', array());

        return parent::estUnAvenant();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroAvenant($numeroAvenant)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroAvenant', array($numeroAvenant));

        return parent::setNumeroAvenant($numeroAvenant);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroAvenant()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroAvenant', array());

        return parent::getNumeroAvenant();
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
    public function setTypeContrat(\Application\Entity\Db\TypeContrat $typeContrat = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypeContrat', array($typeContrat));

        return parent::setTypeContrat($typeContrat);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeContrat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeContrat', array());

        return parent::getTypeContrat();
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
    public function setIntervenant(\Application\Entity\Db\IntervenantExterieur $intervenant = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIntervenant', array($intervenant));

        return parent::setIntervenant($intervenant);
    }

    /**
     * {@inheritDoc}
     */
    public function getIntervenant()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIntervenant', array());

        return parent::getIntervenant();
    }

    /**
     * {@inheritDoc}
     */
    public function addVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addVolumeHoraire', array($volumeHoraire));

        return parent::addVolumeHoraire($volumeHoraire);
    }

    /**
     * {@inheritDoc}
     */
    public function removeVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeVolumeHoraire', array($volumeHoraire));

        return parent::removeVolumeHoraire($volumeHoraire);
    }

    /**
     * {@inheritDoc}
     */
    public function getVolumeHoraire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVolumeHoraire', array());

        return parent::getVolumeHoraire();
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
    public function setValidation(\Application\Entity\Db\Validation $validation = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValidation', array($validation));

        return parent::setValidation($validation);
    }

    /**
     * {@inheritDoc}
     */
    public function getValidation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValidation', array());

        return parent::getValidation();
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
    public function setDateRetourSigne($dateRetourSigne)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateRetourSigne', array($dateRetourSigne));

        return parent::setDateRetourSigne($dateRetourSigne);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateRetourSigne()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateRetourSigne', array());

        return parent::getDateRetourSigne();
    }

    /**
     * {@inheritDoc}
     */
    public function addFichier(\Application\Entity\Db\Fichier $fichier)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addFichier', array($fichier));

        return parent::addFichier($fichier);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFichier(\Application\Entity\Db\Fichier $fichier)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeFichier', array($fichier));

        return parent::removeFichier($fichier);
    }

    /**
     * {@inheritDoc}
     */
    public function getFichier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFichier', array());

        return parent::getFichier();
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResourceId', array());

        return parent::getResourceId();
    }

}
