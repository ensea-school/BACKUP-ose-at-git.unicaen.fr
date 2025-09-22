<?php
declare(strict_types=1);

namespace Contrat\Tbl\Process;

use Administration\Entity\Db\Parametre;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Administration\Service\ParametresServiceAwareTrait;
use Contrat\Entity\Db\TypeContrat;
use Contrat\Service\TypeContratServiceAwareTrait;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use DateTime;
use Exception;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Service\Entity\Db\TypeService;
use Service\Service\TypeServiceServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Event;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

class ContratProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;
    use AnneeServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use TauxRemuServiceAwareTrait;
    use TypeContratServiceAwareTrait;
    use TypeServiceServiceAwareTrait;

    private string $parametreAvenant;

    private string $parametreEns;

    private string $parametreMis;

    private string $parametreFranchissement;

    private int $parametreTauxRemuId;

    private float $parametreTauxCongesPayes = 0.1;

    /** @var array|Contrat[][] */
    private array $intervenants = [];

    private array $tblData = [];



    /**
     * @throws Exception
     */
    public function run(TableauBord $tableauBord, array $params = []): void
    {
        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives(true);
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init();
            $tableauBord->onAction(Event::GET);
            $this->loadContrats($params);
            $this->loadVolumesHoraires($params);
            $count = count($this->intervenants);
            $index = 0;
            $tableauBord->onAction(Event::PROCESS, 0, $count);
            foreach ($this->intervenants as $contrats) {
                $index++;
                $tableauBord->onAction(Event::PROGRESS, $index, $count);
                $this->traitement($contrats);
            }
            $this->exporter();
            $tableauBord->onAction(Event::SET, 0, $count);
            $this->enregistrement($tableauBord, $params);
        }
    }



    public function init(): void
    {
        $parametres = $this->getServiceParametres();

        $this->parametreAvenant         = $parametres->get(Parametre::AVENANT);
        $this->parametreEns             = $parametres->get(Parametre::CONTRAT_ENS);
        $this->parametreMis             = $parametres->get(Parametre::CONTRAT_MIS);
        $this->parametreFranchissement  = $parametres->get(Parametre::CONTRAT_REGLE_FRANCHISSEMENT);
        $this->parametreTauxRemuId      = (int)$parametres->get(Parametre::TAUX_REMU);
        $this->parametreTauxCongesPayes = (float)$parametres->get(Parametre::TAUX_CONGES_PAYES);

        $this->intervenants = [];
    }



    /**
     * @throws Exception
     */
    protected function loadContrats(array $params): void
    {
        $sql    = $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT_CONTRAT');
        $sql    = $this->getServiceBdd()->injectKey($sql, $params);
        $parser = $this->getBdd()->selectEach($sql);
        while ($data = $parser->next()) {
            $data = array_change_key_case($data); // provisoire => migration postgres
            $this->loadContrat($data);
        }
    }



    /**
     * @throws Exception
     */
    public function loadContrat(array $data): Contrat
    {
        $intervenantId = (int)$data['intervenant_id'];
        $contratId     = (int)$data['contrat_id'];
        $uuid          = $this->generateUUID($intervenantId, $contratId);
        $contrat       = $this->getContrat($intervenantId, $uuid);
        $this->contratHydrateFromDb($contrat, $data);

        return $contrat;
    }



    /**
     * @throws Exception
     */
    public function contratHydrateFromDb(Contrat $contrat, array $data): void
    {
        $annee                  = $this->getServiceAnnee()->get((int)$data['annee_id']);
        $contrat->actif         = $data['actif'] === '1';
        $contrat->historise     = !empty($data['histo_destruction']);
        $contrat->id            = (int)$data['contrat_id'] ?: null;
        $contrat->intervenantId = (int)$data['intervenant_id'];
        $contrat->validationId  = (int)$data['validation_id'] ?: null;
        $contrat->annee         = $annee;
        $contrat->structureId   = (int)$data['structure_id'] ?: null;
        $parentId               = (int)$data['parent_id'] ?: null;
        if ($parentId) {
            $uuid = $this->generateUUID($contrat->intervenantId, $parentId);
            $contrat->setParent($this->getContrat($contrat->intervenantId, $uuid));
        }
        $contrat->numeroAvenant = (int)$data['numero_avenant'];
        $contrat->debutValidite = $data['debut_validite'] ? new DateTime($data['debut_validite']) : null;
        $contrat->finValidite   = $data['fin_validite'] ? new DateTime($data['fin_validite']) : null;
        $contrat->histoCreation = $data['histo_creation'] ? new DateTime($data['histo_creation']) : null;
        $contrat->edite         = $data['edite'] === '1';
        $contrat->envoye        = $data['envoye'] === '1';
        $contrat->retourne      = $data['retourne'] === '1';
        $contrat->signe         = $data['signe'] === '1';
    }



    public function contratHydrateFromVolumeHoraire(Contrat $contrat, VolumeHoraire $volumeHoraire): void
    {
        if ($volumeHoraire->volumeHoraireId) {
            $contrat->typeService = $this->getServiceTypeService()->getEnseignement();
            $contrat->isMission   = false;
        } elseif ($volumeHoraire->volumeHoraireRefId) {
            $contrat->typeService = $this->getServiceTypeService()->getReferentiel();
            $contrat->isMission   = false;
        } elseif ($volumeHoraire->volumeHoraireMissionId) {
            $contrat->typeService = $this->getServiceTypeService()->getMission();
            $contrat->isMission   = true;
        }

        // calcul de la structure
        if ($contrat->isMission) {
            switch ($this->parametreMis) {
                case Parametre::CONTRAT_MIS_GLOBAL:
                    $contrat->structureId = null;
                    break;
                case Parametre::CONTRAT_MIS_COMPOSANTE:
                case Parametre::CONTRAT_MIS_MISSION:
                    $contrat->structureId = $volumeHoraire->structureId;
            }
        } else {
            switch ($this->parametreEns) {
                case Parametre::CONTRAT_ENS_GLOBAL:
                    $contrat->structureId = null;
                    break;
                case Parametre::CONTRAT_ENS_COMPOSANTE:
                    $contrat->structureId = $volumeHoraire->structureId;
            }
        }
    }



    private function addContrat(Contrat $contrat): void
    {
        if (!$contrat->intervenantId) {
            throw new \Exception("Impossible d'ajouter un contrat si l'intervenant n'est pas défini");
        }

        if (!$contrat->uuid) {
            throw new \Exception("Impossible d'ajouter un contrat si l'uuid n'est pas défini");
        }

        if (!array_key_exists($contrat->intervenantId, $this->intervenants)) {
            $this->intervenants[$contrat->intervenantId] = [];
        }

        if (!array_key_exists($contrat->uuid, $this->intervenants[$contrat->intervenantId])) {
            $this->intervenants[$contrat->intervenantId][$contrat->uuid] = $contrat;
        }
    }



    private function getContrat(int $intervenantId, string $uuid): Contrat
    {
        if (!array_key_exists($intervenantId, $this->intervenants)) {
            $this->intervenants[$intervenantId] = [];
        }

        if (!array_key_exists($uuid, $this->intervenants[$intervenantId])) {
            $contrat                = new Contrat($uuid);
            $contrat->intervenantId = $intervenantId;
            $this->addContrat($contrat);
        }
        return $this->intervenants[$intervenantId][$uuid];
    }



    protected function loadVolumesHoraires(array $params): void
    {
        $sql    = $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT_VOLUME_HORAIRE');
        $sql    = $this->getServiceBdd()->injectKey($sql, $params);
        $parser = $this->getBdd()->selectEach($sql);
        while ($data = $parser->next()) {
            mpg_lower($data);
            $this->loadVolumeHoraire($data);
        }
    }



    public function loadVolumeHoraire(array $data): VolumeHoraire
    {
        $intervenantId = (int)$data['intervenant_id'];
        $contratId     = (int)$data['contrat_id'] ?: null;

        $volumeHoraire = new VolumeHoraire();
        $this->volumeHoraireHydrateFromDb($volumeHoraire, $data);
        $uuid = $this->generateUUID($intervenantId, $contratId, $volumeHoraire->structureId, $volumeHoraire->missionId);

        $contrat = $this->getContrat($intervenantId, $uuid);

        if (!$contrat->id) {
            if (empty($contrat->annee)) {
                $contrat->annee = $this->getServiceAnnee()->get((int)$data['annee_id']);
            }
            $this->contratHydrateFromVolumeHoraire($contrat, $volumeHoraire);
        }

        $volumeHoraire->setContrat($contrat);

        return $volumeHoraire;
    }



    public function volumeHoraireHydrateFromDb(VolumeHoraire $vh, array $data): void
    {
        $vh->structureId            = (int)$data['structure_id'];
        $vh->serviceId              = (int)$data['service_id'] ?: null;
        $vh->serviceReferentielId   = (int)$data['service_referentiel_id'] ?: null;
        $vh->missionId              = (int)$data['mission_id'] ?: null;
        $vh->volumeHoraireId        = (int)$data['volume_horaire_id'] ?: null;
        $vh->volumeHoraireRefId     = (int)$data['volume_horaire_ref_id'] ?: null;
        $vh->volumeHoraireMissionId = (int)$data['volume_horaire_mission_id'] ?: null;
        $vh->tauxRemuId             = (int)$data['taux_remu_id'] ?: null;
        $vh->tauxRemuMajoreId       = (int)$data['taux_remu_majore_id'] ?: null;
        $vh->dateFinMission         = $data['date_fin_mission'] ? new DateTime($data['date_fin_mission']) : null;
        $vh->dateDebutMission       = $data['date_debut_mission'] ? new DateTime($data['date_debut_mission']) : null;
        $vh->cm                     = (float)$data['cm'];
        $vh->td                     = (float)$data['td'];
        $vh->tp                     = (float)$data['tp'];
        $vh->autres                 = (float)$data['autres'];
        $vh->heures                 = (float)$data['heures'];
        $vh->hetd                   = (float)$data['hetd'];
        $vh->autreLibelle           = $data['autre_libelle'];
        $vh->missionLibelle         = $data['mission_libelle'];
        $vh->typeMissionLibelle     = $data['type_mission_libelle'];
        $vh->heuresFormation        = (float)$data['heures_formation'];
    }



    public function traitement(array $contrats): void
    {
        foreach ($contrats as $contrat) {
            $this->calculTypeService($contrat);
            $this->calculStructure($contrat);
        }
        $this->calculParentsIds($contrats);

        $this->gestionContratsSansHeures($contrats);


        $this->calculDateContrat($contrats);

        // ajout d'avenants vides pour les missions avec des prolongations de dates
        $this->contratProlongationMission($contrats);

        $this->calculNumerosAvenants($contrats);

        /* Double foreach pour calcul structure, déterminer parent_id d'abord, puis le reste après ? */
        foreach ($contrats as $contrat) {
            $this->calculTauxRemu($contrat);
            $this->calculTotalHETD($contrat);
            $this->calculIsProlongation($contrat);
            $this->calculTotalHeures($contrat);
            $this->calculTermine($contrat);
            $this->calculTauxCongesPayes($contrat);
            $this->calculLibelles($contrat);
            $this->calculActif($contrat);
        }
    }



    public function calculActif(Contrat $contrat): void
    {
        if ($contrat->id) {
            $contrat->actif = true;
        }
        if (!$contrat->actif) {
            if ($this->parametreAvenant == Parametre::AVENANT_AUTORISE) {
                $contrat->actif = true;
            } elseif (empty($contrat->parent)) {
                $contrat->actif = true;
            }
        }
    }



    public function contratProlongationMission(array &$contrats): void
    {

        $contratPrincipaux = [];
        // On ne récupère que les contrats pour les traiter eux et leurs avenants ensemble plus tard
        foreach ($contrats as $contrat) {
            /* @var Contrat $contrat */
            if (empty($contrat->parent) && $contrat->isMission && $contrat->id != null) {
                $contratPrincipaux[] = $contrat;
            }
        }

        // On cherche les dates de missions les plus avancés et la date de contrat la plus avancé
        foreach ($contratPrincipaux as $contrat) {
            $dateDebutContrat = $contrat->debutValidite;
            $dateMissions     = [];
            $dateFinContrat   = $contrat->finValidite;
            $intervenantId    = $contrat->intervenantId;

            [$dateMissions, $dateFinContrat, $dateDebutContrat] = $this->calculDateContratEdite($contrat, $dateMissions, $dateFinContrat, $dateDebutContrat);
            if (!empty($dateMissions)) {
                foreach ($dateMissions as $key => $dateMission) {
                    if ($dateMission <= $dateFinContrat) {
                        unset($dateMissions[$key]);
                    }
                }
            }
            // Pour les missions qui dépassent on cherche si un avenant non édité existe avec des heures pour ces missions pour modifier la date de fin
            $dateMissions = $this->changementDateAvenantNonEdite($dateMissions, $contrat);
            // Si aucun avenant n'existe on crée un avenant sur la date la plus éloignée pour une prolongation de tous ceux qui n'ont pas d'avenant
            if (!empty($dateMissions)) {
                $avenantProlongation                  = $this->creationAvenantProlongation($contrat, $dateMissions, $dateDebutContrat, $intervenantId);
                $contrats[$avenantProlongation->uuid] = $avenantProlongation;
            }
        }
    }



    public function calculTypeService(Contrat $contrat): void
    {
        $hasMissions = false;
        $hasEnsRef   = false;

        foreach ($contrat->volumesHoraires as $vh) {
            if ($vh->missionId) {
                $hasMissions = true;
            }
            if ($vh->serviceId || $vh->serviceReferentielId) {
                $hasEnsRef = true;
            }
        }

        if ($hasMissions && $hasEnsRef) {
            throw new Exception('Un même contrat ne peut pas mélanger des heures de missions avec des heures d\'enseignement et/ou de référentiel');
        }

        if (!$hasMissions && !$hasEnsRef) {
            // aucun volume horaire
            if ($contrat->parent) {
                // s'il a un parent, c'est qu'on est sur un avenant de modif de date de fin de mission
                //On calcul le type du parent au cas ou il ne serait pas encore calculé
                $this->calculTypeService($contrat->parent);
                $contrat->isMission = $contrat->parent->isMission;
            } else {
                // S'il n'a pas de parent, c'est qu'on est sur un contrat d'enseignement/référentiel sans prévisionnel
                $contrat->isMission = false;
            }
        } else {
            // C'est forcément l'un des 2 selon les volumes horaires...
            $contrat->isMission = $hasMissions;
        }
    }



    public function calculStructure(Contrat $contrat): void
    {
        if ($contrat->id) {
            // Si le contrat existe déjà et a été valider, on ne touche à rien et on remonte ce qui avait déjà été décidé avant, on recalcule pour un projet
            return;
        }

        if ($contrat->isMission) {
            if ($this->parametreMis == Parametre::CONTRAT_MIS_GLOBAL) {
                $contrat->structureId = null;
                return;
            }
        } elseif ($this->parametreEns == Parametre::CONTRAT_ENS_GLOBAL) {
            $contrat->structureId = null;
            return;
        }

        $structures = [];
        foreach ($contrat->volumesHoraires as $vh) {
            $structures[$vh->structureId] = $vh->structureId;
        }

        if (count($structures) == 1) {
            $contrat->structureId = current($structures);
        } else {
            $contrat->structureId = null;
        }
    }



    /**
     * @param array|Contrat[] $contrats
     * @throws Exception
     */
    public function calculParentsIds(array &$contrats): void
    {
        if (count($contrats) < 2) {
            // On élague tous les cas simples où il n'y a qu'un document max => c'est forcément un contrat
            return;
        }

        // Calcul et tri par $contratsEdites / $avenantsEdites / $autres
        $contratsEdites = [];
        //$avenantsEdites = [];
        $autres = [];
        foreach ($contrats as $contrat) {
            if ($contrat->id && $contrat->edite) {
                if (!$contrat->parent) {
                    $contratsEdites[] = $contrat;
                }
            } else {
                $autres[] = $contrat;
            }
        }

        if (empty($contratsEdites)) {
            // on n'a trouvé aucun contrat déjà édité pour y associer des avenants
            return;
        }

        // On traite tous les autres pour leur trouver un éventuel parent
        foreach ($autres as $autre) {
            $parentsPotentiels = [];
            foreach ($contratsEdites as $candidat) {
                if ($this->isParentPotentiel($autre, $candidat)) {
                    $parentsPotentiels[] = $candidat;
                }
            }

            if (!empty($parentsPotentiels)) {
                // Si on a des parents potentiels, on lui donne le meilleur
                $autre->setParent($this->meilleurParent($parentsPotentiels));
            }
        }
    }



    /**
     * @param array|Contrat[] $contrats
     * @return void
     */
    public function gestionContratsSansHeures(array &$contrats): void
    {
        $hasContrat           = false;
        $hasContratSansHeures = false;
        foreach ($contrats as $contrat) {
            if (!$contrat->historise) {
                if (empty($contrat->volumesHoraires)) {
                    if (null == $contrat->parent) {
                        $hasContratSansHeures = true;
                    }
                } else {
                    $hasContrat = true;
                }
            }
        }

        if ($hasContrat && $hasContratSansHeures) {
            // On supprime les contrats sans heures
            foreach ($contrats as $uuid => $contrat) {
                if (count($contrat->volumesHoraires) == 0 && !$contrat->isMission && !$contrat->id) {
                    unset($contrats[$uuid]);
                    unset($this->intervenants[$contrat->intervenantId][$uuid]);
                }
            }
        } elseif (!$hasContrat && !$hasContratSansHeures) {
            // Si rien, alors on crée un contrat sans heure

            /** @var Contrat $contratSource */
            // On récup le contrat source, pour en extraire l'année et l'intervenant
            $contratSource = current($contrats);
            $uuid          = $this->generateUUID($contratSource->intervenantId);
            $contrat       = $this->getContrat($contratSource->intervenantId, $uuid);

            $contrat->typeService = $this->getServiceTypeService()->getEnseignement();
            $contrat->annee       = $contratSource->annee;
            $contrats[$uuid]      = $contrat;
        }

    }



    public function isParentPotentiel(Contrat $contrat, Contrat $candidat): bool
    {
        if ($candidat->isMission !== $contrat->isMission || $contrat === $candidat || !$candidat->edite || $contrat->historise) {
            return false; // pas les mêmes types => pas de lien, pas edite ou supprimer, ou lui meme
        }

        if ($contrat->isMission) {
            switch ($this->parametreMis) {
                case Parametre::CONTRAT_MIS_GLOBAL:
                    return true; // paramètre global => ce sera un avenant
                case Parametre::CONTRAT_MIS_COMPOSANTE:
                    if (empty($contrat->structureId)) {
                        throw new Exception('En paramétrage par composante, le nouveau contrat doit avoir une structure bien identifiée');
                    }

                    return $candidat->hasStructureId($contrat->structureId);
                case Parametre::CONTRAT_MIS_MISSION:
                    $contratMissionId = $contrat->getMissionId();
                    if (empty($contratMissionId)) {
                        throw new Exception('En paramétrage par mission, le nouveau contrat doit avoir une mission unique bien identifiée');
                    }

                    return $candidat->hasMissionId($contratMissionId);
            }
        } else {
            switch ($this->parametreEns) {
                case Parametre::CONTRAT_ENS_GLOBAL:
                    return true;
                case Parametre::CONTRAT_ENS_COMPOSANTE:
                    if (empty($contrat->structureId)) {
                        throw new Exception('En paramétrage par composante, le nouveau contrat doit avoir une structure bien identifiée');
                    }
                    // En enseignement on a qu'un seul contrat donc un avenant peut s'attacher a un contrat d'une autre composante
                    // Tant que c'est un contrat et pas un avenant il est un parent potentiel
                    return true;
            }
        }

        throw new Exception('Une erreur est survenue : cas de détection de parent potentiel non géré');
    }



    /**
     * @param array|Contrat[] $parents
     * @return Contrat
     */
    public function meilleurParent(array $parents): Contrat
    {
        // Cas facile : 1 parent => c'est lui
        if (count($parents) == 1) {
            return current($parents);
        }

        $idMax    = -1;
        $meilleur = null;
        foreach ($parents as $parent) {
            if ($parent->id > $idMax) {
                $meilleur = $parent;
                $idMax    = $parent->id;
            }
        }
        // on retourne le dernier projet créé, sans tenir compte des dates

        return $meilleur;
    }



    public function calculTauxRemu(Contrat $contrat): void
    {
        if ($contrat->isMission) {
            /* Dans le cas où le contrat n'a pas d'heures et que c'est une mission, on utilise les taux du parent */
            if (empty($contrat->volumesHoraires) && $contrat->parent && $contrat->parent->isMission) {
                $contrat->tauxRemuId       = $contrat->parent->tauxRemuId;
                $contrat->tauxRemuMajoreId = $contrat->parent->tauxRemuMajoreId;
            } else {
                $listeTauxRemu       = [];
                $listeTauxRemuMajore = [];

                foreach ($contrat->volumesHoraires as $vh) {
                    $listeTauxRemu[$vh->tauxRemuId]                  = $vh->tauxRemuId;
                    $listeTauxRemuMajore[$vh->tauxRemuMajoreId ?? 0] = $vh->tauxRemuMajoreId;
                }

                $countTauxRemu       = count($listeTauxRemu);
                $countTauxRemuMajore = count($listeTauxRemuMajore);

                if ($countTauxRemu == 1) {
                    $contrat->tauxRemuId = current($listeTauxRemu);
                }
                if ($countTauxRemuMajore == 1) {
                    $contrat->tauxRemuMajoreId = current($listeTauxRemuMajore);
                }
            }
        }

        if (empty($contrat->tauxRemuId)) {
            $contrat->tauxRemuId = $this->parametreTauxRemuId;
        }

        if (empty($contrat->tauxRemuMajoreId)) {
            $contrat->tauxRemuMajoreId = $contrat->tauxRemuId;
        }
        if (!$contrat->annee) {
            throw new \Exception('Le calcul du taux de rémunération ne peut être effectué sans année affecté au contrat');
        }
        if ($contrat->tauxRemuId) {
            $dateRef                       = $contrat->debutValidite ?? $contrat->histoCreation ?? $contrat->annee->getDateDebut();
            $contrat->tauxRemuDate         = $this->getServiceTauxRemu()->tauxDate($contrat->tauxRemuId, $dateRef);
            $contrat->tauxRemuValeur       = $this->getServiceTauxRemu()->tauxValeur($contrat->tauxRemuId, $dateRef);
            $contrat->tauxRemuMajoreValeur = $this->getServiceTauxRemu()->tauxValeur($contrat->tauxRemuMajoreId, $dateRef);
        }
    }



    /**
     * @param array|Contrat[] $contrats
     */
    public function calculDateContrat(array $contrats): void
    {
        foreach ($contrats as $contrat) {
            /** @var Contrat $contrat */
            if ($contrat->finValidite != null && $contrat->debutValidite != null) {
                continue;
            }


            if ($contrat->isMission) {
                $this->calculDateContratMission($contrat);
            } else {
                if ($contrat->debutValidite == null) {
                    $contrat->debutValidite = $contrat->annee->getDateDebut();
                }
                if ($contrat->finValidite == null) {
                    $contrat->finValidite = $contrat->annee->getDateFin();
                }
            }

        }
    }



    /**
     * @param array|Contrat[] $contrats
     */
    public function calculNumerosAvenants(array &$contrats): void
    {
        foreach ($contrats as $contrat) {
            $this->calculNumeroAvenant($contrat);
        }
    }



    public function calculNumeroAvenant(Contrat $contrat): void
    {
        //On connait deja les numéro d'avenant récuperé de la table contrat et on a pas besoin de le recalculer pour les contrat editer (on le fait cependant pour les projets)
        if (($contrat->id && $contrat->edite == 1) || $contrat->historise) {
            return;
        }

        //On cherche l'avenant au numéro le plus grand et on incrémente de 1 pour l'avenant suivant
        $contratNumero = 0;
        if ($contrat->parent) {
            foreach ($contrat->parent->avenants as $contratParser) {
                // On ne s'intéresse qu'aux avenants étant deja créés
                if ($contratParser->edite && $contratParser->numeroAvenant > $contratNumero && !$contratParser->historise) {
                    $contratNumero = $contratParser->numeroAvenant;
                }
            }

            $contrat->numeroAvenant = $contratNumero + 1;
        }
    }



    public function calculTermine(Contrat $contrat): void
    {
        switch ($this->parametreFranchissement) {
            case Parametre::CONTRAT_FRANCHI_VALIDATION:
                $contrat->termine = $contrat->edite;
                break;
            case Parametre::CONTRAT_FRANCHI_DATE_RETOUR:
                $contrat->termine = $contrat->edite && $contrat->signe;
        }
    }



    public function calculTauxCongesPayes(Contrat $contrat): void
    {
        if ($contrat->isMission) {
            $contrat->tauxCongesPayes = $this->parametreTauxCongesPayes;
        } else {
            $contrat->tauxCongesPayes = 0;
        }
    }



    public function calculLibelles(Contrat $contrat): void
    {
        $contrat->autresLibelles = $this->calculAutresLibelles($contrat);
        if ($contrat->isMission) {
            $contrat->missionsLibelles     = $this->calculMissionsLibelles($contrat);
            $contrat->typesMissionLibelles = $this->calculTypesMissionLibelles($contrat);
        }

    }



    public function calculAutresLibelles(Contrat $contrat): string
    {
        $libelles = [];

        foreach ($contrat->volumesHoraires as $vh) {
            if (!empty($vh->autreLibelle)) {
                $libelles[$vh->autreLibelle] = $vh->autreLibelle;
            }
        }

        sort($libelles); // Tri alphabétique

        $result                  = implode(', ', $libelles);
        $contrat->autresLibelles = $result;
        return $result;
    }



    public function calculMissionsLibelles(Contrat $contrat): string
    {
        $libelles = [];

        foreach ($contrat->volumesHoraires as $vh) {
            if (!empty($vh->missionLibelle)) {
                $libelles[$vh->missionLibelle] = $vh->missionLibelle;
            }
        }

        sort($libelles); // Tri alphabétique

        $result                    = implode(', ', $libelles);
        $contrat->missionsLibelles = $result;
        return $result;
    }



    public function calculTypesMissionLibelles(Contrat $contrat): string
    {
        $libelles = [];

        foreach ($contrat->volumesHoraires as $vh) {
            if (!empty($vh->typeMissionLibelle)) {
                $libelles[$vh->typeMissionLibelle] = $vh->typeMissionLibelle;
            }
        }

        sort($libelles); // Tri alphabétique

        $result                        = implode(', ', $libelles);
        $contrat->typesMissionLibelles = $result;
        return $result;
    }



    public function calculTotalHeures(Contrat $contrat): void
    {
        $totalHeures = 0;


        //On ajoute les heures du contrat pour lequel on cherche
        foreach ($contrat->volumesHoraires as $vh) {
            $contrat->heuresFormation[$vh->missionId] = $vh->heuresFormation;
            $totalHeures += $vh->heures;
        }

        $contrat->totalHeures = round($totalHeures, 2);
    }



    public function calculTotalHETD(Contrat $contrat): void
    {
        $totalGlobal  = 0;
        $totalContrat = 0;

        //On ajoute les heures du contrat pour lequel on cherche
        foreach ($contrat->volumesHoraires as $vh) {
            $totalGlobal  += $vh->hetd;
            $totalContrat += $vh->hetd;
        }

        if ($contrat->parent != null) {

            // On ajoute les heures des autres avenants liés au même contrat déjà contractualisé avec un numéro d'avenant infèrieur
            foreach ($contrat->parent->avenants as $contratParser) {
                //On ne s'occupe que des avenants déjà contractualisés
                if (!$contratParser->id || !$contratParser->edite || $contratParser->numeroAvenant >= $contrat->numeroAvenant) {
                    continue;
                }

                foreach ($contratParser->volumesHoraires as $vh) {
                    $totalGlobal += $vh->hetd;
                }
            }
            //On ajoute les volumes horaires liés au contrat parent
            foreach ($contrat->parent->volumesHoraires as $vh) {
                $totalGlobal += $vh->hetd;
            }
        }
        $contrat->totalHetd       = round($totalContrat, 2);
        $contrat->totalGlobalHetd = round($totalGlobal, 2);
    }



    public function calculIsProlongation(Contrat $contrat): void
    {
        $maxDateFin = null;
        if ($contrat->parent != null) {
            $maxDateFin = $contrat->parent->finValidite;

            foreach ($contrat->parent->avenants as $contratParser) {
                //On ne s'occupe que des avenants déjà contractualisés
                if (!$contratParser->id || !$contratParser->edite || $contratParser->numeroAvenant >= $contrat->numeroAvenant) {
                    continue;
                }

                if ($maxDateFin < $contratParser->finValidite) {
                    $maxDateFin = $contratParser->finValidite;
                }
            }
        }

        if ($maxDateFin != null && $maxDateFin < $contrat->finValidite) {
            $contrat->prolongation = true;
        }
    }



    public function generateUUID(int $intervenantId, ?int $contratId = null, ?int $structureId = null, ?int $missionId = null, ?int $parentId = null): string
    {
        if ($contratId) {
            return 'contrat_id_' . $contratId;
        }
        if ($parentId) {
            return 'prolongation_contrat_' . $parentId;
        }

        if ($missionId != null) {
            return match ($this->parametreMis) {
                Parametre::CONTRAT_MIS_MISSION    => 'mis_mission_' . $intervenantId . '_' . $missionId,
                Parametre::CONTRAT_MIS_COMPOSANTE => 'mis_structure_' . $intervenantId . '_' . $structureId,
                default                           => 'mis_global_' . $intervenantId,
            };
        } else {
            return match ($this->parametreEns) {
                Parametre::CONTRAT_ENS_COMPOSANTE => 'ens_structure_' . $intervenantId . '_' . $structureId,
                default                           => 'ens_global_' . $intervenantId,
            };
        }
    }



    public function getTypeContrat(Contrat $contrat): TypeContrat
    {
        if (empty($contrat->parent)) {
            return $this->getServiceTypeContrat()->getContrat();
        } else {
            return $this->getServiceTypeContrat()->getAvenant();
        }
    }



    public function getVolumeHoraireTypeService(VolumeHoraire $vh): TypeService
    {
        if ($vh->serviceId) {
            return $this->getServiceTypeService()->getEnseignement();
        }

        if ($vh->serviceReferentielId) {
            return $this->getServiceTypeService()->getReferentiel();
        }

        if ($vh->missionId) {
            return $this->getServiceTypeService()->getMission();
        }

        // si on est sur des contrats sans volumes horaires => enseignement
        // si on est sur des avenants dans volumes horaires => mission
        if (empty($vh->contrat->parent)) {
            return $this->getServiceTypeService()->getEnseignement();
        } else {
            return $this->getServiceTypeService()->getMission();
        }
    }



    public function extractContrat(Contrat $contrat): array
    {
        $typeServiceId = $contrat->isMission ? $this->getServiceTypeService()->getMission()->getId() : $this->getServiceTypeService()->getEnseignement()->getId();
        $data          = [
            'annee_id'                  => $contrat->annee->getId(),
            'intervenant_id'            => $contrat->intervenantId,
            'actif'                     => $contrat->actif,
            'structure_id'              => $contrat->structureId,
            'validation_id'             => $contrat->validationId,
            'edite'                     => $contrat->edite,
            'signe'                     => $contrat->signe,
            'autre_libelle'             => null,
            'autres'                    => 0,
            'autres_libelles'           => $contrat->autresLibelles,
            'missions_libelles'         => $contrat->missionsLibelles,
            'types_mission_libelles'    => $contrat->typesMissionLibelles,
            'cm'                        => 0,
            'contrat_id'                => $contrat->id,
            'contrat_parent_id'         => $contrat->parent?->id,
            'numero_avenant'            => $contrat->numeroAvenant,
            'prolongation'              => $contrat->prolongation,
            'date_creation'             => $contrat->histoCreation,
            'date_debut'                => $contrat->debutValidite,
            'date_fin'                  => $contrat->finValidite,
            'hetd'                      => 0,
            'total_hetd'                => $contrat->totalHetd,
            'total_global_hetd'         => $contrat->totalGlobalHetd,
            'heures'                    => 0,
            'total_heures'              => $contrat->totalHeures,
            'mission_id'                => $contrat->getMissionId(),
            'service_id'                => null,
            'service_referentiel_id'    => null,
            'taux_conges_payes'         => $contrat->tauxCongesPayes,
            'taux_remu_date'            => $contrat->tauxRemuDate,
            'taux_remu_id'              => $contrat->tauxRemuId,
            'taux_remu_majore_id'       => $contrat->tauxRemuMajoreId,
            'taux_remu_majore_valeur'   => $contrat->tauxRemuMajoreValeur,
            'taux_remu_valeur'          => $contrat->tauxRemuValeur,
            'td'                        => 0,
            'termine'                   => $contrat->termine,
            'tp'                        => 0,
            'type_contrat_id'           => $this->getTypeContrat($contrat)->getId(),
            'type_service_id'           => $typeServiceId,
            'uuid'                      => $contrat->uuid,
            'volume_horaire_id'         => null,
            'volume_horaire_mission_id' => null,
            'volume_horaire_ref_id'     => null,
        ];

        return $data;
    }



    public function extractVolumeHoraire(VolumeHoraire $vh): array
    {
        $contrat = $vh->contrat;
        $data    = [
            'annee_id'                  => $contrat->annee->getId(),
            'intervenant_id'            => $contrat->intervenantId,
            'actif'                     => $contrat->actif,
            'structure_id'              => $contrat->structureId,
            'validation_id'             => $contrat->validationId,
            'edite'                     => $contrat->edite,
            'signe'                     => $contrat->signe,
            'autre_libelle'             => $vh->autreLibelle,
            'autres'                    => $vh->autres,
            'autres_libelles'           => $contrat->autresLibelles,
            'missions_libelles'         => $contrat->missionsLibelles,
            'types_mission_libelles'    => $contrat->typesMissionLibelles,
            'cm'                        => $vh->cm,
            'contrat_id'                => $contrat->id,
            'contrat_parent_id'         => $contrat->parent?->id,
            'numero_avenant'            => $contrat->numeroAvenant,
            'prolongation'              => $contrat->prolongation,
            'date_creation'             => $contrat->histoCreation,
            'date_debut'                => $contrat->debutValidite,
            'date_fin'                  => $contrat->finValidite,
            'hetd'                      => $vh->hetd,
            'total_hetd'                => $contrat->totalHetd,
            'total_global_hetd'         => $contrat->totalGlobalHetd,
            'heures'                    => $vh->heures,
            'total_heures'              => $contrat->totalHeures,
            'mission_id'                => $vh->missionId,
            'service_id'                => $vh->serviceId,
            'service_referentiel_id'    => $vh->serviceReferentielId,
            'taux_conges_payes'         => $contrat->tauxCongesPayes,
            'taux_remu_date'            => $contrat->tauxRemuDate,
            'taux_remu_id'              => $contrat->tauxRemuId,
            'taux_remu_majore_id'       => $contrat->tauxRemuMajoreId,
            'taux_remu_majore_valeur'   => $contrat->tauxRemuMajoreValeur,
            'taux_remu_valeur'          => $contrat->tauxRemuValeur,
            'td'                        => $vh->td,
            'termine'                   => $contrat->termine,
            'tp'                        => $vh->tp,
            'type_contrat_id'           => $this->getTypeContrat($contrat)->getId(),
            'type_service_id'           => $this->getVolumeHoraireTypeService($vh)->getId(),
            'uuid'                      => $contrat->uuid,
            'volume_horaire_id'         => $vh->volumeHoraireId,
            'volume_horaire_mission_id' => $vh->volumeHoraireMissionId,
            'volume_horaire_ref_id'     => $vh->volumeHoraireRefId,
            'total_heures_formation'    => array_sum($contrat->heuresFormation),
        ];

        return $data;
    }



    public function exporter(): void
    {
        $this->tblData = [];
        foreach ($this->intervenants as $contrats) {
            foreach ($contrats as $contrat) {
                if (!$contrat->historise) {
                    if (empty($contrat->volumesHoraires)) {
                        $ligne           = $this->extractContrat($contrat);
                        $this->tblData[] = array_change_key_case($ligne, CASE_UPPER); // avant migration postgresql
                    } else {
                        $vhs = $contrat->volumesHoraires;

                        usort($vhs, function (VolumeHoraire $a, VolumeHoraire $b) {
                            $aid = $a->volumeHoraireId . '-' . $a->volumeHoraireRefId . '-' . $a->volumeHoraireMissionId;
                            $bid = $b->volumeHoraireId . '-' . $b->volumeHoraireRefId . '-' . $b->volumeHoraireMissionId;

                            return $aid > $bid ? 1 : -1;
                        });
                        $vhIndex = 0;
                        foreach ($vhs as $volumeHoraire) {
                            $ligne                         = $this->extractVolumeHoraire($volumeHoraire);
                            $ligne['volume_horaire_index'] = $vhIndex;
                            $this->tblData[]               = array_change_key_case($ligne, CASE_UPPER); // avant migration postgresql
                            $vhIndex++;
                        }
                    }
                }
            }
        }
    }



    public function enregistrement(TableauBord $tableauBord, array $params): void
    {

        // Enregistrement en BDD
        $key = $tableauBord->getOption('key');

        $table = $this->getBdd()->getTable('TBL_CONTRAT');

        //         on force la DDL pour éviter de faire des requêtes en plus
        //        $table->setDdl(['sequence' => $tableauBord->getOption('sequence'), 'columns' => array_fill_keys($tableauBord->getOption('cols'), [])]);

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['INTERVENANT_ID']),
            'callback'           => function (string $action, int $progress, int $total) use ($tableauBord) {
                $tableauBord->onAction(Event::PROGRESS, $progress, $total);
            },
        ];

        $table->merge($this->tblData, $key, $options);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }



    /**
     * @param Contrat       $contrat
     * @param array         $dateMissions
     * @param DateTime|null $dateFinContrat
     * @param DateTime|null $dateDebutContrat
     * @return array
     */
    public function calculDateContratEdite(Contrat $contrat, array $dateMissions, ?DateTime $dateFinContrat, ?DateTime $dateDebutContrat): array
    {
        foreach ($contrat->volumesHoraires as $volumeHoraire) {
            if (empty($dateMissions[$volumeHoraire->missionId])
                || $volumeHoraire->dateFinMission > $dateMissions[$volumeHoraire->missionId]) {
                $dateMissions[$volumeHoraire->missionId] = $volumeHoraire->dateFinMission;
            }
        }

        foreach ($contrat->avenants as $avenant) {
            if (!$avenant->edite) {
                continue;
            }

            if (empty($dateFinContrat) || $avenant->finValidite > $dateFinContrat) {
                $dateFinContrat = $avenant->finValidite;
            }
            if ($dateDebutContrat == null || $avenant->debutValidite < $dateDebutContrat) {
                $dateDebutContrat = $avenant->debutValidite;
            }

            foreach ($avenant->volumesHoraires as $volumeHoraire) {
                if ($volumeHoraire->missionId == null) {
                    continue;
                }
                if (empty($dateMissions[$volumeHoraire->missionId]) || $volumeHoraire->dateFinMission > $dateMissions[$volumeHoraire->missionId]) {
                    $dateMissions[$volumeHoraire->missionId] = $volumeHoraire->dateFinMission;
                }
            }
        }
        return [$dateMissions, $dateFinContrat, $dateDebutContrat];
    }



    /**
     * @param mixed   $dateMissions
     * @param Contrat $contrat
     * @return array
     */
    public function changementDateAvenantNonEdite(array $dateMissions, Contrat $contrat): array
    {
        if (!empty($dateMissions)) {
            foreach ($contrat->avenants as $avenant) {
                if ($avenant->edite) {
                    continue;
                }

                foreach ($avenant->volumesHoraires as $volumeHoraire) {
                    if ($volumeHoraire->missionId == null) {
                        continue;
                    }

                    if (!empty($dateMissions[$volumeHoraire->missionId]) && $avenant->finValidite >= $dateMissions[$volumeHoraire->missionId]) {
                        unset($dateMissions[$volumeHoraire->missionId]);
                    } elseif (!empty($dateMissions[$volumeHoraire->missionId]) && $avenant->finValidite < $dateMissions[$volumeHoraire->missionId]) {
                        $avenant->finValidite = $dateMissions[$volumeHoraire->missionId];
                        unset($dateMissions[$volumeHoraire->missionId]);
                    }
                }
            }
        }
        return $dateMissions;
    }



    /**
     * @param Contrat  $contrat
     * @param array    $dateMissions
     * @param mixed    $dateDebutContrat
     * @param int|null $intervenantId
     * @return Contrat
     */
    public function creationAvenantProlongation(Contrat $contrat, array $dateMissions, mixed $dateDebutContrat, ?int $intervenantId): Contrat
    {
        $avenantProlongation         = new Contrat();
        $avenantProlongation->parent = $contrat;
        $contrat->avenants[]         = $avenantProlongation;
        $avenantProlongation->annee  = $contrat->annee;
        $dateAvenant                 = null;
        foreach ($dateMissions as $dateMission) {
            if (empty($dateAvenant) || $dateAvenant < $dateMission) {
                $dateAvenant = $dateMission;
            }
        }
        $avenantProlongation->finValidite   = $dateAvenant;
        $avenantProlongation->debutValidite = $dateDebutContrat;
        $avenantProlongation->isMission     = true;
        $avenantProlongation->intervenantId = $intervenantId;
        $avenantProlongation->structureId   = $contrat->structureId;
        $avenantProlongation->uuid          = $this->generateUUID($intervenantId, $avenantProlongation->id, $avenantProlongation->structureId, $avenantProlongation->getMissionId(), $avenantProlongation->parent->id);
        $avenantProlongation->typeService   = $contrat->typeService;

        $this->addContrat($avenantProlongation);

        return $avenantProlongation;
    }



    public function calculDateContratMission(Contrat $contrat): void
    {
        $dateDebut = null;
        $dateFin   = null;
        foreach ($contrat->volumesHoraires as $vh) {
            if ($dateDebut == null || $dateDebut > $vh->dateDebutMission) {
                $dateDebut = $vh->dateDebutMission;
            }

            if ($dateFin == null || $dateFin < $vh->dateFinMission) {
                $dateFin = $vh->dateFinMission;
            }
        }

        if ($contrat->parent != null) {

            // On ajoute les heures des autres avenants liés au même contrat déjà contractualisé avec un numéro d'avenant infèrieur
            foreach ($contrat->parent->avenants as $contratParser) {
                //On ne s'occupe que des avenants déjà contractualisés
                if (!$contratParser->id || !$contratParser->edite || $contratParser->numeroAvenant >= $contrat->numeroAvenant) {
                    continue;
                }

                foreach ($contratParser->volumesHoraires as $vh) {
                    if ($contrat->debutValidite == null || $dateDebut > $vh->dateDebutMission) {
                        $dateDebut = $vh->dateDebutMission;
                    }

                    if ($contrat->finValidite == null || $dateFin > $vh->dateFinMission) {
                        $dateFin = $vh->dateFinMission;
                    }
                }
            }
            //On ajoute les volumes horaires liés au contrat parent
            foreach ($contrat->parent->volumesHoraires as $vh) {
                if ($contrat->debutValidite == null || $dateDebut > $vh->dateDebutMission) {
                    $dateDebut = $vh->dateDebutMission;
                }

                if ($contrat->finValidite == null || $dateFin > $vh->dateFinMission) {
                    $dateFin = $vh->dateFinMission;
                }
            }
        }


        if ($dateDebut == null) {
            $dateDebut = $contrat->annee->getDateDebut();
        }
        if ($dateFin == null) {
            $dateFin = $contrat->annee->getDateFin();
        }
        if ($contrat->debutValidite == null) {
            $contrat->debutValidite = $dateDebut;
        }
        if ($contrat->finValidite == null) {
            $contrat->finValidite = $dateFin;
        }
    }

}
