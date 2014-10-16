<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Source;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\StatutIntervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeStructure;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Annee;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Common\ORM\Event\Listeners\HistoriqueListener;
use Doctrine\ORM\EntityManager;
use RuntimeException;
use LogicException;
use SplStack;

/**
 * Description of EntityProvider
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class EntityProvider
{
    use \UnicaenApp\Service\EntityManagerAwareTrait;
    
    /**
     * @var array
     */
    private $newEntities;
    
    /**
     * @var Source
     */
    private $source;
    
    /**
     * @var Annee
     */
    private $annee;
    
    /**
     * @var Etablissement
     */
    private $etablissement;
    
    /**
     * @var Civilite
     */
    private $civilite;
    
    /**
     * @var TypeStructure
     */
    private $typeStructure;
    
    /**
     * @var Structure
     */
    private $structureRacine;
    
    /**
     * @var Structure
     */
    private $structureEns;
    
    /**
     * @var StatutIntervenant[]
     */
    private $statuts;
    
    /**
     * @var TypeValidation[]
     */
    private $typesValidation;
    
    /**
     * @var Corps
     */
    private $corps;
    
    /**
     * @var RegimeSecu
     */
    private $regimeSecu;
    
    /**
     * @var ElementPedagogique
     */
    private $ep;
    
    /**
     * @var FonctionReferentiel
     */
    private $fonction;
    
    /**
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;
    
    /**
     * @var TypeIntervenant[]
     */
    private $typesIntervenant;
    
    /**
     * @var TypeIntervention
     */
    private $typeIntervention;
    
    /**
     * @var Periode
     */
    private $periode;

    /**
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->setEntityManager($entityManager);
        
        Asset::setSource($this->getSource());

        $this->getEntityManager()->getFilters()->enable('historique');
        
        // recherche du pseudo-utilisateur OSE
        if (!($param = $this->getEntityManager()->getRepository("Application\Entity\Db\Parametre")->findOneByNom($nom = 'oseuser'))) {
            throw new RuntimeException("Paramètre '$nom' introuvable.");
        }
        if (!($oseuser = $this->getEntityManager()->find("Application\Entity\Db\Utilisateur", $id = $param->getValeur()))) {
            throw new RuntimeException("Utilisateur OSE (id = $id) introuvable.");
        }
        // recherche du listener de gestion de l'historique pour lui transmettre le pseudo-utilisateur OSE
        foreach ($this->getEntityManager()->getEventManager()->getListeners() as $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof HistoriqueListener) {
                    $listener->setIdentity(array('db' => $oseuser));
                }
            }
        }
        
        $this->newEntities = new SplStack();
    }
    
    /**
     * SUpprime du gestionnaire d'entité les éventuelles nouvelles instances d'entités créées.
     * 
     * @return self
     */
    public function removeNewEntities($flush = true)
    {
        $this->newEntities->rewind();
        
        while ($this->newEntities->valid()) {
            $this->getEntityManager()->remove($this->newEntities->current());
            $this->newEntities->next();
        }
        
        if ($flush) {
            $this->getEntityManager()->flush();
        }
        
        return $this;
    }
    
    /**
     * Retourne à chaque appel une nouvelle instance d'IntervenantPermanent persistée.
     * 
     * @param StatutIntervenant $statut
     * @return IntervenantPermanent
     */
    public function getIntervenantPermanent(StatutIntervenant $statut = null)
    {
        $i = Asset::newIntervenantPermanent(
                $this->getCivilite(), 
                $statut ?: $this->getStatutIntervenantByCode(StatutIntervenant::ENS_CH), 
                $this->getStructure(), 
                $this->getCorps());
        
        $this->getEntityManager()->persist($i);
        
        $this->newEntities->push($i);
        
        return $i;
    }
    
    /**
     * Retourne à chaque appel une nouvelle instance d'IntervenantExterieur persistée.
     * 
     * @param StatutIntervenant $statut
     * @return IntervenantExterieur
     */
    public function getIntervenantExterieur(StatutIntervenant $statut = null)
    {
        $i = Asset::newIntervenantExterieur(
                $this->getCivilite(), 
                $statut ?: $this->getStatutIntervenantByCode(StatutIntervenant::SALAR_PRIVE), 
                $this->getStructure(), 
                $this->getRegimeSecu());
        
        $this->getEntityManager()->persist($i);
        
        $this->newEntities->push($i);
        
        return $i;
    }
    
    /** 
     * Recherche et retourne la source de test.
     * 
     * @return Source
     */
    public function getSource()
    {
        if (null === $this->source) {
            $this->source = $this->getEntityManager()->getRepository('Application\Entity\Db\Source')
                    ->findOneBy(array('libelle' => "Test"));
            if (!$this->source) {
                throw new RuntimeException("Source de test (libelle = Test) introuvable.");
            }
        }
        
        return $this->source;
    }
    
    /** 
     * Recherche et retourne l'année en cours.
     * 
     * @return Annee
     */
    public function getAnnee()
    {
        if (null === $this->annee) {
            if (!($param = $this->getEntityManager()->getRepository("Application\Entity\Db\Parametre")->findOneByNom($nom = 'annee'))) {
                throw new RuntimeException("Paramètre '$nom' introuvable.");
            }
            $this->annee = $this->getEntityManager()->find('Application\Entity\Db\Annee', $id = $param->getValeur());
            if (!$this->annee) {
                throw new RuntimeException("Année $id introuvable.");
            }
        }
        
        return $this->annee;
    }

    /**
     * Recherche et retourne l'Etablissement par défaut.
     * 
     * @return Etablissement
     */
    public function getEtablissement()
    {
        if (null === $this->etablissement) {
            if (!($param = $this->getEntityManager()->getRepository("Application\Entity\Db\Parametre")->findOneByNom($nom = 'etablissement'))) {
                throw new RuntimeException("Paramètre '$nom' introuvable.");
            }
            $this->etablissement = $this->getEntityManager()->find('Application\Entity\Db\Etablissement', $id = $param->getValeur());
            if (!$this->etablissement) {
                throw new RuntimeException("Etablissement $id introuvable.");
            }
        }
        
        return $this->etablissement;
    }

    /** 
     * Recherche et retourne la première Civilite trouvée.
     * 
     * @return Civilite
     */
    public function getCivilite()
    {
        if (null === $this->civilite) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Civilite')->createQueryBuilder("c");
            $this->civilite = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->civilite) {
                throw new RuntimeException("Aucune civilité trouvée.");
            }
        }
        
        return $this->civilite;
    }

    /** 
     * Recherche et retourne le premier TypeStructure trouvé.
     * 
     * @return TypeStructure
     */
    public function getTypeStructure()
    {
        if (null === $this->typeStructure) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeStructure')->createQueryBuilder("ts");
            $this->typeStructure = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->typeStructure) {
                throw new RuntimeException("Aucun type de structure trouvé.");
            }
        }
        
        return $this->typeStructure;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de Structure persistée.
     * 
     * @return Structure
     */
    public function getStructure()
    {
        $structure = Asset::newStructure($this->getTypeStructure(), $this->getEtablissement(), $this->getStructureRacine());
        
        $this->getEntityManager()->persist($structure);

        $this->newEntities->push($structure);
        
        return $structure;
    }

    /**
     * Recherche et retourne la structure racine, i.e. qui n'a aucun structure mère.
     * 
     * @return Structure
     * @throws RuntimeException Structure racine introuvable
     */
    public function getStructureRacine()
    {
        if (null !== $this->structureRacine) {
            return $this->structureRacine;
        }
        
        $this->structureRacine = $this->getEntityManager()->getRepository("Application\Entity\Db\Structure")->findOneByParente(null);
        if (!$this->structureRacine) {
            throw new RuntimeException("Structure racine introuvable.");
        }
        
        return $this->structureRacine;
    }
    
    /**
     * Recherche et retourne une Structure d'enseignement quelconque.
     * 
     * @return Structure
     */
    public function getStructureEns()
    {
        if (null === $this->structureEns) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Structure')->createQueryBuilder("s")
                    ->join("s.type", "ts")
                    ->andWhere("ts.enseignement = 1");
            $this->structureEns = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->structureEns) {
                throw new RuntimeException("Structure d'enseignement quelconque introuvable.");
            }
        }
        
        return $this->structureEns;
    }

    /** 
     * Recherche et retourne le TypeIntervenant permanent ou extérieur.
     * 
     * @param boolean $permanent
     * @return TypeIntervenant
     */
    public function getTypeIntervenant($permanent = true)
    {
        $code = $permanent ? TypeIntervenant::CODE_PERMANENT : TypeIntervenant::CODE_EXTERIEUR;
            
        if (null === $this->typesIntervenant[$code]) {
            $type = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeIntervenant')->findOneByCode($code);
            if (!$type) {
                throw new RuntimeException(sprintf("Type d'intervenant '%s' introuvable.", $code));
            }
            $this->typesIntervenant[$code] = $type;
        }
        
        return $this->typesIntervenant[$code];
    }

    /** 
     * Retourne à chaque appel une nouvelle instance de StatutIntervenant persistée.
     * 
     * @param boolean $permanent
     * @return StatutIntervenant
     */
    public function getStatutIntervenant($permanent = true)
    {
        $typeIntervenant = $this->getTypeIntervenant($permanent);
        $statut          = Asset::newStatutIntervenant($typeIntervenant);
        
        $this->getEntityManager()->persist($statut);

        $this->newEntities->push($statut);

        return $statut;
    }

    /** 
     * Recherche et retourne le StatutIntervenant correspondant au code spécifié.
     * 
     * @param string $sourceCode Code "source" du statut, ex: StatutIntervenant::SALAR_PRIVE
     * @return StatutIntervenant
     */
    public function getStatutIntervenantByCode($sourceCode)
    {
        if (!$sourceCode) {
            throw new LogicException("Un code de statut intervenant est requis.");
        }
        
        if (!isset($this->statuts[$sourceCode])) {
            $this->statuts[$sourceCode] = $this->getEntityManager()->getRepository('Application\Entity\Db\StatutIntervenant')
                    ->findOneBySourceCode($sourceCode);
            if (!$this->statuts[$sourceCode]) {
                throw new RuntimeException("Statut intervenant introuvable avec le code '$sourceCode'.");
            }
        }
        
        return $this->statuts[$sourceCode];
    }

    /**
     * Retourne une nouvelle instance UNIQUE de Corps.
     * 
     * @return Corps
     */
    public function getCorps()
    {
        if (null === $this->corps) {
            $this->corps = Asset::newCorps();
            $this->getEntityManager()->persist($this->corps);
        
            $this->newEntities->push($this->corps);
        }
        
        return $this->corps;
    }

    /**
     * Retourne une nouvelle instance UNIQUE de RegimeSecu.
     * 
     * @return RegimeSecu
     */
    public function getRegimeSecu()
    {
        if (null === $this->regimeSecu) {
            $this->regimeSecu = Asset::newRegimeSecu();
            $this->getEntityManager()->persist($this->regimeSecu);
        
            $this->newEntities->push($this->regimeSecu);
        }
        
        return $this->regimeSecu;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de Dossier persistée.
     * 
     * @return Dossier
     */
    public function getDossier()
    {
        $dossier = Asset::newDossier(
                $this->getCivilite(),
                true,
                false,
                $this->getStatutIntervenantByCode(StatutIntervenant::SALAR_PRIVE)
        );
        
        $this->getEntityManager()->persist($dossier);

        $this->newEntities->push($dossier);
        
        return $dossier;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de Service.
     * 
     * @param Intervenant $intervenant
     * @param Structure $structureEns
     * @return Service
     */
    public function getService(Intervenant $intervenant, Structure $structureEns = null)
    {
        $service = Asset::newService(
            $intervenant,
            $structureEns ?: $this->getStructureEns(),
            $this->getElementPedagogique(),
            $this->getTypeVolumeHoraire(),
            $this->getAnnee()
        );
        $this->getEntityManager()->persist($service);

        $this->newEntities->push($service);
        
        return $service;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de VolumeHoraire.
     * 
     * @param Service $v
     * @param float $heures
     * @param TypeIntervention $typeIntervention
     * @return VolumeHoraire
     */
    public function getVolumeHoraire(Service $v, $heures, TypeIntervention $typeIntervention = null, Periode $periode = null)
    {
        $vh = Asset::newVolumeHoraire(
                $v, 
                $typeIntervention ?: $this->getTypeIntervention(), 
                $periode ?: $this->getPeriode(),  
                $heures);
        $this->getEntityManager()->persist($vh);

        $this->newEntities->push($vh);
        
        return $vh;
    }

    /**
     * Recherche et retourne un TypeIntervention quelconque.
     * 
     * @return TypeIntervention
     */
    public function getTypeIntervention()
    {
        if (null === $this->typeIntervention) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeIntervention')->createQueryBuilder("ti");
            $this->typeIntervention = $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
            if (!$this->typeIntervention) {
                throw new RuntimeException("TypeIntervention quelconque introuvable.");
            }
        }
        
        return $this->typeIntervention;
    }

    /**
     * Recherche et retourne un Periode quelconque.
     * 
     * @return Periode
     */
    public function getPeriode()
    {
        if (null === $this->periode) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Periode')->createQueryBuilder("p");
            $this->periode = $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
            if (!$this->periode) {
                throw new RuntimeException("Periode quelconque introuvable.");
            }
        }
        
        return $this->periode;
    }

    /**
     * Recherche et retourne un ElementPedagogique quelconque.
     * 
     * @return ElementPedagogique
     */
    public function getElementPedagogique()
    {
        if (null === $this->ep) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\ElementPedagogique')->createQueryBuilder("ep");
            $this->ep = $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
            if (!$this->ep) {
                throw new RuntimeException("Elément pédagogique quelconque introuvable.");
            }
        }
        
        return $this->ep;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de ServiceReferentiel.
     * 
     * @param Intervenant $intervenant
     * @param Structure $structure
     * @return ServiceReferentiel
     */
    public function getServiceReferentiel(Intervenant $intervenant, Structure $structure = null)
    {
        $service = Asset::newServiceReferentiel(
            $intervenant,
            $this->getFonctionReferentiel(),
            $structure ?: $this->getStructureEns(),
            $this->getAnnee()
        );
        $this->getEntityManager()->persist($service);

        $this->newEntities->push($service);
        
        return $service;
    }

    /**
     * Recherche et retourne une FonctionReferentiel quelconque.
     * 
     * @return FonctionReferentiel
     */
    public function getFonctionReferentiel()
    {
        if (null === $this->fonction) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\FonctionReferentiel')->createQueryBuilder("fr");
            $this->fonction = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->fonction) {
                throw new RuntimeException("Fonction Referentiel quelconque introuvable.");
            }
        }
        
        return $this->fonction;
    }

    /** 
     * Recherche et retourne le TypeVolumeHoraire "Prévu".
     * 
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        if (null === $this->typeVolumeHoraire) {
            $this->typeVolumeHoraire = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeVolumeHoraire')
                    ->findOneByCode($code = TypeVolumeHoraire::CODE_PREVU);
            if (!$this->typeVolumeHoraire) {
                throw new RuntimeException(sprintf("Type de volume horaire '%s' introuvable.", $code));
            }
        }
        
        return $this->typeVolumeHoraire;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de TypePieceJointe persistée.
     * 
     * @return TypePieceJointe
     */
    public function getTypePieceJointe()
    {
        $type = Asset::newTypePieceJointe();
        
        $this->getEntityManager()->persist($type);

        $this->newEntities->push($type);
        
        return $type;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de TypePieceJointeStatut persistée.
     * 
     * @param StatutIntervenant $statut
     * @param TypePieceJointe $type
     * @return TypePieceJointeStatut
     */
    public function getTypePieceJointeStatut(StatutIntervenant $statut, TypePieceJointe $type = null)
    {
        $tpjs = Asset::newTypePieceJointeStatut(
                $statut,
                $type ?: $this->getTypePieceJointe());
        
        $this->getEntityManager()->persist($tpjs);

        $this->newEntities->push($tpjs);
        
        return $tpjs;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de PieceJointe persistée.
     * 
     * @param TypePieceJointe $type
     * @param Dossier $dossier
     * @return PieceJointe
     */
    public function getPieceJointe(TypePieceJointe $type, Dossier $dossier = null)
    {
        $pj = Asset::newPieceJointe($type, $dossier);
        
        $this->getEntityManager()->persist($pj);

        $this->newEntities->push($pj);
        
        return $pj;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de Fichier persistée.
     * 
     * @return Fichier
     */
    public function getFichier()
    {
        $fichier = Asset::newFichier();
        
        $this->getEntityManager()->persist($fichier);

        $this->newEntities->push($fichier);
        
        return $fichier;
    }

    /**
     * Retourne à chaque appel une nouvelle instance de Validation persistée.
     * 
     * @return Validation
     */
    public function getValidation(TypeValidation $type, Intervenant $intervenant)
    {
        $validation = Asset::newValidation($type, $intervenant);
        
        $this->getEntityManager()->persist($validation);

        $this->newEntities->push($validation);
        
        return $validation;
    }

    /** 
     * Recherche et retourne le TypeValidation correspondant au code spécifié.
     * 
     * @param string $sourceCode Code du TypeValidation, ex: TypeValidation::CODE_PIECE_JOINTE
     * @return TypeValidation
     */
    public function getTypeValidationByCode($sourceCode)
    {
        if (!$sourceCode) {
            throw new LogicException("Un code de TypeValidation est requis.");
        }
        
        if (!isset($this->typesValidation[$sourceCode])) {
            $this->typesValidation[$sourceCode] = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeValidation')
                    ->findOneByCode($sourceCode);
            if (!$this->typesValidation[$sourceCode]) {
                throw new RuntimeException("TypeValidation introuvable avec le code '$sourceCode'.");
            }
        }
        
        return $this->typesValidation[$sourceCode];
    }
}