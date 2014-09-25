<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class StatutIntervenant extends \Application\Entity\Db\StatutIntervenant implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', 'source', 'sourceCode', 'depassement', 'fonctionEC', 'histoCreation', 'histoDestruction', 'histoModification', 'libelle', 'serviceStatutaire', 'plafondReferentiel', 'maximumHETD', 'validiteDebut', 'validiteFin', 'id', 'typeIntervenant', 'histoModificateur', 'histoDestructeur', 'histoCreateur', 'typeAgrementStatut', 'nonAutorise', 'peutSaisirService', 'peutChoisirDansDossier');
        }

        return array('__isInitialized__', 'source', 'sourceCode', 'depassement', 'fonctionEC', 'histoCreation', 'histoDestruction', 'histoModification', 'libelle', 'serviceStatutaire', 'plafondReferentiel', 'maximumHETD', 'validiteDebut', 'validiteFin', 'id', 'typeIntervenant', 'histoModificateur', 'histoDestructeur', 'histoCreateur', 'typeAgrementStatut', 'nonAutorise', 'peutSaisirService', 'peutChoisirDansDossier');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (StatutIntervenant $proxy) {
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
    public function estPermanent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estPermanent', array());

        return parent::estPermanent();
    }

    /**
     * {@inheritDoc}
     */
    public function estVacataire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estVacataire', array());

        return parent::estVacataire();
    }

    /**
     * {@inheritDoc}
     */
    public function estBiatss()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estBiatss', array());

        return parent::estBiatss();
    }

    /**
     * {@inheritDoc}
     */
    public function estAutre()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estAutre', array());

        return parent::estAutre();
    }

    /**
     * {@inheritDoc}
     */
    public function estAgentTemporaireVacataire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estAgentTemporaireVacataire', array());

        return parent::estAgentTemporaireVacataire();
    }

    /**
     * {@inheritDoc}
     */
    public function necessiteContrat()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'necessiteContrat', array());

        return parent::necessiteContrat();
    }

    /**
     * {@inheritDoc}
     */
    public function peutSaisirDossier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'peutSaisirDossier', array());

        return parent::peutSaisirDossier();
    }

    /**
     * {@inheritDoc}
     */
    public function peutSaisirPieceJointe()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'peutSaisirPieceJointe', array());

        return parent::peutSaisirPieceJointe();
    }

    /**
     * {@inheritDoc}
     */
    public function getNonAutorise()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNonAutorise', array());

        return parent::getNonAutorise();
    }

    /**
     * {@inheritDoc}
     */
    public function getPeutSaisirService()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeutSaisirService', array());

        return parent::getPeutSaisirService();
    }

    /**
     * {@inheritDoc}
     */
    public function setNonAutorise($nonAutorise)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNonAutorise', array($nonAutorise));

        return parent::setNonAutorise($nonAutorise);
    }

    /**
     * {@inheritDoc}
     */
    public function setPeutSaisirService($peutSaisirService)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeutSaisirService', array($peutSaisirService));

        return parent::setPeutSaisirService($peutSaisirService);
    }

    /**
     * {@inheritDoc}
     */
    public function getPeutChoisirDansDossier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPeutChoisirDansDossier', array());

        return parent::getPeutChoisirDansDossier();
    }

    /**
     * {@inheritDoc}
     */
    public function setPeutChoisirDansDossier($peutChoisirDansDossier)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPeutChoisirDansDossier', array($peutChoisirDansDossier));

        return parent::setPeutChoisirDansDossier($peutChoisirDansDossier);
    }

    /**
     * {@inheritDoc}
     */
    public function setDepassement($depassement)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDepassement', array($depassement));

        return parent::setDepassement($depassement);
    }

    /**
     * {@inheritDoc}
     */
    public function getDepassement()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDepassement', array());

        return parent::getDepassement();
    }

    /**
     * {@inheritDoc}
     */
    public function setFonctionEC($fonctionEC)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFonctionEC', array($fonctionEC));

        return parent::setFonctionEC($fonctionEC);
    }

    /**
     * {@inheritDoc}
     */
    public function getFonctionEC()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFonctionEC', array());

        return parent::getFonctionEC();
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
    public function setServiceStatutaire($serviceStatutaire)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setServiceStatutaire', array($serviceStatutaire));

        return parent::setServiceStatutaire($serviceStatutaire);
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceStatutaire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getServiceStatutaire', array());

        return parent::getServiceStatutaire();
    }

    /**
     * {@inheritDoc}
     */
    public function setPlafondReferentiel($plafondReferentiel)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPlafondReferentiel', array($plafondReferentiel));

        return parent::setPlafondReferentiel($plafondReferentiel);
    }

    /**
     * {@inheritDoc}
     */
    public function getPlafondReferentiel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPlafondReferentiel', array());

        return parent::getPlafondReferentiel();
    }

    /**
     * {@inheritDoc}
     */
    public function setMaximumHETD($maximumHETD)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMaximumHETD', array($maximumHETD));

        return parent::setMaximumHETD($maximumHETD);
    }

    /**
     * {@inheritDoc}
     */
    public function getMaximumHETD()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMaximumHETD', array());

        return parent::getMaximumHETD();
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
    public function setTypeIntervenant(\Application\Entity\Db\TypeIntervenant $typeIntervenant = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypeIntervenant', array($typeIntervenant));

        return parent::setTypeIntervenant($typeIntervenant);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeIntervenant()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeIntervenant', array());

        return parent::getTypeIntervenant();
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
    public function addTypeAgrementStatut(\Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTypeAgrementStatut', array($typeAgrementStatut));

        return parent::addTypeAgrementStatut($typeAgrementStatut);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTypeAgrementStatut(\Application\Entity\Db\TypeAgrementStatut $typeAgrementStatut)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTypeAgrementStatut', array($typeAgrementStatut));

        return parent::removeTypeAgrementStatut($typeAgrementStatut);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeAgrementStatut()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeAgrementStatut', array());

        return parent::getTypeAgrementStatut();
    }

}
