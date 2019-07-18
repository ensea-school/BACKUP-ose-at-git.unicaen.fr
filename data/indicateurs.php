<?php

return [
    310  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires ont reçu l\'agrément du Conseil Académique et n\'ont pas encore de contrat/avenant',
        'LIBELLE_SINGULIER' => '%s vacataire a reçu l\'agrément du Conseil Académique et n\'a pas encore de contrat/avenant',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/contrat',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    410  => [
        'TYPE'              => 'Données personnelles',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de validation de leurs données personnelles',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de validation de ses données personnelles',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/dossier',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1010 => [
        'TYPE'              => 'Pièces justificatives',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires n\'ont pas fourni toutes les pièces justificatives obligatoires',
        'LIBELLE_SINGULIER' => '%s vacataire n\'a pas fourni toutes les pièces justificatives obligatoires',
        'MESSAGE'           => null,
        'ROUTE'             => 'piece-jointe/intervenant',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1020 => [
        'TYPE'              => 'Pièces justificatives',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de validation de leurs pièces justificatives obligatoires',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de validation de ses pièces justificatives obligatoires',
        'MESSAGE'           => null,
        'ROUTE'             => 'piece-jointe/intervenant',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    510  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
        'LIBELLE_SINGULIER' => '%s intervenant a saisi des enseignements dont l\'étape, l\'élément pédagogique ou la période ont disparu',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    710  => [
        'TYPE'              => 'Enseignements <em>Vacataires</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de validation de ses enseignements <i>prévisionnels</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    610  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents sont en attente de validation de leurs enseignements <i>prévisionnels</i>',
        'LIBELLE_SINGULIER' => '%s permanent est en attente de validation de ses enseignements <i>prévisionnels</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    620  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents sont en attente de validation de leur référentiel <i>prévisionnel</i>',
        'LIBELLE_SINGULIER' => '%s permanent est en attente de validation de son référentiel <i>prévisionnel</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/referentiel/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    630  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents n\'ont pas clôturé la saisie de leurs services <i>réalisés</i>',
        'LIBELLE_SINGULIER' => '%s permanent n\'a pas clôturé la saisie de ses services <i>réalisés</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services-realises',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    720  => [
        'TYPE'              => 'Enseignements <em>Vacataires</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de validation de leurs enseignements <i>réalisés</i>',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de validation de ses enseignements <i>réalisés</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/realise',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    640  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i>',
        'LIBELLE_SINGULIER' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/realise',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    650  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leurs enseignements <i>réalisés</i> par d\'autres composantes',
        'LIBELLE_SINGULIER' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de ses enseignements <i>réalisés</i> par d\'autres composantes',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/realise',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    660  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i>',
        'LIBELLE_SINGULIER' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/referentiel/realise',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    670  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents ont clôturé la saisie de leurs services réalisés et sont en attente de validation de leur référentiel <i>réalisé</i> par d\'autres composantes',
        'LIBELLE_SINGULIER' => '%s permanent a clôturé la saisie de ses services réalisés et est en attente de validation de son référentiel <i>réalisé</i> par d\'autres composantes',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/referentiel/realise',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    210  => [
        'TYPE'              => 'Agrément',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente d\'agrément du conseil restreint',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente d\'agrément du conseil restreint',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/agrement/conseil-restreint',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    220  => [
        'TYPE'              => 'Agrément',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente d\'agrément du conseil académique',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente d\'agrément du conseil académique',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/agrement/conseil-academique',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    540  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total HC <i>prévisionnel saisi</i> qui dépasse le plafond de la rémunération FC D714-60',
        'LIBELLE_SINGULIER' => '%s intervenant a un total HC <i>prévisionnel saisi</i> qui dépasse le plafond de la rémunération FC D714-60',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    550  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total HC <i>réalisé saisi</i> qui dépasse le plafond de la rémunération FC D714-60',
        'LIBELLE_SINGULIER' => '%s intervenant a un total HC <i>réalisé saisi</i> qui dépasse le plafond de la rémunération FC D714-60',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    560  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures équivalent TD (HETD) <i>prévisionnelles</i> au-delà du plafond autorisé de part leur statut',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures équivalent TD (HETD) <i>prévisionnelles</i> au-delà du plafond autorisé de part son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    570  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures équivalent TD (HETD) <i>réalisées</i> au-delà du plafond autorisé de part leur statut',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures équivalent TD (HETD) <i>réalisées</i> au-delà du plafond autorisé de part son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    320  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de leur contrat initial',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de son contrat initial',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/contrat',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    330  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires sont en attente de leur avenant',
        'LIBELLE_SINGULIER' => '%s vacataire est en attente de son avenant',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/contrat',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    340  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires ont saisi des heures d\'enseignements <i>prévisionnels</i> supplémentaires depuis l\'édition de leur contrat ou avenant',
        'LIBELLE_SINGULIER' => '%s vacataire a saisi des heures d\'enseignements prévisionnels supplémentaires depuis l\'édition de son contrat ou avenant',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    350  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s contrats/avenants de vacataire ont été déposés',
        'LIBELLE_SINGULIER' => '%s contrat/avenant de vacataire a été déposé',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/contrat',
        'TEM_DISTINCT'      => false,
        'TEM_NOT_STRUCTURE' => false,
    ],
    360  => [
        'TYPE'              => 'Contrat / avenant',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s contrats de vacataires sont en attente de retour',
        'LIBELLE_SINGULIER' => '%s contrat de vacataire est en attente de retour',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/contrat',
        'TEM_DISTINCT'      => false,
        'TEM_NOT_STRUCTURE' => false,
    ],
    110  => [
        'TYPE'              => 'Affectation',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents affectés dans une autre structure ont des enseignements <i>prévisionnels validés</i> dans ma structure',
        'LIBELLE_SINGULIER' => '%s permanent affecté dans une autre structure a des enseignements <i>prévisionnels validés</i> dans ma structure',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    120  => [
        'TYPE'              => 'Affectation',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
        'LIBELLE_SINGULIER' => '%s permanent affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    130  => [
        'TYPE'              => 'Affectation',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants \'BIATSS\' affectés dans ma structure ont des enseignements <i>prévisionnels validés</i> dans une autre structure',
        'LIBELLE_SINGULIER' => '%s intervenant \'BIATSS\' affecté dans ma structure a des enseignements <i>prévisionnels validés</i> dans une autre structure',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/validation/service/prevu',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    420  => [
        'TYPE'              => 'Données personnelles',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires ont saisi des données personnelles qui diffèrent de celles importées',
        'LIBELLE_SINGULIER' => '%s vacataire a saisi des données personnelles qui diffèrent de celles importées',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/dossier/differences',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1011 => [
        'TYPE'              => 'Pièces justificatives',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanent n\'a pas fourni toutes les pièces justificatives obligatoires',
        'LIBELLE_SINGULIER' => '%s permanent n\'a pas fourni toutes les pièces justificatives obligatoires',
        'MESSAGE'           => null,
        'ROUTE'             => 'piece-jointe/intervenant',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1021 => [
        'TYPE'              => 'Pièces justificatives',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents sont en attente de validation de leurs pièces justificatives obligatoires',
        'LIBELLE_SINGULIER' => '%s permanent est en attente de validation de ses pièces justificatives obligatoires',
        'MESSAGE'           => null,
        'ROUTE'             => 'piece-jointe/intervenant',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    910  => [
        'TYPE'              => 'Mise en paiement <em>Vacataires</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires peuvent faire l\'objet d\'une demande de mise en paiement',
        'LIBELLE_SINGULIER' => '%s vacataire peut faire l\'objet d\'une demande de mise en paiement',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/mise-en-paiement/demande',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1110 => [
        'TYPE'              => 'Charges d\'enseignement',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>premier semestre</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'indicateur/depassement-charges/prevu/s1',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1111 => [
        'TYPE'              => 'Charges d\'enseignement',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures d\'enseignement <i>prévisionnel</i> dépassant la charge programmée au <i>second semestre</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'indicateur/depassement-charges/prevu/s2',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1120 => [
        'TYPE'              => 'Charges d\'enseignement',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>premier semestre</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'indicateur/depassement-charges/realise/s1',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1121 => [
        'TYPE'              => 'Charges d\'enseignement',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures d\'enseignement <i>réalisé</i> dépassant la charge programmée au <i>second semestre</i>',
        'MESSAGE'           => null,
        'ROUTE'             => 'indicateur/depassement-charges/realise/s2',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    810  => [
        'TYPE'              => 'Mise en paiement <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents peuvent faire l\'objet d\'une demande de mise en paiement',
        'LIBELLE_SINGULIER' => '%s permanent peut faire l\'objet d\'une demande de mise en paiement',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/mise-en-paiement/demande',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    920  => [
        'TYPE'              => 'Mise en paiement <em>Vacataires</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s vacataires peuvent faire l\'objet d\'une mise en paiement',
        'LIBELLE_SINGULIER' => '%s vacataire peut faire l\'objet d\'une mise en paiement',
        'MESSAGE'           => null,
        'ROUTE'             => 'paiement/etat-demande-paiement',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1210 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la fonction correspondante',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la fonction correspondante',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1211 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour le type de fonction correspondant',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour le type de fonction correspondant',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1220 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la fonction correspondante',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la fonction correspondante',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1221 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour le type de fonction correspondant',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour le type de fonction correspondant',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1230 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la composante correspondante',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>prévisionnel</i> dépassant le plafond autorisé pour la composante correspondante',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    1240 => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la composante correspondante',
        'LIBELLE_SINGULIER' => '%s intervenant a des heures de référentiel <i>réalisé</i> dépassant le plafond autorisé pour la composante correspondante',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    820  => [
        'TYPE'              => 'Mise en paiement <em>Permanents</em>',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s permanents peuvent faire l\'objet d\'une mise en paiement',
        'LIBELLE_SINGULIER' => '%s permanent peut faire l\'objet d\'une mise en paiement',
        'MESSAGE'           => null,
        'ROUTE'             => 'paiement/etat-demande-paiement',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    520  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total HC hors rémunération FC D714-60 <i>prévisionnel saisi</i> qui dépasse le plafond correspondant à leur statut',
        'LIBELLE_SINGULIER' => '%s intervenant a un total HC hors rémunération FC D714-60 <i>prévisionnel saisi</i> qui dépasse le plafond correspondant à son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    530  => [
        'TYPE'              => 'Enseignements et référentiel',
        'ENABLED'           => true,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total HC hors rémunération FC D714-60 <i>réalisé saisi</i> qui dépasse le plafond correspondant à leur statut',
        'LIBELLE_SINGULIER' => '%s intervenant a un total HC hors rémunération FC D714-60 <i>réalisé saisi</i> qui dépasse le plafond correspondant à son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services-realises',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    680  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => false,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total Référentiel <i>prévisionnel</i> qui dépasse les plafonds correspondant à leurs statuts',
        'LIBELLE_SINGULIER' => '%s intervenant a un total Référentiel <i>prévisionnel</i> qui dépasse le plafond correspondant à son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
    690  => [
        'TYPE'              => 'Enseignements et référentiel <em>Permanents</em>',
        'ENABLED'           => false,
        'LIBELLE_PLURIEL'   => '%s intervenants ont un total Référentiel <i>réalisé</i> qui dépasse le plafond correspondant à leur statut',
        'LIBELLE_SINGULIER' => '%s intervenant a un total Référentiel <i>réalisé</i> qui dépasse le plafond correspondant à son statut',
        'MESSAGE'           => null,
        'ROUTE'             => 'intervenant/services-realises',
        'TEM_DISTINCT'      => true,
        'TEM_NOT_STRUCTURE' => false,
    ],
];