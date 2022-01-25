<?php

namespace OSETest\Entity\Db;

use Application\Entity\Db\Civilite;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Etablissement;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\RegimeSecu;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantPermanent;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\TypeIntervenant;
use Intervenant\Entity\Db\Statut;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Service;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Etape;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\TypeAgrement;
use Application\Entity\Db\TypeAgrementStatut;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\TypeContrat;
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
     * @var Structure
     */
    private $structureRacine;

    /**
     * @var Structure
     */
    private $structureEns;

    /**
     * @var Statut[]
     */
    private $statuts;

    /**
     * @var TypeAgrement[]
     */
    private $typesAgrement;

    /**
     * @var TypeContrat[]
     */
    private $typesContrat;

    /**
     * @var TypeValidation[]
     */
    private $typesValidation;

    /**
     * @var TypeIntervention[]
     */
    private $typesIntervention;

    /**
     * @var Corps
     */
    private $corps;

    /**
     * @var RegimeSecu
     */
    private $regimeSecu;

    /**
     * @var FonctionReferentiel
     */
    private $fonction;

    /**
     * @var Etape
     */
    private $etape;

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

        //Asset::setSource($this->getSource());

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
                    $listener->setIdentity(['db' => $oseuser]);
                }
            }
        }

        $this->newEntities = new SplStack();
    }



    /**
     * @var string
     */
    protected $testClassName;



    /**
     *
     * @param string $className
     *
     * @return self
     */
    public function setTestClassName($className)
    {
        $this->testClassName = $className;

        return $this;
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
            $entity = $this->newEntities->current();
            if ($entity instanceof ElementPedagogique) {
                // On historise les EP à la main car on n'arrive pas à éviter l'erreur suivante :
                // Doctrine\DBAL\DBALException: An exception occurred while executing
                // 'DELETE FROM V_ELEMENT_TYPE_INTERVENTION WHERE ELEMENT_PEDAGOGIQUE_ID = ?' with params [17970]:
                // ORA-01752: cannot delete from view without exactly one key-preserved table
                $entity->setHistoDestruction(new \DateTime());
            } else {
                $this->getEntityManager()->remove($entity);
            }
            $this->newEntities->next();
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $this;
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
            $qb             = $this->getEntityManager()->getRepository('Application\Entity\Db\Civilite')->createQueryBuilder("c");
            $this->civilite = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->civilite) {
                throw new RuntimeException("Aucune civilité trouvée.");
            }
        }

        return $this->civilite;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de Structure persistée.
     *
     * @return Structure
     */
    public function getStructure()
    {
        return null;
    }



    /**
     * Recherche et retourne la structure racine, i.e. qui n'a aucun structure mère.
     *
     * @return Structure
     * @throws RuntimeException StructureService racine introuvable
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
     * Retourne :
     * - soit une Structure d'enseignement quelconque ;
     * - soit à chaque appel une nouvelle instance de Structure d'enseignement persistée.
     *
     * @param boolean $quelconque
     *
     * @return Structure
     */
    public function getStructureEns($quelconque = true)
    {
        if ($quelconque) {
            if (null === $this->structureEns) {
                $qb                 = $this->getEntityManager()->getRepository('Application\Entity\Db\Structure')->createQueryBuilder("s")
                    ->join("s.type", "ts")
                    ->andWhere("ts.enseignement = 1");
                $this->structureEns = $qb->getQuery()->setMaxResults(1)->getSingleResult();
                if (!$this->structureEns) {
                    throw new RuntimeException("Structure d'enseignement quelconque introuvable.");
                }
            }

            return $this->structureEns;
        }

        $structureEns = Asset::newStructure();

        $this->getEntityManager()->persist($structureEns);

        $this->newEntities->push($structureEns);

        return $structureEns;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de StatutIntervenant persistée.
     *
     * @param boolean $permanent
     *
     * @return Statut
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
     *
     * @return Statut
     */
    public function getStatutIntervenantByCode($sourceCode)
    {
        if (!$sourceCode) {
            throw new LogicException("Un code de statut intervenant est requis.");
        }

        if (!isset($this->statuts[$sourceCode])) {
            $this->statuts[$sourceCode] = $this->getEntityManager()->getRepository('Intervenant\Entity\Db\Statut')
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
     * Retourne à chaque appel une nouvelle instance de Service.
     *
     * @param Intervenant        $intervenant
     * @param Structure          $structureEns
     * @param ElementPedagogique $ep
     *
     * @return Service
     */
    public function getService(Intervenant $intervenant, Structure $structureEns = null, ElementPedagogique $ep = null)
    {
        $service = Asset::newService(
            $intervenant,
            $structureEns ?: $this->getStructureEns(),
            $ep ?: $this->getElementPedagogique(),
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
     * @param Service          $v
     * @param float            $heures
     * @param TypeIntervention $typeIntervention
     *
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
            $qb                     = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeIntervention')->createQueryBuilder("ti");
            $this->typeIntervention = $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
            if (!$this->typeIntervention) {
                throw new RuntimeException("TypeIntervention quelconque introuvable.");
            }
        }

        return $this->typeIntervention;
    }



    /**
     * Recherche et retourne le TypeIntervention correspondant au code spécifié.
     *
     * @param string $code Code du TypeIntervention, ex: TypeIntervention::CODE_PIECE_JOINTE
     *
     * @return TypeIntervention
     */
    public function getTypeInterventionByCode($code)
    {
        if (!$code) {
            throw new LogicException("Un code de TypeIntervention est requis.");
        }

        if (!isset($this->typesIntervention[$code])) {
            $this->typesIntervention[$code] = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeIntervention')
                ->findOneByCode($code);
            if (!$this->typesIntervention[$code]) {
                throw new RuntimeException("TypeIntervention introuvable avec le code '$code'.");
            }
        }

        return $this->typesIntervention[$code];
    }



    /**
     * Recherche et retourne une Periode quelconque.
     *
     * @return Periode
     */
    public function getPeriode()
    {
        if (null === $this->periode) {
            $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\Periode')->createQueryBuilder("p");
            $qb->where("p.enseignement = 1");
            $this->periode = $qb->getQuery()->setMaxResults(1)->getOneOrNullResult();
            if (!$this->periode) {
                throw new RuntimeException("Periode quelconque introuvable.");
            }
        }

        return $this->periode;
    }



    /**
     * Recherche et retourne une Etape quelconque.
     *
     * @return Etape
     */
    public function getEtape()
    {
        if (null === $this->etape) {
            $qb          = $this->getEntityManager()->getRepository('Application\Entity\Db\Etape')->createQueryBuilder("e");
            $this->etape = $qb->getQuery()->setMaxResults(1)->getSingleResult();
            if (!$this->etape) {
                throw new RuntimeException("Etape quelconque introuvable.");
            }
        }

        return $this->etape;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de ElementPedagogique persistée.
     *
     * @return ElementPedagogique
     */
    public function getElementPedagogique(Structure $structure = null)
    {
        $ep = Asset::newElementPedagogique(
            $structure ?: $this->getStructureEns(),
            $this->getEtape(),
            $this->getPeriode()
        );
        $this->getEntityManager()->persist($ep);

        $this->newEntities->push($ep);

        return $ep;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de ServiceReferentiel persistée.
     *
     * @param Intervenant $intervenant
     * @param Structure   $structure
     *
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
            $qb             = $this->getEntityManager()->getRepository('Application\Entity\Db\FonctionReferentiel')->createQueryBuilder("fr");
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
     * @param Statut          $statut
     * @param TypePieceJointe $type
     *
     * @return TypePieceJointeStatut
     */
    public function getTypePieceJointeStatut(Statut $statut, TypePieceJointe $type = null)
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
     * @param TypePieceJointe    $type
     * @param IntervenantDossier $dossier
     *
     * @return PieceJointe
     */
    public function getPieceJointe(TypePieceJointe $type, IntervenantDossier $dossier = null)
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
     * Retourne à chaque appel une nouvelle instance de TypeAgrement persistée.
     *
     * @return TypeAgrement
     */
    public function getTypeAgrement()
    {
        $type = Asset::newTypeAgrement();

        $this->getEntityManager()->persist($type);

        $this->newEntities->push($type);

        return $type;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de TypeAgrementStatut persistée.
     *
     * @param Statut       $statut
     * @param TypeAgrement $type
     *
     * @return TypeAgrementStatut
     */
    public function getTypeAgrementStatut(Statut $statut, TypeAgrement $type = null)
    {
        $tas = Asset::newTypeAgrementStatut(
            $statut,
            $type ?: $this->getTypeAgrement());

        $this->getEntityManager()->persist($tas);

        $this->newEntities->push($tas);

        return $tas;
    }



    /**
     * Retourne à chaque appel une nouvelle instance d'AgrementService persistée.
     *
     * @param TypeAgrement $type
     * @param Intervenant  $intervenant
     * @param Structure    $structure
     *
     * @return Agrement
     */
    public function getAgrement(TypeAgrement $type, Intervenant $intervenant, Structure $structure = null)
    {
        $a = Asset::newAgrement(
            $type,
            $intervenant,
            $structure ?: $intervenant->getStructure(),
            $this->getAnnee());

        $this->getEntityManager()->persist($a);

        $this->newEntities->push($a);

        return $a;
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
     *
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



    /**
     * Recherche et retourne le TypeAgrement correspondant au code spécifié.
     *
     * @param string $sourceCode Code du TypeAgrement, ex: TypeAgrement::CODE_CONSEIL_RESTREINT
     *
     * @return TypeAgrement
     */
    public function getTypeAgrementByCode($sourceCode)
    {
        if (!$sourceCode) {
            throw new LogicException("Un code de TypeAgrement est requis.");
        }

        if (!isset($this->typesAgrement[$sourceCode])) {
            $this->typesAgrement[$sourceCode] = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeAgrement')
                ->findOneByCode($sourceCode);
            if (!$this->typesAgrement[$sourceCode]) {
                throw new RuntimeException("TypeAgrement introuvable avec le code '$sourceCode'.");
            }
        }

        return $this->typesAgrement[$sourceCode];
    }



    /**
     * Retourne à chaque appel une nouvelle instance de Contrat persistée.
     *
     * @param TypeContrat $type
     * @param Intervenant $intervenant
     * @param Structure   $structure
     *
     * @return Contrat
     */
    public function getContrat(TypeContrat $type, Intervenant $intervenant, Structure $structure = null)
    {
        $a = Asset::newContrat(
            $type,
            $intervenant,
            $structure ?: $intervenant->getStructure());

        $this->getEntityManager()->persist($a);

        $this->newEntities->push($a);

        return $a;
    }



    /**
     * Retourne à chaque appel une nouvelle instance de TypeContrat persistée.
     *
     * @param boolean $avenant
     *
     * @return TypeContrat
     */
    public function getTypeContrat($avenant = false)
    {
        $code = $avenant ? TypeContrat::CODE_AVENANT : TypeContrat::CODE_CONTRAT;

        if (!isset($this->typesContrat[$code])) {
            $this->typesContrat[$code] = $this->getEntityManager()->getRepository('Application\Entity\Db\TypeContrat')
                ->findOneByCode($code);
            if (!$this->typesContrat[$code]) {
                throw new RuntimeException("TypeContrat introuvable avec le code '$code'.");
            }
        }

        return $this->typesContrat[$code];
    }
}