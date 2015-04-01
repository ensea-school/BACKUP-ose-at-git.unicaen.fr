<?php

/*
  -- génération des "define ('CONSTANTE', 'Message d'erreur explicite');" :
  select rpad('define (''CONSTRAINT_'||constraint_name||''',', 60, ' ')||' '''||initcap(replace(constraint_name, '_', ' '))||''');' as name
  from user_constraints where constraint_type = 'U' and generated = 'USER NAME' and table_name not like 'BIN$%' order by constraint_name ;

  -- génération des "'Message d'erreur Oracle' => 'CONSTANTE'," :
  select '''ORA-00001: unique constraint ('||owner||'.'||constraint_name||') violated'''||' => CONSTRAINT_'||constraint_name||',' as name
  from user_constraints where constraint_type = 'U' and generated = 'USER NAME' and table_name not like 'BIN$%' order by constraint_name ;
 */

define('CONSTRAINT_ADRESSE_INTERVENANT_SOURCE_UN', 'Adresse Intervenant Source Un');
define('CONSTRAINT_ADRESSE_STRUCTURE_SOURCE_UN', 'Adresse Structure Source Un');
define('CONSTRAINT_AFFECTATION_IS_UN', 'Affectation Is Un');
define('CONSTRAINT_AFFECTATION_SRC_UN', 'Affectation Src Un');
define('CONSTRAINT_CHEMIN_PEDAGOGIQUE__UN', 'Chemin Pedagogique  Un');
define('CONSTRAINT_CHEMIN_PEDAGO_SRC_ID_UN', 'Chemin Pedago Src Id Un');
define('CONSTRAINT_CIVILITE_LIBELLE_COURT_UN', 'Civilite Libelle Court Un');
define('CONSTRAINT_CORPS_SOURCE_UN', 'Corps Source Un');
define('CONSTRAINT_EMPLOI_UN', 'Emploi Un');
define('CONSTRAINT_EP_CODE__UN', 'Ep Code  Un');
define('CONSTRAINT_EPP_SOURCE_UN', 'Epp Source Un');
define('CONSTRAINT_EPP_UN', 'Epp Un');
define('CONSTRAINT_ETABLISSEMENT_SOURCE_ID_UN', 'Etablissement Source Id Un');
define('CONSTRAINT_ETAPE_CODE__UN', 'Etape Code  Un');
define('CONSTRAINT_GTYPE_FORMATION_SOURCE_UN', 'Gtype Formation Source Un');
define('CONSTRAINT_IE_SOURCE_UN', 'Ie Source Un');
define('CONSTRAINT_INTERVENANT_SOURCE__UN', 'Intervenant Source  Un');
define('CONSTRAINT_IP_SOURCE_UN', 'Ip Source Un');
define('CONSTRAINT_PERSONNEL_SOURCE__UN', 'Personnel Source  Un');
define('CONSTRAINT_PE_SOURCE_UN', 'Pe Source Un');
define('CONSTRAINT_ROLE_SOURCE_UN', 'Role Source Un');
define('CONSTRAINT_ROLE_UN', 'Role Un');
define('CONSTRAINT_SECTION_CNU_SOURCE_UN', 'Section Cnu Source Un');
define('CONSTRAINT_SERVICE_DU_IA_UN', 'Service Du Ia Un');
define('CONSTRAINT_SERVICE_REFERENTIEL_UN', "Il n'est pas possible d'attribuer plusieurs fois la même fonction référentielle");
define('CONSTRAINT_SITUATION_FAMILIALE_CODE_UN', 'Situation Familiale Code Un');
define('CONSTRAINT_SOURCE_CODE_UN', 'Source Code Un');
define('CONSTRAINT_STRUCTURE_SOURCE_ID_UN', 'Structure Source Id Un');
define('CONSTRAINT_TYPE_FORMATION__UN', 'Type Formation  Un');
define('CONSTRAINT_TYPE_INTERVENANT_CODE_UN', 'Type Intervenant Code Un');
define('CONSTRAINT_TYPE_ROLE_CODE_UN', 'Type Role Code Un');
define('CONSTRAINT_TYPE_STRUCTURE_CODE_UN', 'Type Structure Code Un');
define('CONSTRAINT_UTILISATEUR_INTERVENANT_UN', 'Utilisateur Intervenant Un');
define('CONSTRAINT_UTILISATEUR_PERSONNEL_UN', 'Utilisateur Personnel Un');
define('CONSTRAINT_UTILISATEUR_ROLE_ID_UN', 'Utilisateur Role Id Un');
define('CONSTRAINT_UTILISATEUR_USERNAME_UN', 'Utilisateur Username Un');
define('CONSTRAINT_VHE_SOURCE_UN', 'Vhe Source Un');
define('CONSTRAINT_VOLUME_HORAIRE_EP__UN', 'Volume Horaire Ep  Un');

return [
    'ORA-00001: unique constraint (OSE.ADRESSE_INTERVENANT_SOURCE_UN) violated' => CONSTRAINT_ADRESSE_INTERVENANT_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.ADRESSE_STRUCTURE_SOURCE_UN) violated'   => CONSTRAINT_ADRESSE_STRUCTURE_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.AFFECTATION_IS_UN) violated'             => CONSTRAINT_AFFECTATION_IS_UN,
    'ORA-00001: unique constraint (OSE.AFFECTATION_SRC_UN) violated'            => CONSTRAINT_AFFECTATION_SRC_UN,
    'ORA-00001: unique constraint (OSE.CHEMIN_PEDAGOGIQUE__UN) violated'        => CONSTRAINT_CHEMIN_PEDAGOGIQUE__UN,
    'ORA-00001: unique constraint (OSE.CHEMIN_PEDAGO_SRC_ID_UN) violated'       => CONSTRAINT_CHEMIN_PEDAGO_SRC_ID_UN,
    'ORA-00001: unique constraint (OSE.CIVILITE_LIBELLE_COURT_UN) violated'     => CONSTRAINT_CIVILITE_LIBELLE_COURT_UN,
    'ORA-00001: unique constraint (OSE.CORPS_SOURCE_UN) violated'               => CONSTRAINT_CORPS_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.EMPLOI_UN) violated'                     => CONSTRAINT_EMPLOI_UN,
    'ORA-00001: unique constraint (OSE.EP_CODE__UN) violated'                   => CONSTRAINT_EP_CODE__UN,
    'ORA-00001: unique constraint (OSE.EPP_SOURCE_UN) violated'                 => CONSTRAINT_EPP_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.EPP_UN) violated'                        => CONSTRAINT_EPP_UN,
    'ORA-00001: unique constraint (OSE.ETABLISSEMENT_SOURCE_ID_UN) violated'    => CONSTRAINT_ETABLISSEMENT_SOURCE_ID_UN,
    'ORA-00001: unique constraint (OSE.ETAPE_CODE__UN) violated'                => CONSTRAINT_ETAPE_CODE__UN,
    'ORA-00001: unique constraint (OSE.GTYPE_FORMATION_SOURCE_UN) violated'     => CONSTRAINT_GTYPE_FORMATION_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.IE_SOURCE_UN) violated'                  => CONSTRAINT_IE_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.INTERVENANT_SOURCE__UN) violated'        => CONSTRAINT_INTERVENANT_SOURCE__UN,
    'ORA-00001: unique constraint (OSE.IP_SOURCE_UN) violated'                  => CONSTRAINT_IP_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.PERSONNEL_SOURCE__UN) violated'          => CONSTRAINT_PERSONNEL_SOURCE__UN,
    'ORA-00001: unique constraint (OSE.PE_SOURCE_UN) violated'                  => CONSTRAINT_PE_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.ROLE_SOURCE_UN) violated'                => CONSTRAINT_ROLE_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.ROLE_UN) violated'                       => CONSTRAINT_ROLE_UN,
    'ORA-00001: unique constraint (OSE.SECTION_CNU_SOURCE_UN) violated'         => CONSTRAINT_SECTION_CNU_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.SERVICE_DU_IA_UN) violated'              => CONSTRAINT_SERVICE_DU_IA_UN,
    'ORA-00001: unique constraint (OSE.SERVICE_REFERENTIEL_UN) violated'        => CONSTRAINT_SERVICE_REFERENTIEL_UN,
    'ORA-00001: unique constraint (OSE.SITUATION_FAMILIALE_CODE_UN) violated'   => CONSTRAINT_SITUATION_FAMILIALE_CODE_UN,
    'ORA-00001: unique constraint (OSE.SOURCE_CODE_UN) violated'                => CONSTRAINT_SOURCE_CODE_UN,
    'ORA-00001: unique constraint (OSE.STRUCTURE_SOURCE_ID_UN) violated'        => CONSTRAINT_STRUCTURE_SOURCE_ID_UN,
    'ORA-00001: unique constraint (OSE.TYPE_FORMATION__UN) violated'            => CONSTRAINT_TYPE_FORMATION__UN,
    'ORA-00001: unique constraint (OSE.TYPE_INTERVENANT_CODE_UN) violated'      => CONSTRAINT_TYPE_INTERVENANT_CODE_UN,
    'ORA-00001: unique constraint (OSE.TYPE_ROLE_CODE_UN) violated'             => CONSTRAINT_TYPE_ROLE_CODE_UN,
    'ORA-00001: unique constraint (OSE.TYPE_STRUCTURE_CODE_UN) violated'        => CONSTRAINT_TYPE_STRUCTURE_CODE_UN,
    'ORA-00001: unique constraint (OSE.UTILISATEUR_INTERVENANT_UN) violated'    => CONSTRAINT_UTILISATEUR_INTERVENANT_UN,
    'ORA-00001: unique constraint (OSE.UTILISATEUR_PERSONNEL_UN) violated'      => CONSTRAINT_UTILISATEUR_PERSONNEL_UN,
    'ORA-00001: unique constraint (OSE.UTILISATEUR_ROLE_ID_UN) violated'        => CONSTRAINT_UTILISATEUR_ROLE_ID_UN,
    'ORA-00001: unique constraint (OSE.UTILISATEUR_USERNAME_UN) violated'       => CONSTRAINT_UTILISATEUR_USERNAME_UN,
    'ORA-00001: unique constraint (OSE.VHE_SOURCE_UN) violated'                 => CONSTRAINT_VHE_SOURCE_UN,
    'ORA-00001: unique constraint (OSE.VOLUME_HORAIRE_EP__UN) violated'         => CONSTRAINT_VOLUME_HORAIRE_EP__UN,
];