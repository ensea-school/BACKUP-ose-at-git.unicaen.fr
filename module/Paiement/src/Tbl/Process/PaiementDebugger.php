<?php

namespace Paiement\Tbl\Process;


use Application\Constants;
use Application\Entity\Db\Periode;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\DomaineFonctionnel;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\TauxRemu;
use Paiement\Tbl\Process\Sub\ServiceAPayer;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;

/**
 * Description of PaiementDebugger
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class PaiementDebugger
{
    protected PaiementProcess $process;

    public ?Intervenant $intervenant = null;

    /** @var ServiceAPayer[] */
    protected array $services = [];

    protected array $pg = [
        'mode'                           => [
            'libelle' => 'Mode de saisie pour les enseignements réalisés',
        ],
        'centres_couts_paye'             => [
            'libelle' => 'Centres de coûts utilisés pour la paye',
            'options' => [
                'enseignement' => 'Utiliser les centres de coûts de la composante d\'enseignement',
                'affectation'  => 'Utiliser les centres de coûts de la composante d\'affectation de l\'intervenant',
            ],
        ],
        'regle_paiement_annee_civile'    => [
            'libelle' => 'Répartition année civile antérieure / en cours',
            'options' => [
                '4-6sur10'      => 'Répartition 4/10 des heures pour l\'année antérieure, 6/10 pour l\'année en cours',
                'semestre-date' => 'En fonction du semestre des heures',
            ],
        ],
        'regle_repartition_annee_civile' => [
            'libelle' => 'Répartition des heures AA/AC dans les mises en paiement',
            'options' => [
                'prorata'      => 'Chaque mise en paiement est répartie selon le prorata AA/AC',
                'ordre-saisie' => 'Les premières mises en paiement sont considérées en AA, puis ce qui dépasse est en AC',
            ],
        ],
        'pourc_s1_pour_annee_civile'     => [
            'libelle' => 'Pour le 1er semestre, % d\'heures sur l\'année antérieure',
            'options' => '%',
        ],
        'pourc_aa_referentiel'           => [
            'libelle' => 'Pour les heures de référentiel, % d\'heures sur l\'année antérieure',
            'options' => '%',
        ],
        'distinction_fi_fa_fc'           => [
            'libelle' => 'Distinction FI/FA/FC des heures à payer',
            'options' => [
                '1' => 'Oui, distinguer la FI, la FA et la FC pour les heures à payer',
                '0' => 'Non, toutes les heures d\'enseignement seront traitées sous l label "Enseignement"',
            ],
        ],
        'horaire_nocturne'               => [
            'libelle' => 'Horaire nocturne',
            'options' => 'string',
        ],
        'taux_conges_payes'              => [
            'libelle' => 'Taux pour prise en compte des congés payés',
            'options' => '%',
        ],
        'taux-remu'                      => [
            'libelle' => 'Taux de rémunération par défaut',
            'options' => TauxRemu::class,
        ],
    ];



    public function __construct(PaiementProcess $process)
    {
        $this->process = $process;
    }



    public function run(Intervenant $intervenant)
    {
        $this->intervenant = $intervenant;
        $this->services = $this->process->debug(['intervenant_id' => $intervenant->getId()]);
    }



    public function parametres(): array
    {
        $statut = $this->intervenant->getStatut();

        $this->pg['mode']['value'] = $statut->getModeEnseignementRealise();
        $this->pg['mode']['libval'] = ucfirst($this->pg['mode']['value']);

        if ($statut->getTauxRemu()) {
            $this->pg['taux-remu']['value'] = $statut->getTauxRemu();
        }

        foreach ($this->pg as $nom => $pg) {
            if (!isset($pg['value'])) {
                $this->pg[$nom]['value'] = $this->process->getServiceParametres()->get($nom);
            }
            if (!isset($pg['libval'])) {
                $value = $this->pg[$nom]['value'];

                $libval = (string)$value;
                if (is_array($pg['options'])) {
                    $libval = $pg['options'][$value];
                }
                if ($pg['options'] == 'string') {
                    $libval = $value;
                }
                if ($pg['options'] == '%') {
                    $libval = ((float)$value * 100) . ' %';
                }
                if (is_string($pg['options']) && class_exists($pg['options'])) {
                    $libval = (string)$this->getEntity($pg['options'], $value);
                }

                $this->pg[$nom]['libval'] = $libval;
            }
        }

        $visibility = [
            'mode'                           => $statut->getServiceRealise() || $statut->getReferentielRealise(),
            'centres_couts_paye'             => true,
            'regle_paiement_annee_civile'    => $statut->getServiceRealise() || $statut->getReferentielRealise(),
            'regle_repartition_annee_civile' => $statut->getServiceRealise() || $statut->getReferentielRealise(),
            'pourc_s1_pour_annee_civile'     => $statut->getServiceRealise() && 'semestriel' == $this->pg['mode']['value'],
            'pourc_aa_referentiel'           => $statut->getServiceRealise() && 'semestriel' == $this->pg['mode']['value'],
            'distinction_fi_fa_fc'           => $statut->getServiceRealise(),
            'horaire_nocturne'               => $statut->getMission(),
            'taux_conges_payes'              => $statut->getMission(),
            'taux-remu'                      => $statut->getServiceRealise() || $statut->getReferentielRealise(),
        ];

        $parametres = [];
        foreach ($this->pg as $nom => $pg) {
            if ($visibility[$nom]) {
                $parametres[$pg['libelle']] = $pg['libval'];
            }
        }

        return $parametres;
    }



    public function servicesAPayer(): array
    {
        $saps = [];

        foreach ($this->services as $service) {
            $s = [
                'parametres' => [],
            ];

            $inutile = $service->heures == null;

            if ($service->service) {
                $s['type'] = 'Enseignement';

                /** @var Service $ss */
                $sEntity = $this->getEntity(Service::class, $service->service);

                if ($sEntity->getElementPedagogique()) {
                    $s['libelle'] = $sEntity->getElementPedagogique()->getEtape() . ' ==> ' . $sEntity->getElementPedagogique();
                } else {
                    $s['libelle'] = $sEntity->getEtablissement() . ' ==> ' . $sEntity->getDescription();
                }
            }
            if ($service->serviceReferentiel) {
                $s['type'] = 'Référentiel';
                $s['libelle'] = (string)$this->getEntity(ServiceReferentiel::class, $service->serviceReferentiel)->getFonctionReferentiel();
            }
            if ($service->mission) {
                $s['type'] = 'Mission';
                $s['libelle'] = (string)$this->getEntity(Mission::class, $service->mission);
            }

            $s['parametres']['Structure'] = (string)$this->getEntity(Structure::class, $service->structure);
            $s['parametres']['Type d\'heures'] = (string)$this->getEntity(TypeHeures::class, $service->typeHeures);

            if ($service->defCentreCout) {
                $s['parametres']['Centre de coûts par défaut'] = (string)$this->getEntity(CentreCout::class, $service->defCentreCout);
            }

            if ($service->defDomaineFonctionnel) {
                $s['parametres']['Domaine fonctionnel par défaut'] = (string)$this->getEntity(DomaineFonctionnel::class, $service->defDomaineFonctionnel);
            }

            if (1.0 !== $service->tauxCongesPayes) {
                $s['parametres']['Taux de congés payés'] = floatToString(($service->tauxCongesPayes - 1) * 100) . ' %';
            }

            $s['laps'] = [];

            foreach ($service->lignesAPayer as $lap) {
                if ($lap->heuresAA + $lap->heuresAC !== 0){
                    $inutile = false;
                }

                $l = $lap->toArray();
                if (!isset($l['misesEnPaiement'])) {
                    $l['misesEnPaiement'] = [];
                }
                $l['volumeHoraire'] = '';
                $l['volumeHoraireHisto'] = '';
                if ($l['volumeHoraireId']) {
                    if ($service->service) {
                        $entity = $this->getEntity(VolumeHoraire::class, $l['volumeHoraireId']);
                        $l['volumeHoraire'] = $this->vhLibelle($entity);
                    }
                    if ($service->mission) {
                        $entity = $this->getEntity(VolumeHoraireMission::class, $l['volumeHoraireId']);
                        $l['volumeHoraire'] = $this->vhLibelle($entity);
                    }
                    if ($service->serviceReferentiel){
                        $entity = $this->getEntity(VolumeHoraireReferentiel::class, $l['volumeHoraireId']);
                        $l['volumeHoraire'] = $this->vhLibelle($entity);
                    }
                    if ($entity){
                        /** @var $entity HistoriqueAwareInterface */
                        $l['volumeHoraireHisto'] = 'Dernière modification par '.$entity->getHistoModificateur().' le '.$entity->getHistoModification()->format('d/m/Y');
                    }
                }
                $l['tauxRemu'] = (string)$this->getEntity(TauxRemu::class, $l['tauxRemu']);
                $l['tauxValeur'] = $this->fts($l['tauxValeur']);
                $l['heuresRestantes'] = $l['heuresAA'] + $l['heuresAC'];
                $l['heures'] = $this->fts(($l['heuresAA'] + $l['heuresAC']) / 100);
                $l['heuresAA'] = $this->fts($l['heuresAA'] / 100);
                $l['heuresAC'] = $this->fts($l['heuresAC'] / 100);
                foreach ($l['misesEnPaiement'] as $i => $m) {
                    /** @var CentreCout $centreCouts */
                    $centreCouts = $this->getEntity(CentreCout::class, $m['centreCout']);

                    $mepEntity = $this->getEntity(MiseEnPaiement::class,$m['id']);
                    $m['heures'] = $this->fts(($m['heuresAA'] + $m['heuresAC']) / 100);
                    $l['heuresRestantes'] = $l['heuresRestantes'] - $m['heuresAA'] - $m['heuresAC'];
                    $m['heuresAA'] = $this->fts($m['heuresAA'] / 100);
                    $m['heuresAC'] = $this->fts($m['heuresAC'] / 100);
                    $m['date'] = $m['date'] ? \DateTime::createFromFormat('Y-m-d', $m['date'])->format('d/m/Y') : null;
                    $m['periodePaiement'] = (string)$this->getEntity(Periode::class, $m['periodePaiement']);
                    $m['centreCoutCode'] = $centreCouts?->getCode();
                    $m['centreCoutLibelle'] = $centreCouts?->getLibelle();
                    $m['domaineFonctionnel'] = (string)$this->getEntity(DomaineFonctionnel::class, $m['domaineFonctionnel']);
                    $m['heuresTotal'] = $mepEntity->getHeures();
                    $m['historique'] = 'Dernière modification par '.$mepEntity->getHistoModificateur().' le '.$mepEntity->getHistoModification()->format('d/m/Y');
                    $l['misesEnPaiement'][$i] = $m;
                }
                $l['heuresRestantes'] = $this->fts($l['heuresRestantes'] / 100);
                $s['laps'][] = $l;
            }

            $s['misesEnPaiement'] = [];

            foreach ($service->misesEnPaiement as $mep) {
                $inutile = false;
                $m = $mep->toArray();

                /** @var CentreCout $centreCouts */
                $centreCouts = $this->getEntity(CentreCout::class, $m['centreCout']);
                $mepEntity = $this->getEntity(MiseEnPaiement::class,$m['id']);
                $m['heures'] = $this->fts(($m['heuresAA']+$m['heuresAC']) / 100);
                $m['date'] = $m['date'] ? \DateTime::createFromFormat('Y-m-d', $m['date'])->format('d/m/Y') : null;
                $m['periodePaiement'] = (string)$this->getEntity(Periode::class, $m['periodePaiement']);
                $m['centreCoutCode'] = $centreCouts?->getCode();
                $m['centreCoutLibelle'] = $centreCouts?->getLibelle();
                $m['domaineFonctionnel'] = (string)$this->getEntity(DomaineFonctionnel::class, $m['domaineFonctionnel']);
                $m['historique'] = 'Dernière modification par '.$mepEntity->getHistoModificateur().' le '.$mepEntity->getHistoModification()->format('d/m/Y');
                $s['misesEnPaiement'][] = $m;
            }

            if (!$inutile) {
                $saps[] = $s;
            }
        }

        return $saps;
    }



    protected function getEntity(string $class, mixed $id): mixed
    {
        if (!$id) {
            return null;
        }

        return $this->process->getServiceBdd()->getEntityManager()->find($class, $id);
    }



    protected function vhLibelle(VolumeHoraire|VolumeHoraireReferentiel|VolumeHoraireMission $volumeHoraire): string
    {
        $props = [];

        if ($volumeHoraire instanceof VolumeHoraire) {
            $lib = $volumeHoraire->getHeures() . ' heures ' . $volumeHoraire->getTypeIntervention() . ' au ' . $volumeHoraire->getPeriode()->getLibelleCourt();
            if ($volumeHoraire->getHoraireDebut() && !$volumeHoraire->getHoraireFin()){
                $lib .= ' à partir du ' . $volumeHoraire->getHoraireDebut()->format('d/m/Y à H:i');
            }
            if (!$volumeHoraire->getHoraireDebut() && $volumeHoraire->getHoraireFin()){
                $lib .= ' jusqu\'au ' . $volumeHoraire->getHoraireFin()->format('d/m/Y à H:i');
            }

            if ($volumeHoraire->getHoraireDebut() && $volumeHoraire->getHoraireFin()) {
                $dateDebut = $volumeHoraire->getHoraireDebut()->format('d/m/Y');
                $heureDebut = $volumeHoraire->getHoraireDebut()->format('H:i');
                $dateFin = $volumeHoraire->getHoraireFin()->format('d/m/Y');
                $heureFin = $volumeHoraire->getHoraireFin()->format('H:i');

                if ($dateDebut == $dateFin){
                    $lib .= " le $dateDebut de $heureDebut à $heureFin";
                }else{
                    $lib.= " du $dateDebut à $heureDebut au $dateFin à $heureFin";
                }
            }

            return $lib;

        }
        if ($volumeHoraire instanceof VolumeHoraireReferentiel) {
            return $volumeHoraire->getHeures() . ' heures';
        }
        if ($volumeHoraire instanceof VolumeHoraireMission) {
            return 'Le ' . $volumeHoraire->getHoraireDebut()->format(Constants::DATE_FORMAT)
                . ' de ' . $volumeHoraire->getHeureDebut()
                . ' à ' . $volumeHoraire->getHeureFin();
        }

        return '';
    }



    protected function fts(float $value): string
    {
        return number_format($value, 2, ',', ' ');
    }
}