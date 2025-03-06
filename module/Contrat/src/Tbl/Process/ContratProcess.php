<?php

namespace Contrat\Tbl\Process;


use Application\Entity\Db\Parametre;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Contrat\Tbl\Process\Model\Contrat;
use Contrat\Tbl\Process\Model\VolumeHoraire;
use Paiement\Service\TauxRemuServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
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

    private string $parametreAvenant;

    private string $parametreEns;

    private string $parametreMis;

    private string $parametreFranchissement;

    private int $parametreTauxRemuId;

    private float $parametreTauxCongesPayes = 0.1;

    /** @var array|Contrat[][] */
    private array $intervenants = [];

    private array $contrats = [];

    private array $tblData = [];



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        if (empty($params)) {
            $annees = $this->getServiceAnnee()->getActives();
            foreach ($annees as $annee) {
                $this->run($tableauBord, ['ANNEE_ID' => $annee->getId()]);
            }
        } else {
            $this->init();
            $this->loadContrats($params);
            $this->loadVolumesHoraires($params);
            $this->traitement();
            $this->exporter();
            //$this->enregistrement($tableauBord, $params);
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



    protected function loadContrats(array $params): void
    {
        $sql    = $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT_CONTRAT');
        $sql    = $this->getServiceBdd()->injectKey($sql, $params);
        $parser = $this->getBdd()->selectEach($sql);
        while ($data = $parser->next()) {
            $data          = array_change_key_case($data, CASE_LOWER);
            $intervenantId = (int)$data['intervenant_id'];
            $contratId     = (int)$data['contrat_id'];
            $uuid          = $this->generateUUID($intervenantId, $contratId);
            $contrat       = $this->getContrat($intervenantId, $uuid);
            $this->contratHydrateFromDb($contrat, $data);
        }

    }



    public function contratHydrateFromDb(Contrat $contrat, array $data): void
    {
        $contrat->actif          = $data['actif'] === '1';
        $contrat->anneeDateDebut = new \DateTime($data['annee_date_debut']);
        $contrat->id             = (int)$data['contrat_id'];
        $contrat->intervenantId  = (int)$data['intervenant_id'];
        $contrat->structureId    = (int)$data['structure_id'] ?: null;
        $parentId                = (int)$data['parent_id'] ?: null;
        if ($parentId) {
            $uuid                        = $this->generateUUID($contrat->intervenantId, $parentId);
            $contrat->parent             = $this->getContrat($contrat->intervenantId, $uuid);
            $contrat->parent->avenants[] = $contrat;
        }
        $contrat->numeroAvenant = (int)$data['numero_avenant'];
        $contrat->totalHetd     = $data['totalHetd'] != null ? (int)$data['totalHetd'] : null;
        $contrat->debutValidite = $data['debut_validite'] ? new \DateTime($data['debut_validite']) : null;
        $contrat->finValidite   = $data['fin_validite'] ? new \DateTime($data['fin_validite']) : null;
        $contrat->histoCreation = $data['histo_creation'] ? new \DateTime($data['histo_creation']) : null;
        $contrat->edite         = $data['edite'] === '1';
        $contrat->envoye        = $data['envoye'] === '1';
        $contrat->retourne      = $data['retourne'] === '1';
        $contrat->signe         = $data['signe'] === '1';
    }



    private function getContrat(int $intervenantId, string $uuid): Contrat
    {
        if (!array_key_exists($intervenantId, $this->intervenants)) {
            $this->intervenants[$intervenantId] = [];
        }

        if (!array_key_exists($uuid, $this->intervenants[$intervenantId])) {
            $this->intervenants[$intervenantId][$uuid] = new Contrat($uuid);
        }
        return $this->intervenants[$intervenantId][$uuid];
    }



    protected function loadVolumesHoraires(array $params): void
    {
        $sql    = $this->getServiceBdd()->getViewDefinition('V_TBL_CONTRAT_VOLUME_HORAIRE');
        $sql    = $this->getServiceBdd()->injectKey($sql, $params);
        $parser = $this->getBdd()->selectEach($sql);
        while ($data = $parser->next()) {
            $data          = array_change_key_case($data, CASE_LOWER);
            $volumeHoraire = new VolumeHoraire();
            $this->volumeHoraireHydrateFromDb($volumeHoraire, $data);

            $intervenantId                                              = (int)$data['intervenant_id'];
            $contratId                                                  = (int)$data['contrat_id'] ?: null;
            $uuid                                                       = $this->generateUUID($intervenantId, $contratId, $volumeHoraire->structureId, $volumeHoraire->missionId);
            $this->getContrat($intervenantId, $uuid)->volumesHoraires[] = $volumeHoraire;
        }
    }



    public function volumeHoraireHydrateFromDb(VolumeHoraire $vh, array $data): void
    {
        $vh->anneeId                = (int)$data['annee_id'];
        $vh->structureId            = (int)$data['structure_id'];
        $vh->serviceId              = (int)$data['service_id'] ?: null;
        $vh->serviceReferentielId   = (int)$data['service_referentiel_id'] ?: null;
        $vh->missionId              = (int)$data['mission_id'] ?: null;
        $vh->volumeHoraireId        = (int)$data['volume_horaire_id'] ?: null;
        $vh->volumeHoraireRefId     = (int)$data['volume_horaire_ref_id'] ?: null;
        $vh->volumeHoraireMissionId = (int)$data['volume_horaire_mission_id'] ?: null;
        $vh->tauxRemuId             = (int)$data['taux_remu_id'] ?: null;
        $vh->tauxRemuMajoreId       = (int)$data['taux_remu_majore_id'] ?: null;
        $vh->cm                     = (float)$data['cm'];
        $vh->td                     = (float)$data['td'];
        $vh->tp                     = (float)$data['tp'];
        $vh->autres                 = (float)$data['autres'];
        $vh->heures                 = (float)$data['heures'];
        $vh->hetd                   = (float)$data['hetd'];
        $vh->autreLibelle           = $data['autre_libelle'];
    }



    public function traitement(): void
    {
        foreach ($this->intervenants as $intervenantId => $contrats) {
            foreach ($contrats as $uuid => $contrat) {
                $this->calculTypeService($contrat);
                $this->calculStructure($contrat);
            }

            $this->calculParentsIds($contrats);

            /* Double foreach pour calcul structure, déterminer parent_id d'abord, puis le reste après ? */
            foreach ($contrats as $uuid => $contrat) {
                $this->calculTauxRemu($contrat);
                $this->calculTotalHETD($contrat);

            }

            $this->calculNumerosAvenants($contrats);
        }
    }



    public function calculTypeService(Contrat $contrat): void
    {
        $hasMissions = false;
        $hasEnsRef   = false;

        foreach ($contrat->volumesHoraires as $vh) {
            if ($vh->missionId) $hasMissions = true;
            if ($vh->serviceId || $vh->serviceReferentielId) $hasEnsRef = true;
        }

        if ($hasMissions && $hasEnsRef) {
            throw new \Exception('Un même contrat ne peut pas mélanger des heures de missions avec des heures d\'enseignement et/ou de référentiel');
        }

        if (!$hasMissions && !$hasEnsRef) {
            // aucun volume horaire
            if ($contrat->parent) {
                // s'il a un parent, c'est qu'on est sur un avenant de modif de date de fin de mission
                $contrat->isMission = true;
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
        if ($contrat->id && $contrat->edite == 1) {
            // Si le contrat existe déjà et a été valider, on ne touche à rien et on remonte ce qui avait déjà été décidé avant, on recalcule pour un projet
            return;
        }

        if ($contrat->isMission) {
            if ($this->parametreMis == Parametre::CONTRAT_MIS_GLOBALE) {
                $contrat->structureId = null;
                return;
            }
        } else {
            if ($this->parametreEns == Parametre::CONTRAT_ENS_GLOBALE) {
                $contrat->structureId = null;
                return;
            }
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
     */
    public function calculParentsIds(array $contrats): void
    {
        if (count($contrats) < 2) {
            // On élague tous les cas simples où il n'y a qu'un document max => c'est forcément un contrat
            return;
        }

        //TODO

        $boites = [];
        foreach( $contrats as $contrat){
            $bid = $this->contratGetBid($contrat);
            if (!isset($boites[$bid])){
                $boites[$bid] = [];
            }
            $boites[$bid][] = $contrat;
        }

    }



    public function contratGetBid(Contrat $contrat): string
    {
        if ($contrat->isMission) {
            switch ($this->parametreMis) {
                case Parametre::CONTRAT_MIS_MISSION:
                    return 'mis_mission_' ;//. $contrat->missionId;
                case Parametre::CONTRAT_MIS_COMPOSANTE:
                    return 'mis_structure_' ;//. $structureId;
                default:
                    return 'mis_global';
            }
        } else {
            switch ($this->parametreMis) {
                case Parametre::CONTRAT_ENS_COMPOSANTE:
                    return 'ens_structure_' ;// . $structureId;
                default:
                    return 'ens_global';
            }
        }
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

        $contrat->tauxRemuDate         = $contrat->debutValidite ?? $contrat->anneeDateDebut;
        $contrat->tauxRemuValeur       = $this->getServiceTauxRemu()->tauxValeur($contrat->tauxRemuId, $contrat->tauxRemuDate);
        $contrat->tauxRemuMajoreValeur = $this->getServiceTauxRemu()->tauxValeur($contrat->tauxRemuMajoreId, $contrat->tauxRemuDate);
    }



    /**
     * @param array|Contrat[] $contrats
     */
    public function calculNumerosAvenants(array $contrats): void
    {
        foreach ($contrats as $contrat) {
            $this->calculNumeroAvenant($contrat);
        }
    }



    /**
     * @param Contrat         $contrat
     * @param array|Contrat[] $contrats
     */
    public function calculNumeroAvenant(Contrat $contrat): void
    {
        //On connait deja les numéro d'avenant récuperé de la table contrat et on a pas besoin de le recalculer pour les contrat editer (on le fait cependant pour les projets)
        if ($contrat->id && $contrat->edite == 1) {
            exit;
        }

        //On cherche l'avenant au numéro le plus grand et on incrémente de 1 pour l'avenant suivant
        $contratNumero = 0;
        foreach ($contrat->parent->avenants as $contratParser) {
            //On ne s'interesse qu'au avenant étant deja créer
            if ($contratParser->id && $contratParser->NumeroAvenant > $contratNumero) {
                $contratNumero = $contratParser->NumeroAvenant;
            }
        }
        $contrat->numeroAvenant = $contratNumero + 1;
    }



    /**
     * @param array|Contrat[] $contrats
     */
    public function calculTotauxHETDs(array $contrats): void
    {
        foreach ($contrats as $contrat) {
            $this->calculTotalHETD($contrat);
        }
    }



    /**
     * @param array|Contrat[] $contrats
     */
    public function calculTotalHETD(Contrat $contrat): void
    {// ne pas prendre en compte les projets amont
        if ($contrat->id && $contrat->edite == 1) {
            //Si le contrat exite on recupere le total hetd de la table contrat
            exit;
        }

        $total = 0;
        if ($contrat->parent == null) {
            //s'il n'a pas de parent alors il est un contrat on n'a besoin d'ajouter que ses propres heures
            foreach ($contrat->volumesHoraires as $vh) {
                $total += $vh->hetd;
            }
        } else {
            //On ajoute les heures du contrat a contractualisé
            foreach ($contrat->volumesHoraires as $vh) {
                $total += $vh->hetd;
            }
            // On ajout les heures des autres avenants lié au meme contrat deja contractualisé
            foreach ($contrat->parent->avenants as $contratParser) {
                //On ne s'occupe que des avenants deja contractualisé
                if (!$contratParser->id) {
                    continue;
                }

                foreach ($contratParser->volumesHoraires as $vh) {
                    $total += $vh->hetd;
                }
            }
            //On ajoutes les volumes horaire lié au contrat parent
            foreach ($contrat->parent->volumesHoraires as $vh) {
                $total += $vh->hetd;
            }
        }
        $contrat->totalHetd = $total;
    }



    public function generateUUID(int $intervenant_id, ?int $contratId, ?int $structureId = null, ?int $missionId = null): string
    {
        if ($contratId) {
            return 'contrat_id_' . $contratId;
        }

        if ($missionId != null) {
            switch ($this->parametreMis) {
                case Parametre::CONTRAT_MIS_MISSION:
                    return 'mis_mission_' . $intervenant_id . '_' . $missionId;
                case Parametre::CONTRAT_MIS_COMPOSANTE:
                    return 'mis_structure_' . $intervenant_id . '_' . $structureId;
                default:
                    return 'mis_global_' . $intervenant_id;
            }
        } else {
            switch ($this->parametreEns) {
                case Parametre::CONTRAT_ENS_COMPOSANTE:
                    return 'ens_structure_' . $intervenant_id . '_' . $structureId;
                default:
                    return 'ens_global_' . $intervenant_id;
            }
        }
    }



    public function extractContratVolumeHoraire(int $intervenantId, Contrat $contrat, VolumeHoraire $vh): array
    {
        $data = [];

        /* TODO process data */

        return $data;
    }



    public function exporter(): void
    {
        $this->tblData = [];
        foreach ($this->intervenants as $intervenantId => $contrats) {
            foreach ($contrats as $uuid => $contrat) {
                foreach ($contrat->volumesHoraires as $volumeHoraire) {
                    $this->tblData[] = $this->extractContratVolumeHoraire($intervenantId, $contrat, $volumeHoraire);
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
        ];

        $table->merge($this->tblData, $key, $options);
        // on vide pour limiter la conso de RAM
        $this->tblData = [];
    }

}