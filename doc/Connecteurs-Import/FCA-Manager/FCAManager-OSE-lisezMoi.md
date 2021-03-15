INTERFACAGE ENTRE FCA MANAGER et OSE
-------------------------------------------------
Auteur : Jérôme Gallot (jerome.gallot@unicaen.fr)

L'importation de données de FCA Manager vers OSE s'effectue via un dblink entre les deux bases Oracle.

Les vues décrites plus bas utilisent aussi un dblink vers la base Apogee : @apoprod. Cette liaison ne sert qu'à trouver le numéro de composante Hapege depuis le numéro de composante de FCA (= N° composante Apogee)

OSE va lire des vues créées dans la base FCA Manager.

Préambule :
------------

La nomenclature des noms de formation dans FCA Manager répond à des choix propres à l'UNICAEN :

- Les actions et enseignements qualifiants sont gérés uniquement par FCA Manager et définis de la manière suivante :
	- le nom d'une action est représenté ainsi : FCA-XXXXXX-ANNEE_UNIV
	- le nom d'un enseignement suit la nomenclature : FCA-XXXXXX-ANNEE_UNIV-YY (Y variant de 0 à 20)
	- le code source du logiciel est alors FCA Manager

- Les actions et enseignements diplômants sont intégrés depuis Apogee vers FCA:
	- le nom de l'action et les enseignements sont au format APO-ETAPE-VET-ANNEE_UNIV

ANNEE_UNIV correspond par exemple à 2017 pour 2017/2018


Fca Manager peut aussi contenir des enseignements de type qualifiant qui sont rattachés à des actions diplômantes.
Les scripts SQL permettent de déverser uniquement les données des formations qualifiantes mais prennent en compte cette subtilité (notamment dans la vue OSE_CHEMIN_PEDAGOGIQUE) 

Ils ont été testés avec les versions 1.10.XXX et 1.11.004 de FCA Manager

Le déversement dans OSE :
-------------------------

Il est possible si :
 - des actions et des enseignements dits qualifiants ont été créés dans FCA Manager
 - au moins un intervenant doit être attaché à chaque enseignement de l'action
 - il n'y a pas d'obligation que les séances d'un enseignement soient déjà créées (cette contrainte sera éventuellement mise en place ultérieurement)
 - s'il n'y a pas d'intervenant de défini sur un enseignement -> les heures déclarées pour OSE sont fixées à 0H (à adapter)


La vue OSE_ETAPE :
--------------------------------

Cette vue permet de déverser seulement les actions qualifiantes gérées via FCA Manager. Les actions diplômantes sont gérées par la connecteur APOGEE->OSE

les étapes créées sont uniquement en Formation continue (FC) dans OSE


La vue OSE_ELEMENT_PEDAGOGIQUE :
--------------------------------
Cette vue permet de déverser les enseignements qualifiants gérés via FCA Manager 

La vue OSE_CHEMIN_PEDAGOGIQUE :
--------------------------------

Cette vue définit les relations entre les actions (étapes) et élements pédagogiques (enseignements FCA)


La vue OSE_VOLUME_HORAIRE_ENS :
--------------------------------
Cette vue permet d'associer à chaque enseignement ses volumes horaires par type d'intervention, la saisie de services dans OSE n'étant possible que si une charge existe pour l'enseignement.
ATTENTION : les volumes horaires envoyés correspondent au cumul des horaires des intervenants d'un enseignement de FCA Manager. Ce n'est donc pas le volume horaire défini sur l'enseignement qui est utilisé. (cf cours avec plusieurs intervenants pendant la même séance)


Le script [FCAManager-controles.sql](FCAManager-OSE-export.sql) crée les vues dédiées à l'outil OSE dans la base FCA Manager.
Le script [FCAManager-controles.sql](FCAManager-controles.sql) contient quelques requêtes notamment pour déceler des incohérences entre les vues lors des associations étapes/élèments
