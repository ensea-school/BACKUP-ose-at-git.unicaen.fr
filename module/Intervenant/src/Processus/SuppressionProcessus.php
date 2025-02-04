<?php

namespace Intervenant\Processus;

use Application\Model\TreeNode;
use Application\Traits\TranslatorTrait;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;


class SuppressionProcessus
{
    use EntityManagerAwareTrait;
    use IntervenantAwareTrait;
    use TranslatorTrait;


    protected $queries = [

        '.INTERVENANT' => "
SELECT
  1                              visible,
  null                           parent_id,
  i.id                           id,
  null                           categorie,
  i.prenom || ' ' || i.nom_usuel || ' (' || si.libelle || ', ' || a.libelle || ')'         label,
  'fas fa-user' icon
FROM
  intervenant i
  JOIN statut si ON si.id = i.statut_id 
  JOIN annee a ON a.id = i.annee_id
WHERE 
  i.id IN (:id)
        ",


        'INTERVENANT.INTERVENANT_DOSSIER' => "
SELECT
  CASE WHEN d.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  d.intervenant_id               parent_id,
  d.id                           id,
  null                           categorie,
  'Données personnelles'         label,
  'fas fa-user' icon
FROM
  intervenant_dossier d
WHERE 
  d.intervenant_id IN (:id)
        ",


        'INTERVENANT.PIECE_JOINTE' => "
SELECT
  CASE WHEN pj.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  pj.intervenant_id             parent_id,
  pj.id                         id,
  'Pièces justificatives'       categorie,
  tpj.libelle                   label,
 'fas fa-envelope' icon
FROM
  piece_jointe pj
  JOIN type_piece_jointe tpj ON tpj.id = pj.type_piece_jointe_id
WHERE
  pj.intervenant_id IN (:id)
ORDER BY
  tpj.ordre
        ",


        'INTERVENANT.MODIFICATION_SERVICE_DU' => "
SELECT
  CASE WHEN msd.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  msd.intervenant_id                  parent_id,
  msd.id                              id,
  'Modifications de service dû'       categorie,
  msd.heures * mss.multiplicateur || ' heures pour ' || mss.libelle label,
  'fas fa-fill'    icon
FROM
  MODIFICATION_SERVICE_DU msd
  JOIN motif_modification_service mss ON mss.id = msd.motif_id
WHERE
  msd.intervenant_id IN (:id)
ORDER BY
  msd.id
        ",


        'INTERVENANT.SERVICE' => "
SELECT
  CASE WHEN s.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  s.intervenant_id                parent_id,
  s.id                            id,
  'Enseignements|' || str.libelle_court categorie,
  TRIM(initcap(case WHEN ep.id IS NULL THEN 'Extérieur (' || e.libelle || ')' else ep.libelle || ' (' || ep.code || ')' END)) label,
  'fas fa-graduation-cap' icon
FROM
  service s
  JOIN etablissement e ON e.id = s.etablissement_id 
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id 
  LEFT JOIN structure str ON str.id = ep.structure_id
WHERE
  s.intervenant_id IN (:id)
ORDER BY
  ep.libelle, e.libelle
        ",


        'INTERVENANT.SERVICE_REFERENTIEL' => "
SELECT
  CASE WHEN s.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  s.intervenant_id              parent_id,
  s.id                          id,
  'Référentiel'                 categorie,
  fr.libelle_court || CASE WHEN str.id IS NULL THEN '' ELSE ' (' || str.libelle_court || ')' END label,
  'fas fa-graduation-cap' icon
FROM
  service_referentiel s
  JOIN fonction_referentiel fr ON fr.id = s.fonction_id
  LEFT JOIN structure str ON str.id = s.structure_id
WHERE
  s.intervenant_id IN (:id)
ORDER BY
  fr.libelle_court
        ",


        'INTERVENANT.MISSION' => "
SELECT
  CASE WHEN m.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  m.intervenant_id               parent_id,
  m.id                           id,
  null                           categorie,
  'Mission ' || tm.libelle || ', ' || str.libelle_court || ', du ' || to_char( m.date_debut, 'dd/mm/YYYY' ) || ' au ' || to_char( m.date_fin, 'dd/mm/YYYY' ) label,
  'fas fa-briefcase' icon
FROM
  mission m
  JOIN type_mission tm ON tm.id = m.type_mission_id
  JOIN structure str ON str.id = m.structure_id
WHERE 
  m.intervenant_id IN (:id)
        ",


        'MISSION.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  m.id                     parent_id,
  vm.validation_id         id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation_mission vm
  JOIN validation v ON v.id = vm.validation_id
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN mission m ON m.id = vm.mission_id
WHERE
  vm.mission_id IN (:id)
        ",


        'INTERVENANT.CANDIDATURE' => "
SELECT
  CASE WHEN c.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  c.intervenant_id               parent_id,
  c.id                           id,
  null                           categorie,
  'Candidature à ' || oe.titre   label,
  'fas fa-paperclip' icon
FROM
  candidature c
  JOIN offre_emploi oe ON oe.id = c.offre_emploi_id
WHERE 
  c.intervenant_id IN (:id)
        ",


        'INTERVENANT.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  v.intervenant_id         parent_id,
  tv.code                  type_validation_code,
  v.id                     id,
  'Validations'            categorie,
  tv.libelle || ' par ' || u.display_name || ' le ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code IN ('CLOTURE_REALISE', 'REFERENTIEL', 'SERVICES_PAR_COMP')
WHERE
  v.intervenant_id IN (:id)
        ",


        'INTERVENANT.AGREMENT' => "
SELECT
  CASE WHEN a.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  a.intervenant_id              parent_id,
  a.id                          id,
  'Agréments'                   categorie,
  ta.libelle || CASE WHEN s.id IS NULL THEN '' ELSE ' ' || s.libelle_court END || CASE WHEN a.date_decision IS NULL THEN '' ELSE ' (décision du ' || to_char( a.date_decision, 'dd/mm/YYYY' ) || ')' END label,
  'fas fa-circle-check'icon
FROM
  agrement a
  JOIN type_agrement ta ON ta.id = a.type_agrement_id
  LEFT JOIN structure s ON s.id = a.structure_id
WHERE
  a.intervenant_id IN (:id)
ORDER BY
  ta.code        
        ",


        'INTERVENANT.CONTRAT' => "
SELECT
  CASE WHEN c.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  c.intervenant_id              parent_id,
  c.id                          id,
  tc.libelle || 's'             categorie,
  'N°' || c.id || ', ' || COALESCE(s.libelle_court, 'établissement') || case WHEN c.date_retour_signe IS NOT NULL THEN ', retourné signé le ' || to_char( c.date_retour_signe, 'dd/mm/YYYY' ) ELSE '' END label,
  'fas fa-book'    icon
FROM
  contrat c
  JOIN type_contrat tc ON tc.id = c.type_contrat_id 
  LEFT JOIN structure s ON s.id = c.structure_id
WHERE
  c.intervenant_id IN (:id)
ORDER BY            
  c.id
        ",


        'INTERVENANT_DOSSIER.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  d.id                     parent_id,
  v.id                     id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN type_validation tv ON tv.id = v.type_validation_id AND tv.code = 'DONNEES_PERSO_PAR_COMP'
  JOIN intervenant_dossier d ON d.histo_destruction IS NULL AND d.intervenant_id = v.intervenant_id
WHERE
  d.id IN (:id)
        ",


        'MISSION.VOLUME_HORAIRE_MISSION' => "
SELECT
  CASE WHEN vhm.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vhm.mission_id            parent_id,
  vhm.id                    id,
  tvh.libelle               categorie,
  vhm.heures || ' heures ' label,
  'fas fa-calendar-days' icon
FROM
  volume_horaire_mission vhm
  JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id 
WHERE
  vhm.mission_id IN (:id)
ORDER BY
  tvh.code
        ",


        'SERVICE.VOLUME_HORAIRE' => "
SELECT
  CASE WHEN vh.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vh.service_id             parent_id,
  vh.id                     id,
  tvh.libelle               categorie,
  vh.heures || ' heures ' || ti.code || ', ' || p.libelle_court || CASE WHEN mnp.id IS NULL THEN '' ELSE ' (NP: ' || mnp.libelle_court || ')' END label,
  'fas fa-calendar-days' icon
FROM
  volume_horaire vh
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
  JOIN periode p ON p.id = vh.periode_id JOIN type_intervention ti ON ti.id = vh.type_intervention_id 
  LEFT JOIN motif_non_paiement mnp ON mnp.id = vh.motif_non_paiement_id
WHERE
  vh.service_id IN (:id)
ORDER BY
  tvh.code, p.ordre, ti.ordre
        ",


        'SERVICE.MISE_EN_PAIEMENT' => "
SELECT
  CASE WHEN mep.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  mep.service_id                  parent_id,
  mep.id                          id,
  'Mises en paiement'             categorie,
  mep.heures || 'h ' || th.libelle_court || CASE WHEN p.id IS NULL THEN '' ELSE ' (paiement en ' || p.libelle_court || ')' END label,
  'fas fa-euro-sign' icon
FROM
  mise_en_paiement mep
  JOIN type_heures th ON th.id = mep.type_heures_id
  LEFT JOIN periode p ON p.id = mep.periode_paiement_id
WHERE
  mep.service_id IN (:id)
ORDER BY
  mep.id
        ",


        'SERVICE_REFERENTIEL.VOLUME_HORAIRE_REF' => "
SELECT
  CASE WHEN vh.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vh.service_referentiel_id       parent_id,
  vh.id                           id,
  tvh.libelle                     categorie,
  vh.heures || 'h'                label,
  'fas fa-calendar-days' icon
FROM
  volume_horaire_ref vh
  JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id 
WHERE
  vh.service_referentiel_id IN (:id)
ORDER BY
  tvh.code
        ",


        'SERVICE_REFERENTIEL.MISE_EN_PAIEMENT' => "
SELECT
  CASE WHEN mep.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  mep.service_referentiel_id      parent_id,
  mep.id                          id,
  'Mises en paiement'             categorie,
  mep.heures || 'h ' || CASE WHEN p.id IS NULL THEN '' ELSE ' (paiement en ' || p.libelle_court || ')' END label,
  'fas fa-euro-sign' icon
FROM
  mise_en_paiement mep
  LEFT JOIN periode p ON p.id = mep.periode_paiement_id
WHERE
  mep.service_referentiel_id IN (:id)
ORDER BY
  mep.id
        ",


        'PIECE_JOINTE.FICHIER' => "
SELECT
  CASE WHEN f.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  pjf.piece_jointe_id             parent_id,
  f.id                            id,
  null                            categorie,
  'Fichier: ' || f.nom            label,
  'fas fa-file'      icon
FROM
  fichier f
  JOIN piece_jointe_fichier pjf ON pjf.fichier_id = f.id
WHERE
  pjf.piece_jointe_id IN (:id)
ORDER BY
  f.nom
        ",


        'PIECE_JOINTE.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  pj.id                    parent_id,
  v.id                     id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN piece_jointe pj ON pj.validation_id = v.id
WHERE
  pj.id IN (:id)
        ",


        'CONTRAT.FICHIER' => "
SELECT
  CASE WHEN f.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  cf.contrat_id                   parent_id,
  f.id                            id,
  null                            categorie,
  'Fichier: ' || f.nom            label,
  'fas fa-file'      icon
FROM
  fichier f
  JOIN contrat_fichier cf ON cf.fichier_id = f.id
WHERE
  cf.contrat_id IN (:id)
ORDER BY
  f.nom
        ",


        'CONTRAT.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  c.id                     parent_id,
  v.id                     id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN contrat c ON c.validation_id = v.id
WHERE
  c.id IN (:id)
        ",


        'VOLUME_HORAIRE.VALIDATION_VOL_HORAIRE' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vvh.volume_horaire_id    parent_id,
  vvh.volume_horaire_id    volume_horaire_id,
  vvh.validation_id        validation_id,
  '[VOLUME_HORAIRE_ID:' || vvh.VOLUME_HORAIRE_ID || ',VALIDATION_ID:' || vvh.validation_id || ']' id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN validation_vol_horaire vvh ON vvh.validation_id = v.id
WHERE
  vvh.volume_horaire_id IN (:id)
        ",


        'VOLUME_HORAIRE_REF.VALIDATION_VOL_HORAIRE_REF' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vvh.volume_horaire_ref_id parent_id,
  vvh.volume_horaire_ref_id volume_horaire_ref_id,
  vvh.validation_id         validation_id,
  '[VOLUME_HORAIRE_REF_ID:' || vvh.VOLUME_HORAIRE_REF_ID || ',VALIDATION_ID:' || vvh.validation_id || ']' id,
  null                      categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check'  icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN validation_vol_horaire_ref vvh ON vvh.validation_id = v.id
WHERE
  vvh.volume_horaire_ref_id IN (:id)
        ",


        'VOLUME_HORAIRE_MISSION.VALIDATION_VOL_HORAIRE_MISS' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  vvhm.volume_horaire_mission_id parent_id,
  vvhm.volume_horaire_mission_id volume_horaire_mission_id,
  vvhm.validation_id             validation_id,
  '[VOLUME_HORAIRE_MISSION_ID:' || vvhm.VOLUME_HORAIRE_MISSION_ID || ',VALIDATION_ID:' || vvhm.validation_id || ']' id,
  null                      categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check'  icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN validation_vol_horaire_miss vvhm ON vvhm.validation_id = v.id
WHERE
 vvhm.volume_horaire_mission_id IN (:id)
        ",


        'FICHIER.VALIDATION' => "
SELECT
  CASE WHEN v.histo_destruction IS NULL THEN 1 ELSE 0 END visible,
  f.id                     parent_id,
  v.id                     id,
  null                     categorie,
  'Validation du ' || to_char( v.histo_creation, 'dd/mm/YYYY \"à\" HH24:MI' ) || ' par ' || u.display_name label,
  'fas fa-check' icon
FROM
  validation v
  JOIN utilisateur u ON u.id = v.histo_createur_id
  JOIN fichier f ON f.validation_id = v.id
WHERE
  f.id IN (:id)
        ",
    ];

    protected $delete = [
        '.MISE_EN_PAIEMENT'            => [],
        'CONTRAT.VALIDATION'           => ['queries' => [
            'UPDATE CONTRAT SET VALIDATION_ID = NULL WHERE validation_id = :ID',
        ]],
        'CONTRAT.FICHIER'              => [],
        '.CONTRAT'                     => ['queries' => [
            'UPDATE VOLUME_HORAIRE SET contrat_id = null WHERE contrat_id = :ID',
            'UPDATE contrat SET CONTRAT_ID = NULL WHERE contrat_id = :ID',
        ]],
        '.AGREMENT'                    => [],
        '.VALIDATION'                  => [],
        '.VALIDATION_VOL_HORAIRE'      => ['key' => ['VALIDATION_ID', 'VOLUME_HORAIRE_ID']],
        '.VALIDATION_VOL_HORAIRE_REF'  => ['key' => ['VALIDATION_ID', 'VOLUME_HORAIRE_REF_ID']],
        '.VALIDATION_VOL_HORAIRE_MISS' => ['key' => ['VALIDATION_ID', 'VOLUME_HORAIRE_MISSION_ID']],
        'PIECE_JOINTE.FICHIER'         => ['queries' => [
            'DELETE FROM piece_jointe_fichier WHERE fichier_id = :ID',
        ]],
        '.PIECE_JOINTE'                => [],
        '.CANDIDATURE'                 => [],
        'MISSION.VALIDATION'           => [],
        '.VOLUME_HORAIRE'              => [],
        '.VOLUME_HORAIRE_REF'          => [],
        '.VOLUME_HORAIRE_MISSION'      => [],
        '.SERVICE'                     => [],
        '.SERVICE_REFERENTIEL'         => [],
        '.MISSION'                     => ['queries' => [
            'DELETE FROM mission_etudiant WHERE mission_id = :ID',
        ]],
        '.MODIFICATION_SERVICE_DU'     => [],
        '.INTERVENANT_DOSSIER'         => [],
        '.INTERVENANT'                 => ['queries' => [
            'DELETE FROM note WHERE intervenant_id = :ID',//Suppression des notes de l'intervenant
            'DELETE FROM validation WHERE intervenant_id = :ID',// Suppression de toutes les validations orphelines restantes
            'DELETE FROM formule_resultat_intervenant WHERE intervenant_id = :ID', // Suppression de tous les restes de formules de calcul
        ]],
    ];

    /**
     * @var array
     */
    private $data;

    /**
     * @var TreeNode
     */
    private $tree;



    public function getData()
    {
        if (null === $this->data) {
            $this->makeData();
        }

        return $this->data;
    }



    public function idsFromPost(?array $post): array
    {
        if (empty($post)) return [];

        $ids = $post;

        foreach ($ids as $i => $val) {
            if (str_starts_with($val, 'b64-')) {
                $ids[$i] = base64_decode(substr($val, 4));
            }
        }

        return $ids;
    }



    public function getTree()
    {
        $this->getData();
        $this->tree = $this->makeTree($this->getIntervenant()->getId());

        return $this->tree;
    }



    protected function makeData()
    {
        $this->data = [];
        $this->loadData('', [$this->getIntervenant()->getId()]);
    }



    protected function loadData(string $table, array $ids)
    {
        $bdd = $this->getEntityManager()->getConnection();

        foreach ($this->queries as $tables => $sql) {
            [$ref, $dest] = explode('.', $tables);
            if ($ref == $table) {
                foreach ($ids as $i => $id) {
                    if (is_string($id)) $ids[$i] = "'$id'";
                }
                $idList = implode(',', $ids);
                $sql = str_replace(':id', $idList, $sql);
                $ds = $bdd->fetchAllAssociative($sql);
                foreach ($ds as $d) {
                    if (!isset($this->data[$dest])) {
                        $this->data[$dest] = [];
                    }
                    $d['TABLE'] = $dest;
                    $d['PARENT_TABLE'] = $ref;
                    $d['DELETE'] = false;
                    $this->data[$dest][$d['ID']] = $d;
                }
                if (isset($this->data[$dest])) {
                    $this->loadData($dest, array_keys($this->data[$dest]));
                }
            }
        }
    }



    protected function makeTree(int $id): ?TreeNode
    {
        if (!isset($this->data['INTERVENANT'][$id])) {
            return null;
        }

        $d = $this->data['INTERVENANT'][$id];

        $node = $this->makeNode($d);
        $this->makeNodes('INTERVENANT', $d['ID'], $node);

        return $node;
    }



    protected function makeNode(array $data): TreeNode
    {
        $id = 'b64-' . base64_encode($data['TABLE'] . '#' . $data['ID']);
        $node = new TreeNode($id);
        $node->setIcon($data['ICON']);
        if (isset($data['ERROR'])) {
            $node->setLabel($data['LABEL'] . ' <span style="font-weight:bold;color:red" title="' . htmlentities($data['ERROR']) . '">Erreur rencontrée</span>');
        } else {
            $node->setLabel($data['LABEL']);
        }

        if (isset($data['TITLE'])) $node->setTitle($data['TITLE']);


        return $node;
    }



    protected function makeNodes(string $table, $id, TreeNode $parent)
    {
        foreach ($this->data as $t => $ds) {
            foreach ($ds as $null => $d) {
                if ($d['PARENT_TABLE'] == $table && $d['PARENT_ID'] == $id && $d['VISIBLE'] == 1) {
                    $n = $this->makeNode($d);

                    if ($d['CATEGORIE']) {
                        $cats = explode('|', $d['CATEGORIE']);
                        $cp = $parent;
                        foreach ($cats as $categorie) {
                            if ($cp->has($categorie)) {
                                $cp = $cp->get($categorie);
                            } else {
                                $newNode = new TreeNode($table == 'INTERVENANT' ? $categorie : uniqid('cat-'));
                                $newNode->setLabel($categorie);
                                $cp->add($newNode);
                                $cp = $newNode;
                            }
                        }
                        $cp->add($n);
                    } else {
                        $parent->add($n);
                    }
                    $this->makeNodes($t, $d['ID'], $n);
                }
            }
        }
    }



    public function delete(array $ids, bool $force = false): bool
    {
        $this->getData();
        $this->tagDelete($ids, $force);
        $this->makeDeleteQueries();

        return $this->doDelete();
    }



    private function tagDelete(array $ids, bool $force)
    {
        /* On positionne le tag sur toutes les data à détruire */
        foreach ($ids as $id) {
            if (false !== strpos($id, '#')) {
                [$table, $id] = explode('#', $id);
                if (isset($this->data[$table][$id])) {
                    //$this->subTagDelete($table, $id, true, $force);
                    $this->data[$table][$id]['DELETE'] = true;
                }
            }
        }


        /* On détruit les sous-éléments si on est en FORCE ou bien si les sous-éléments sont cachés pour ne pas qu'ils gênent */
        foreach ($this->data as $table => $ds) {
            foreach ($ds as $i => $d) {
                if ($d['DELETE']) {
                    $this->subDelete($d['TABLE'], $d ['ID'], $force);
                }
            }
        }

        /* Si on est en mode soft, on ne supprime pas les parents dont les enfants doivent rester */
        foreach ($this->data as $table => $ds) {
            foreach ($ds as $i => $d) {
                if (!$force && !$d['DELETE']) {
                    $dp = $d;
                    while ($dp['PARENT_ID']) {
                        $pTable = $dp['PARENT_TABLE'];
                        $pId = $dp['PARENT_ID'];
                        $dp = $this->data[$pTable][$pId];
                        if ($this->data[$pTable][$pId]['DELETE']) {
                            $this->data[$pTable][$pId]['DELETE'] = false;
                        }
                    }
                }
            }
        }

        /* Suppression des validations de VH ou VHRef orphelines */
        if (isset($this->data['VALIDATION'])) {
            foreach ($this->data['VALIDATION'] as $vid => $vdata) {
                if (isset($vdata['TYPE_VALIDATION_CODE'])) {
                    if ('SERVICES_PAR_COMP' == $vdata['TYPE_VALIDATION_CODE'] && isset($this->data['VALIDATION_VOL_HORAIRE'])) {
                        $found = false;
                        foreach ($this->data['VALIDATION_VOL_HORAIRE'] as $vvh) {
                            if ($vvh['VALIDATION_ID'] == $vid && (false === $vvh['DELETE'])) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $this->data['VALIDATION'][$vid]['DELETE'] = true;
                        }
                    }
                    if ('REFERENTIEL' == $vdata['TYPE_VALIDATION_CODE'] && isset($this->data['VALIDATION_VOL_HORAIRE_REF'])) {
                        $found = false;
                        foreach ($this->data['VALIDATION_VOL_HORAIRE_REF'] as $vvh) {
                            if ($vvh['VALIDATION_ID'] == $vid && (false === $vvh['DELETE'])) {
                                $found = true;
                                break;
                            }
                        }
                        if (!$found) {
                            $this->data['VALIDATION'][$vid]['DELETE'] = true;
                        }
                    }
                }
            }
        }
        /* DEBUG *
        $delIds = [];
        foreach ($this->data as $table => $ds) {
            foreach ($ds as $i => $d) {
                if ($d['DELETE']) {
                    if (!isset($delIds[$table])) $delIds[$table] = [];
                    $delIds[$table][]                = $d['ID'];
                    $this->data[$table][$i]['LABEL'] .= '<span style="font-weight:bold;color:red"> (à supprimer!)</span>';
                }
            }
        }
        //var_dump($this->data);
        var_dump($ids);
        var_dump($delIds);
        /* FIN DEBUG */
    }



    private function makeDeleteQueries()
    {
        foreach ($this->delete as $rule => $config) {
            [$ruleParent, $ruleTable] = explode('.', $rule);
            $keyCols = isset($config['key']) ? $config['key'] : ['ID'];
            $queries = isset($config['queries']) ? $config['queries'] : [];
            $delSql = '';
            foreach ($keyCols as $k) {
                if ($delSql == '') {
                    $delSql .= 'DELETE FROM ' . $ruleTable . ' WHERE ';
                } else {
                    $delSql .= ' AND ';
                }
                $delSql .= $k . ' = :' . $k;
            }
            $queries[] = $delSql;

            if (isset($this->data[$ruleTable])) {
                foreach ($this->data[$ruleTable] as $i => $d) {
                    if (!$ruleParent || $ruleParent == $d['PARENT_TABLE']) {
                        if (isset($d['DELETE']) && $d['DELETE']) {
                            foreach ($queries as $query) {
                                $delQuery = $query;
                                foreach ($keyCols as $k) {
                                    $delQuery = str_replace(':' . $k, (int)$d[$k], $delQuery);
                                }
                                if (!isset($this->data[$ruleTable][$i]['DELETE_QUERIES'])) {
                                    $this->data[$ruleTable][$i]['DELETE_QUERIES'] = [];
                                }
                                $this->data[$ruleTable][$i]['DELETE_QUERIES'][] = $delQuery;
                            }
                        }
                    }
                }
            }
        }
    }



    private function doDelete(): bool
    {
        $res = true;
        foreach ($this->delete as $rule => $null) {
            [$ruleParent, $ruleTable] = explode('.', $rule);
            if (isset($this->data[$ruleTable])) {
                foreach ($this->data[$ruleTable] as $i => $d) {
                    if ((!$ruleParent || $d['PARENT_TABLE'] == $ruleParent) && isset($d['DELETE_QUERIES']) && !empty($d['DELETE_QUERIES'])) {
                        if (!$this->deleteItem($ruleTable, $i)) $res = false;
                    }
                }
            }
        }

        return $res;
    }



    private function deleteItem(string $table, string $id): bool
    {
        $data = &$this->data[$table][$id];
        try {
            foreach ($data['DELETE_QUERIES'] as $sql) {
                $this->getEntityManager()->getConnection()->executeStatement($sql);
            }
            unset($this->data[$table][$id]);
            $res = true;
        } catch (\Exception $e) {
            $data['ERROR'] = $this->translate($e->getMessage());
            $res = false;
        }

        return $res;
    }



    protected function subDelete(string $table, string $id, bool $force)
    {
        foreach ($this->data as $t => $ds) {
            foreach ($ds as $i => $d) {
                if ($d['PARENT_TABLE'] == $table && $d['PARENT_ID'] == $id && !$d['DELETE']) {
                    if ($force || !$d['VISIBLE']) {
                        $this->data[$t][$i]['DELETE'] = true;
                    }
                    $this->subDelete($t, $i, $force);
                }
            }
        }
    }
}