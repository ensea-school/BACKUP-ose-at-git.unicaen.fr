<?php

namespace Workflow\Service;

use Dompdf\Dompdf;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\Statut;
use Laminas\View\Renderer\PhpRenderer;
use Unicaen\BddAdmin\Bdd;

/**
 * État des lieux d'un intervenant, au format PDF, destiné à l'assistance : diagnostic de la feuille de route,
 * paramétrage du statut, état des données issues des tableaux de bord et écarts du paramétrage du workflow.
 *
 * Aucune donnée personnelle sensible (INSEE, coordonnées bancaires, adresse, naissance) n'y figure.
 */
class EtatLieuxIntervenantService
{
    use DiagnosticFeuilleDeRouteServiceAwareTrait;
    use RapportParametrageServiceAwareTrait;

    const RUBRIQUES_DOSSIER = [
        Statut::DONNEES_PERSONNELLES_NON_DEMANDEES              => 'Non demandée',
        Statut::DONNEES_PERSONNELLES_DEMANDEES                  => 'Avant recrutement',
        Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT => 'Après recrutement',
    ];



    public function __construct(
        private readonly PhpRenderer $renderer,
        private readonly Bdd         $bdd,
        private readonly string      $version,
    )
    {
    }



    public function genererPdf(Intervenant $intervenant): string
    {
        $html = $this->renderer->render('workflow/diagnostic/etat-lieux-pdf', ['etat' => $this->etatLieux($intervenant)]);

        $dompdf = new Dompdf(['defaultFont' => 'DejaVu Sans']);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }



    public function getNomFichier(Intervenant $intervenant): string
    {
        $nom = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', (string)$intervenant) ?: '';
        $nom = trim((string)preg_replace('/[^a-z0-9]+/i', '-', $nom), '-');

        return implode('_', array_filter([
                'etat-lieux',
                $nom,
                $intervenant->getId(),
                $intervenant->getAnnee()->getId(),
                date('Ymd-His'),
            ])) . '.pdf';
    }



    public function etatLieux(Intervenant $intervenant): array
    {
        $diagnostic = $this->getServiceDiagnosticFeuilleDeRoute()->diagnostiquer($intervenant);
        $id         = $intervenant->getId();

        $sections = [];
        foreach ([
                     'identite'    => fn() => $this->identite($id),
                     'statut'      => fn() => $this->statut($intervenant->getStatut()->getId()),
                     'dossier'     => fn() => $this->dossier($id, $intervenant->getStatut()->getId()),
                     'pj'          => fn() => $this->piecesJointes($id, $intervenant->getStatut()->getId()),
                     'service'     => fn() => $this->heures($id, 'tbl_service', 'Enseignements'),
                     'referentiel' => fn() => $this->heures($id, 'tbl_referentiel', 'Référentiel'),
                     'agrement'    => fn() => $this->agrements($id),
                     'contrat'     => fn() => $this->contrats($id),
                     'cloture'     => fn() => $this->cloture($id),
                     'paiement'    => fn() => $this->paiements($id),
                 ] as $cle => $collecte) {
            try {
                $sections[$cle] = $collecte();
            } catch (\Throwable $e) {
                // Une section en erreur ne doit pas empêcher de produire le reste de l'état des lieux
                $sections[$cle] = ['titre' => ucfirst($cle), 'erreur' => $e->getMessage()];
            }
        }

        try {
            $parametrage = $this->ecartsParametrage($diagnostic);
        } catch (\Throwable $e) {
            $parametrage = ['erreur' => $e->getMessage()];
        }

        return [
            'diagnostic'  => $diagnostic,
            'sections'    => $sections,
            'parametrage' => $parametrage,
            'version'     => $this->version,
        ];
    }



    private function identite(int $id): array
    {
        $d = $this->bdd->selectOne("
            SELECT i.id, i.code, i.source_code, s.libelle source, i.sync_statut, i.sync_structure,
                   i.irrecevable, i.validite_debut, i.validite_fin, i.export_date, i.utilisateur_code,
                   i.histo_creation, i.histo_modification, str.libelle_court structure, st.code statut_code
            FROM intervenant i
              JOIN source s ON s.id = i.source_id
              JOIN statut st ON st.id = i.statut_id
              LEFT JOIN structure str ON str.id = i.structure_id
            WHERE i.id = :intervenant", ['intervenant' => $id], null, ['case' => CASE_LOWER]);

        return [
            'titre'    => 'Identité administrative',
            'colonnes' => ['Information', 'Valeur'],
            'lignes'   => [
                ['Identifiant OSE', $d['id']],
                ['Code intervenant', $d['code'] ?? '—'],
                ['Source', $d['source'] . ($d['source_code'] ? ' (code source ' . $d['source_code'] . ')' : '')],
                ['Structure d\'affectation', $d['structure'] ?? 'Aucune'],
                ['Code du statut', $d['statut_code']],
                ['Statut synchronisé avec la source', $d['sync_statut'] ? 'Oui' : 'Non'],
                ['Structure synchronisée avec la source', $d['sync_structure'] ? 'Oui' : 'Non'],
                ['Irrecevable', $d['irrecevable'] ? 'Oui' : 'Non'],
                ['Validité', $this->periode($d['validite_debut'], $d['validite_fin'])],
                ['Compte utilisateur associé', $d['utilisateur_code'] ? 'Oui' : 'Non'],
                ['Dernier export RH', $this->date($d['export_date']) ?? 'Jamais'],
                ['Fiche créée le', $this->date($d['histo_creation'])],
                ['Fiche modifiée le', $this->date($d['histo_modification'])],
            ],
        ];
    }



    private function statut(int $statutId): array
    {
        $s = $this->bdd->selectOne(
            'SELECT * FROM statut WHERE id = :statut',
            ['statut' => $statutId], null, ['case' => CASE_LOWER]
        );

        $activation = fn(string $col, ?string $visu = null) => [
            !empty($s[$col]) ? 'Activé' : 'Désactivé',
            $visu ? (!empty($s[$visu]) ? 'Oui' : 'Non') : '—',
        ];

        $lignes = [
            ['Données personnelles', ...$activation('dossier', 'dossier_visualisation')],
        ];
        foreach ([
                     'dossier_statut'        => 'Statut',
                     'dossier_identite_comp' => 'Identité complémentaire',
                     'dossier_contact'       => 'Contact',
                     'dossier_adresse'       => 'Adresse',
                     'dossier_banque'        => 'Banque',
                     'dossier_insee'         => 'Numéro INSEE',
                     'dossier_employeur'     => 'Employeur',
                     ...$this->champsAutres(),
                 ] as $col => $libelle) {
            $lignes[] = ['   · ' . $libelle, self::RUBRIQUES_DOSSIER[(int)($s[$col] ?? 0)] ?? (string)$s[$col], '—'];
        }

        array_push($lignes,
            ['Pièces justificatives', ...$activation('pj_active', 'pj_visualisation')],
            ['Postuler à une offre d\'emploi', ...$activation('offre_emploi_postuler')],
            ['Enseignements prévisionnels', ...$activation('service_prevu', 'service_prevu_visu')],
            ['Enseignements réalisés', ...$activation('service_realise', 'service_realise_visu')],
            ['Référentiel prévisionnel', ...$activation('referentiel_prevu', 'referentiel_prevu_visu')],
            ['Référentiel réalisé', ...$activation('referentiel_realise', 'referentiel_realise_visu')],
            ['Missions', ...$activation('mission', 'mission_visualisation')],
            ['Indemnités de fin de contrat', ...$activation('mission_indemnitees')],
            ['Conseil restreint', ...$activation('conseil_restreint', 'conseil_restreint_visu')],
            ['Conseil académique', ...$activation('conseil_aca', 'conseil_aca_visualisation')],
            ['Contrat', ...$activation('contrat', 'contrat_visualisation')],
            ['Export vers le logiciel RH', ...$activation('export_rh')],
            ['Clôture du service réalisé', ...$activation('cloture')],
            ['Paiement', ...$activation('paiement', 'paiement_visualisation')],
            ['Service statutaire', $this->nombre($s['service_statutaire'] ?? 0) . ' h', '—'],
            ['Mode de saisie des enseignements prévisionnels', (string)($s['mode_enseignement_previsionnel'] ?? '—'), '—'],
            ['Mode de saisie des enseignements réalisés', (string)($s['mode_enseignement_realise'] ?? '—'), '—'],
        );

        return [
            'titre'    => 'Paramétrage du statut « ' . $s['libelle'] . ' »',
            'colonnes' => ['Option', 'Valeur', 'Visible par l\'intervenant'],
            'lignes'   => $lignes,
        ];
    }



    /**
     * Données personnelles : pour chaque rubrique, le moment où le statut la demande
     * (avant recrutement = étape « Données personnelles », après = étape « Données personnelles complémentaires »)
     * et sa complétude.
     */
    private function dossier(int $id, int $statutId): array
    {
        $titre = 'Données personnelles';
        $d     = $this->bdd->selectOne(
            'SELECT * FROM tbl_dossier WHERE intervenant_id = :intervenant AND actif = 1',
            ['intervenant' => $id], null, ['case' => CASE_LOWER]
        );
        if (!$d) {
            return ['titre' => $titre, 'vide' => 'Aucune donnée personnelle attendue pour cet intervenant.'];
        }
        $s = $this->bdd->selectOne(
            'SELECT * FROM statut WHERE id = :statut',
            ['statut' => $statutId], null, ['case' => CASE_LOWER]
        ) ?? [];

        $moment   = fn(string $col) => self::RUBRIQUES_DOSSIER[(int)($s[$col] ?? 0)] ?? (string)$s[$col];
        $demandee = fn(string $col) => (int)($s[$col] ?? 0) !== Statut::DONNEES_PERSONNELLES_NON_DEMANDEES;
        $etat     = fn(string $col, bool $demandee) => $demandee ? (!empty($d[$col]) ? 'Complète' : 'Incomplète') : '—';

        $lignes = [['Identité', 'Avant recrutement (toujours)', $etat('completude_identite', true)]];
        foreach ([
                     'statut'        => 'Statut',
                     'identite_comp' => 'Identité complémentaire',
                     'contact'       => 'Contact',
                     'adresse'       => 'Adresse',
                     'insee'         => 'Numéro INSEE',
                     'banque'        => 'Banque',
                     'employeur'     => 'Employeur',
                 ] as $bloc => $libelle) {
            $lignes[] = [$libelle, $moment('dossier_' . $bloc), $etat('completude_' . $bloc, $demandee('dossier_' . $bloc))];
        }

        // La complétude des champs autres n'est calculée que globalement, par moment de demande
        foreach ($this->champsAutres() as $col => $libelle) {
            if (!$demandee($col)) {
                continue;
            }
            $apres    = (int)$s[$col] === Statut::DONNEES_PERSONNELLES_DEMANDEES_POST_RECRUTEMENT;
            $lignes[] = [
                $libelle,
                $moment($col),
                $etat($apres ? 'completude_autre_apres_recrutement' : 'completude_autre_avant_recrutement', true)
                . ' (champs autres ' . ($apres ? 'après' : 'avant') . ' recrutement)',
            ];
        }

        array_push($lignes,
            ['Bilan avant recrutement', 'Étape « Données personnelles »',
             $this->nombre($d['avant_recrutement_realisee']) . ' rubrique(s) complète(s) sur ' . $this->nombre($d['avant_recrutement_attendue'])],
            ['Bilan après recrutement', 'Étape « Données personnelles complémentaires »',
             $this->nombre($d['apres_recrutement_realisee']) . ' rubrique(s) complète(s) sur ' . $this->nombre($d['apres_recrutement_attendue'])],
            ['Validation des données avant recrutement', '—', $d['validation_id'] ? 'Validées' : 'Non validées'],
            ['Validation des données après recrutement', '—', $d['validation_complementaire_id'] ? 'Validées' : 'Non validées'],
        );

        return ['titre' => $titre, 'colonnes' => ['Rubrique', 'Demandée', 'État'], 'lignes' => $lignes];
    }



    /**
     * @return array<string, string> colonne du statut => libellé du champ autre
     */
    private function champsAutres(): array
    {
        $libelles = [];
        foreach ($this->bdd->select('SELECT id, libelle FROM dossier_champ_autre', [], ['case' => CASE_LOWER]) as $r) {
            $libelles[(int)$r['id']] = $r['libelle'];
        }
        $champs = [];
        for ($i = 1; $i <= 5; $i++) {
            $champs['dossier_autre_' . $i] = 'Champ autre ' . $i . (isset($libelles[$i]) ? ' « ' . $libelles[$i] . ' »' : '');
        }

        return $champs;
    }



    /**
     * Pièces justificatives : le paramétrage du statut (moment de la demande, conditions)
     * croisé avec l'état calculé pour l'intervenant, y compris les pièces paramétrées mais non demandées.
     */
    private function piecesJointes(int $id, int $statutId): array
    {
        $parametrage = $this->bdd->select('
            SELECT tpj.id, tpj.libelle, tpj.ordre, tpjs.demandee_apres_recrutement, tpjs.obligatoire, tpjs.seuil_hetd,
                   tpjs.type_heure_hetd, tpjs.changement_rib, tpjs.nationalite_etrangere, tpjs.fc, tpjs.fa, tpjs.duree_vie
            FROM type_piece_jointe_statut tpjs
              JOIN type_piece_jointe tpj ON tpj.id = tpjs.type_piece_jointe_id AND tpj.histo_destruction IS NULL
            WHERE tpjs.statut_id = :statut AND tpjs.histo_destruction IS NULL', ['statut' => $statutId], ['case' => CASE_LOWER]);

        $etats = [];
        foreach ($this->bdd->select('
            SELECT tpj.id, tpj.libelle, tpj.ordre, pj.demandee, pj.demandee_apres_recrutement, pj.obligatoire, pj.fournie, pj.validee, pj.date_validitee
            FROM tbl_piece_jointe pj
              JOIN type_piece_jointe tpj ON tpj.id = pj.type_piece_jointe_id
            WHERE pj.intervenant_id = :intervenant', ['intervenant' => $id], ['case' => CASE_LOWER]) as $r) {
            $etats[$r['id']] = $r;
        }

        $pieces = [];
        foreach ($parametrage as $p) {
            $pieces[$p['id']] = ['param' => $p, 'etat' => $etats[$p['id']] ?? null];
        }
        foreach ($etats as $tpjId => $e) {
            $pieces[$tpjId] ??= ['param' => null, 'etat' => $e];
        }
        uasort($pieces, fn($a, $b) => [($a['param'] ?? $a['etat'])['demandee_apres_recrutement'], ($a['param'] ?? $a['etat'])['ordre']]
            <=> [($b['param'] ?? $b['etat'])['demandee_apres_recrutement'], ($b['param'] ?? $b['etat'])['ordre']]);

        $lignes = [];
        foreach ($pieces as $piece) {
            $p = $piece['param'];
            $e = $piece['etat'];
            $r = $p ?? $e;

            $conditions = [];
            if ($p) {
                if ((int)$p['seuil_hetd'] > 0) {
                    $conditions[] = 'au-delà de ' . $this->nombre($p['seuil_hetd']) . ' h' . ((int)$p['type_heure_hetd'] ? ' HETD' : '');
                }
                if ((int)$p['changement_rib']) {
                    $conditions[] = 'si changement de RIB';
                }
                if ((int)$p['nationalite_etrangere']) {
                    $conditions[] = 'si nationalité étrangère';
                }
                if ((int)$p['fc']) {
                    $conditions[] = 'si heures en FC';
                }
                if ((int)$p['fa']) {
                    $conditions[] = 'si heures en FA';
                }
                if ((int)$p['duree_vie'] > 1) {
                    $conditions[] = 'valable ' . $p['duree_vie'] . ' ans';
                }
            } else {
                $conditions[] = 'plus paramétrée pour le statut';
            }

            $lignes[] = [
                $r['libelle'],
                (int)$r['demandee_apres_recrutement'] ? 'Après recrutement' : 'Avant recrutement',
                implode(', ', $conditions) ?: '—',
                !empty($e['demandee']) ? 'Oui' : 'Non',
                !empty($e['obligatoire'] ?? $p['obligatoire']) ? 'Oui' : 'Non',
                $e ? ($e['fournie'] ? 'Oui' : 'Non') : '—',
                $e ? ($e['validee'] ? 'Oui' : 'Non') : '—',
                // date_validitee est une année : la pièce reste valable pour les années universitaires antérieures
                !empty($e['date_validitee']) ? 'jusqu\'en ' . ((int)$e['date_validitee'] - 1) . '/' . (int)$e['date_validitee'] : '—',
            ];
        }

        return [
            'titre'    => 'Pièces justificatives',
            'note'     => 'Moment : les pièces « avant recrutement » sont demandées à l\'étape des pièces justificatives, '
                . 'celles « après recrutement » plus tard, à l\'étape des pièces justificatives complémentaires. '
                . 'Demandée à l\'intervenant : une pièce paramétrée pour le statut n\'est demandée que si ses conditions sont remplies '
                . '(par exemple un seuil d\'heures atteint) ; sinon, « Non ».',
            'colonnes' => ['Pièce', 'Moment', 'Conditions', 'Demandée à l\'intervenant', 'Obligatoire', 'Fournie', 'Validée', 'Validité'],
            'lignes'   => $lignes,
            'vide'     => 'Aucune pièce justificative paramétrée pour ce statut.',
        ];
    }



    private function heures(int $id, string $table, string $titre): array
    {
        $rows = $this->bdd->select("
            SELECT tvh.libelle type_vh, tvh.ordre, str.libelle_court structure,
                   SUM(t.heures) heures, SUM(t.nbvh) nbvh, SUM(t.valide) valide
            FROM $table t
              JOIN type_volume_horaire tvh ON tvh.id = t.type_volume_horaire_id
              LEFT JOIN structure str ON str.id = t.structure_id
            WHERE t.intervenant_id = :intervenant AND t.actif = 1
            GROUP BY tvh.libelle, tvh.ordre, str.libelle_court
            ORDER BY tvh.ordre, str.libelle_court", ['intervenant' => $id], ['case' => CASE_LOWER]);

        return [
            'titre'    => $titre,
            'colonnes' => ['Type', 'Composante', 'Heures', 'Volumes horaires validés'],
            'lignes'   => array_map(fn($r) => [
                $r['type_vh'],
                $r['structure'] ?? 'Hors établissement',
                $this->nombre($r['heures']),
                $this->nombre($r['valide']) . ' / ' . $this->nombre($r['nbvh']),
            ], $rows),
            'vide'     => 'Aucune heure saisie.',
        ];
    }



    private function agrements(int $id): array
    {
        $rows = $this->bdd->select('
            SELECT ta.libelle, str.libelle_court structure, a.agrement_id, a.annee_agrement, a.duree_vie
            FROM tbl_agrement a
              JOIN type_agrement ta ON ta.id = a.type_agrement_id
              LEFT JOIN structure str ON str.id = a.structure_id
            WHERE a.intervenant_id = :intervenant
            ORDER BY ta.libelle, str.libelle_court', ['intervenant' => $id], ['case' => CASE_LOWER]);

        return [
            'titre'    => 'Agréments',
            'colonnes' => ['Agrément', 'Composante', 'État', 'Durée de vie'],
            'lignes'   => array_map(fn($r) => [
                $r['libelle'],
                $r['structure'] ?? 'Établissement',
                $r['agrement_id'] ? 'Obtenu' . ($r['annee_agrement'] ? ' en ' . $r['annee_agrement'] . '/' . ($r['annee_agrement'] + 1) : '') : 'En attente',
                $r['duree_vie'] ? $r['duree_vie'] . ' an(s)' : '—',
            ], $rows),
            'vide'     => 'Aucun agrément attendu.',
        ];
    }



    private function contrats(int $id): array
    {
        $rows = $this->bdd->select('
            SELECT tc.libelle type_contrat, c.numero_avenant, str.libelle_court structure, c.date_debut, c.date_fin,
                   c.total_heures, c.contrat_id, c.validation_id, c.edite, c.signe, c.termine
            FROM tbl_contrat c
              LEFT JOIN type_contrat tc ON tc.id = c.type_contrat_id
              LEFT JOIN structure str ON str.id = c.structure_id
            WHERE c.intervenant_id = :intervenant AND c.actif = 1 AND c.volume_horaire_index = 0
            ORDER BY str.libelle_court, c.numero_avenant', ['intervenant' => $id], ['case' => CASE_LOWER]);

        return [
            'titre'    => 'Contrats et avenants',
            'colonnes' => ['Contrat', 'Composante', 'Période', 'Heures', 'Avancement'],
            'lignes'   => array_map(fn($r) => [
                ($r['type_contrat'] ?? 'Contrat') . ($r['numero_avenant'] ? ' (avenant ' . $r['numero_avenant'] . ')' : ''),
                $r['structure'] ?? '—',
                $this->periode($r['date_debut'], $r['date_fin']),
                $this->nombre($r['total_heures']),
                $r['contrat_id'] ? implode(', ', array_filter([
                    $r['validation_id'] ? 'validé' : 'non validé',
                    $r['edite'] ? 'édité' : null,
                    $r['signe'] ? 'signé' : null,
                    $r['termine'] ? 'terminé' : null,
                ])) : 'À créer',
            ], $rows),
            'vide'     => 'Aucun contrat attendu.',
        ];
    }



    private function cloture(int $id): array
    {
        $d = $this->bdd->selectOne(
            'SELECT cloture FROM tbl_cloture_realise WHERE intervenant_id = :intervenant AND actif = 1',
            ['intervenant' => $id], null, ['case' => CASE_LOWER]
        );

        return [
            'titre'    => 'Clôture du service réalisé',
            'colonnes' => ['Information', 'Valeur'],
            'lignes'   => $d ? [['Service réalisé clôturé', $d['cloture'] ? 'Oui' : 'Non']] : [],
            'vide'     => 'Clôture non demandée pour cet intervenant.',
        ];
    }



    private function paiements(int $id): array
    {
        $d = $this->bdd->selectOne('
            SELECT COUNT(*) nb,
                   SUM(heures_a_payer_aa + heures_a_payer_ac) a_payer,
                   SUM(heures_demandees_aa + heures_demandees_ac) demandees,
                   SUM(heures_payees_aa + heures_payees_ac) payees
            FROM tbl_paiement WHERE intervenant_id = :intervenant', ['intervenant' => $id], null, ['case' => CASE_LOWER]);

        return [
            'titre'    => 'Paiements',
            'colonnes' => ['Heures (HETD)', 'Total'],
            'lignes'   => (int)($d['nb'] ?? 0) ? [
                ['À payer', $this->nombre($d['a_payer'])],
                ['Demandées en paiement', $this->nombre($d['demandees'])],
                ['Payées', $this->nombre($d['payees'])],
            ] : [],
            'vide'     => 'Aucune heure à payer.',
        ];
    }



    /**
     * Écarts du paramétrage du workflow de l'établissement par rapport au standard, et anomalies,
     * limités aux étapes prévues pour l'intervenant.
     */
    private function ecartsParametrage(array $diagnostic): array
    {
        $prevues = [];
        foreach ($diagnostic['etapes'] as $etape) {
            if ($etape['activation']['prevue']) {
                $prevues[$etape['libelle']] = true;
            }
        }

        $rapport = $this->getServiceRapportParametrage()->analyser();

        return [
            'ecarts'    => array_values(array_filter($rapport['ecarts'], fn($e) => isset($prevues[$e['etape']]))),
            'anomalies' => array_values(array_filter($rapport['anomalies'], fn($a) => isset($prevues[$a['etape']]))),
            'total'     => $rapport['stats']['ecarts'],
        ];
    }



    /* Utilitaires */



    private function nombre(mixed $valeur): string
    {
        $valeur = (float)$valeur;

        return number_format($valeur, floor($valeur) == $valeur ? 0 : 2, ',', ' ');
    }



    private function date(mixed $valeur, bool $heure = true): ?string
    {
        if (!$valeur) {
            return null;
        }
        try {
            return (new \DateTime((string)$valeur))->format($heure ? 'd/m/Y à H:i' : 'd/m/Y');
        } catch (\Exception) {
            return (string)$valeur;
        }
    }



    private function periode(mixed $debut, mixed $fin): string
    {
        $debut = $this->date($debut, false);
        $fin   = $this->date($fin, false);

        return match (true) {
            $debut && $fin => "du $debut au $fin",
            (bool)$debut   => "à partir du $debut",
            (bool)$fin     => "jusqu'au $fin",
            default        => '—',
        };
    }
}
