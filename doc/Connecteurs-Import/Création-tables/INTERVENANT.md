# INTERVENANT

Liste des intervenants

Colonnes nécessaires :

Ici, principalement pour des raisons de performances, ilest recommandé de procéder en deux étapes :
* Rapatrier les données dans une vue matérialisée à appeler MV_INTERVENANT pourvue des colonnes listées ci-dessous
* Utiliser [SRC_INTERVENANT](../Générique/SRC_INTERVENANT.sql) en tant que vue source. Cette vue SRC_INTERVENANT est commune à tous les connecteurs.
Vous devez l'utiliser telle quelle.

Votre vue matérialisée MV_INTERVENANT devra contenir les colonnes suivantes :

|Colonne                   |Type    |Longueur|Nullable|Commentaire                  |
|--------------------------|--------|--------|--------|-----------------------------|
|CODE                      |VARCHAR2|60      |Non     | Identifiant unique de l'individu dans le système d'information l'individu |
|CODE_RH                   |VARCHAR2|60      |Oui     | Matricule permettant éventuellement d'identifier l'intervenant dans Siham, Harpège, Mangue, etc. |
|Z_SOURCE_ID               |VARCHAR2|15      |Non     |==> SOURCE.CODE              |
|SOURCE_CODE               |VARCHAR2|100     |Oui     |                             |
|UTILISATEUR_CODE          |VARCHAR2|60      |Oui     | Identifiant pour faire lien avec le LDAP |
|Z_STRUCTURE_ID            |NUMBER  |        |Oui     |==> STRUCTURE.SOURCE_CODE    |
|Z_STATUT_ID               |NUMBER  |        |Non     |==> STATUT.CODE  |
|Z_GRADE_ID                |NUMBER  |        |Oui     |==> GRADE.SOURCE_CODE        |
|Z_DISCIPLINE_ID           |NUMBER  |        |Oui     |==> DISCIPLINE.SOURCE_CODE   |
|Z_CIVILITE_ID             |NUMBER  |        |Oui     |==> CIVILITE.LIBELLE_COURT   |
|NOM_USUEL                 |VARCHAR2|60      |Non     |                             |
|PRENOM                    |VARCHAR2|60      |Non     |                             |
|DATE_NAISSANCE            |DATE    |        |Non     |                             |
|NOM_PATRONYMIQUE          |VARCHAR2|60      |Oui     |                             |
|COMMUNE_NAISSANCE         |VARCHAR2|60      |Oui     |                             |
|Z_PAYS_NAISSANCE_ID       |NUMBER  |        |Oui     |==> PAYS.SOURCE_CODE         |
|Z_DEPARTEMENT_NAISSANCE_ID|NUMBER  |        |Oui     |==> DEPARTEMENT.SOURCE_CODE  |
|Z_PAYS_NATIONALITE_ID     |NUMBER  |        |Oui     |==> PAYS.SOURCE_CODE         |
|TEL_PRO                   |VARCHAR2|30      |Oui     |                             |
|TEL_PERSO                 |VARCHAR2|30      |Oui     |                             |
|EMAIL_PRO                 |VARCHAR2|255     |Oui     |                             |
|EMAIL_PERSO               |VARCHAR2|255     |Oui     |                             |
|ADRESSE_PRECISIONS        |VARCHAR2|240     |Oui     |                             |
|ADRESSE_NUMERO            |VARCHAR2|4       |Oui     |                             |
|Z_ADRESSE_NUMERO_COMPL_ID |NUMBER  |        |Oui     |==> ADRESSE_NUMERO_COMPL.CODE|
|Z_ADRESSE_VOIRIE_ID       |NUMBER  |        |Oui     |==> ADRESSE_VOIRIE.SOURCE_CODE |
|ADRESSE_VOIE              |VARCHAR2|60      |Oui     |                             |
|ADRESSE_LIEU_DIT          |VARCHAR2|60      |Oui     |                             |
|ADRESSE_CODE_POSTAL       |VARCHAR2|15      |Oui     |                             |
|ADRESSE_COMMUNE           |VARCHAR2|50      |Oui     |                             |
|Z_ADRESSE_PAYS_ID         |NUMBER  |        |Oui     |==> PAYS.SOURCE_CODE         |
|NUMERO_INSEE              |VARCHAR2|20      |Oui     |                             |
|NUMERO_INSEE_PROVISOIRE   |NUMBER  |        |Non     | Flag (1 ou 0)               |
|IBAN                      |VARCHAR2|50      |Oui     |                             |
|BIC                       |VARCHAR2|20      |Oui     |                             |
|RIB_HORS_SEPA             |NUMBER  |        |Non     | Flag (1 ou 0)               |
|AUTRE_1                   |VARCHAR2|1000    |Oui     |                             |
|AUTRE_2                   |VARCHAR2|1000    |Oui     |                             |
|AUTRE_3                   |VARCHAR2|1000    |Oui     |                             |
|AUTRE_4                   |VARCHAR2|1000    |Oui     |                             |
|AUTRE_5                   |VARCHAR2|1000    |Oui     |                             |
|Z_EMPLOYEUR_ID            |NUMBER  |        |Oui     |==> EMPLOYEUR.SOURCE_CODE    |
|VALIDITE_DEBUT            |DATE    |        |Oui     |Date de début de validité (NULL = depuis toujours)  |
|VALIDITE_FIN              |DATE    |        |Oui     |Date de fin   de validité (NULL = pas d'expiration) |
|AFFECTATION_FIN           |DATE    |        |Oui     |Date de fin   d'affectation |

Attention : un même individu peut avoir plusieurs fiches INTERVENANT dans la même année, tant qu'on peut les distinguer par le statut.
Le champ CODE doit être spécifique à l'individu. Une même personne ne devrait pas avoir plusieurs valeurs diférentes dans CODE.
CODE sert donc à identifier que plusieurs fiches appartiennent à une même personne.

Attention également : les dates de début et de fin de validité sont utilisées par la SRC_INTERVENANT pour déterminer sur quelles années synchroniser l'intervenant.

La vue source doit avoir *in fine* une unicité sur le trouple [CODE,ANNEE_ID,STATUT_ID].

UTILISATEUR_CODE doit contenir un identifiant qui sera mis en rapport avec les données LDAP de l'utilisateur connecté.
Par défaut, c'est `supannEmpId`, mais vous pouvez le personnaliser dans le fichier de configuration config.local.php, paramètre `ldap` `utilisateurCode`.


Exemple de vue matérialisée :
[MV_INTERVENANT](../Harpège/MV_INTERVENANT.sql)
