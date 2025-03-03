<?php

namespace Contrat\Tbl\Process;


use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Contrat\Tbl\Process\Sub\Contrat;
use Contrat\Tbl\Process\Sub\VolumeHoraire;
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

    private string $parametreAvenant;

    private string $parametreEns;

    private string $parametreMis;

    private string $parametreFranchissement;

    private float $parametreTauxRemu = 1.0;

    private float $parametreTauxCongesPayes = 0.1;

    private array $intervenants = [];

    private array $contrats = [];



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
        }
        $tata = 'toto';
    }



    protected function init(): void
    {
        $parametres = $this->getServiceParametres();

        $this->parametreAvenant         = $parametres->get('avenant');
        $this->parametreEns             = $parametres->get('contrat_ens');
        $this->parametreMis             = $parametres->get('contrat_mis');
        $this->parametreFranchissement  = $parametres->get('contrat_regle_franchissement');
        $this->parametreTauxRemu        = (int)$parametres->get('taux-remu');
        $this->parametreTauxCongesPayes = (float)$parametres->get('taux_conges_payes');

        $this->intervenants = [];
    }



    protected function loadContrats(array $params): void
    {
        $sql = "
        SELECT
            c.id contrat_id,
            i.id intervenant_id,
            c.structure_id,
            c.contrat_id parent_id,
            c.numero_avenant,
            c.debut_validite,
            c.fin_validite
        FROM
            contrat c
            JOIN intervenant i ON i.id = c.intervenant_id
            JOIN statut si ON si.id = i.statut_id
        WHERE
            c.histo_destruction IS NULL
            /*@INTERVENANT_ID=i.id*/
            /*@ANNEE_ID=i.annee_id*/
            /*@STATUT_ID=i.statut_id*/
        ";

        $sql    = $this->getServiceBdd()->injectKey($sql, $params);
        $parser = $this->getBdd()->selectEach($sql);
        while ($data = $parser->next()) {
            $data          = array_change_key_case($data, CASE_LOWER);
            $intervenantId = (int)$data['intervenant_id'];
            $contratId     = (int)$data['contrat_id'];
            $uuid          = $this->generateUUID($contratId);
            $contrat       = $this->getContrat($intervenantId, $uuid);
            $this->contratHydrateFromDb($contrat, $data);
        }

    }



    public function contratHydrateFromDb(Contrat $contrat, array $data): void
    {
        $contrat->id            = (int)$data['contrat_id'];
        $contrat->intervenantId = (int)$data['intervenant_id'];
        $contrat->structureId   = (int)$data['structure_id'] ?: null;
        $parentId               = (int)$data['parent_id'] ?: null;
        if ($parentId) {
            $uuid            = $this->generateUUID($parentId);
            $contrat->parent = $this->getContrat($contrat->intervenantId, $uuid);
        }
        $contrat->numeroAvenant = (int)$data['numero_avenant'];
        $contrat->debutValidite = $data['debut_validite'] ? new \DateTime($data['debut_validite']) : null;
        $contrat->finValidite   = $data['fin_validite'] ? new \DateTime($data['fin_validite']) : null;
    }



    private function getContrat(int $intervenantId, string $uuid): Contrat
    {
        if (!array_key_exists($intervenantId, $this->intervenants)) {
            $this->intervenants[$intervenantId] = [];
        }

        if (!array_key_exists($uuid, $this->intervenants[$intervenantId])) {
            $this->intervenants[$intervenantId][$uuid] = new Contrat();
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

            $intervenantId = (int)$data['intervenant_id'];
            $contratId = (int)$data['contrat_id'] ?: null;
            $uuid = $this->generateUUID($contratId, $volumeHoraire->structureId, $volumeHoraire->missionId);
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



    public function generateUUID(?int $contratId, ?int $structureId = null, ?int $missionId = null): string
    {
        if ($contratId) {
            return 'contrat_id_' . $contratId;
        }

        if ($missionId != null) {
            /* à compléter !!! */
        }else{
            /* à compléter !!! */
        }

        return 'TODO';
    }
}