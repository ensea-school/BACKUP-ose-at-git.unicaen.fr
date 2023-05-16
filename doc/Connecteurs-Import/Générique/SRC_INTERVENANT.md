# Vue source SRC_INTERVENANT

Pour pouvoir fonctionner, il faut pouvoir gérer simultanément: 
 - les statuts multiples
 - l'annualisation des fiches (en se basant sur les informations de validité fournies par la MV_INTERVENANT)
 - La synchronisation partielle dans certains cas

[SRC_INTERVENANT](SRC_INTERVENANT.sql) se base sur MV_INTERVENANT, puis confronte les données à la table INTERVENANT pour présenter à la vue différentielle
un ensemble de données prêt à synchroniser.

Concrètement, on part de MV_INTERVENANT, on fait une jointure sur la table des années et on confronte les dates de validité de début et de fin
avec les dates de début et de fin des années pour déterminer si l'intervenant est pertinent dans l'année n ou non.

Ensuite, on fait une jointure avec la table INTERVENANT en se basant sur le code et l'année de l'intervenant.

Il en résulta une liste de valeurs à partir de laquelle on va calculer des variables qui seront ensuite exploitées pour 

Pour la suite, un certains nombre de scénarios ont été mis au point.
Pour chaque scénario, une ou plusieurs stratégies de synchronisation sont mises en place.

Chaque ligne issue de MV_INTERVENANT doit se voire attribué un scénario.
Pour cela, on s'appuie sur un certain nombre de variables.

Nous avons donc : 
- la vue matérialisée MV_INTERVENANT annualisée et confrontée à la table INTERVENANT
- calcul des variables sur cette base
- sélection des scénarios et des sous scénarios sur la base des variables
- action définie par scénario (SYNCHRO, SYNCHRO partielle ou DROP).

Tout cela se fait dans la vue SRC_INTERVENANT.

SRC_INTERVENANT est ensuite utilisée par le système d'import de la même manière que les autres vues sources de OSE pour réaliser 
les opérations de synchronisation adéquates (INSERT, UPDATE, DELETE, UNDELETE).

## Grands principes de fonctionnement. Liste des variables nécessaires :

### statut_source_autre

Valeurs possibles : 0 ou 1

Détermine si le statut issu de MV_INTERVENANT est AUTRE ou bien tout autre statut.

### statut_intervenant_autre

Valeurs possibles : 0 ou 1

Détermine si le statut de INTERVENANT est AUTRE ou bien tout autre statut.

### statuts_identiques

Valeurs possibles : 0 ou 1

Détermine si les statuts issus de MV_INTERVENANT et INTERVENANT sont identiques ou non

### types_identiques

CASE WHEN ssi.type_intervenant_id = isi.type_intervenant_id THEN 1 ELSE 0 END

### sync_statut

Valeurs possibles : 0 ou 1

Détermine s'il faut mettre à jour le statut via la synchro ou bien si celui-ci ne doit pas être modifié, 
parcequ'il aurait par exemple été changé manuellement dans l'application.                                                   


### intervenant_local

Valeurs possibles : 0 ou 1

Détermine si l'intervenant a été saisi directement dans OSE, auquel cas il ne devra pas être impacté par la synchro, 
ou bien s'il provient d'un précédent import.


### intervenant_donnees

Valeurs possibles : 0 ou 1

Détermine si des données (services, PJ, dossier, etc.) sont associées à la fiche de l'intervenant ou non 


### intervenant_histo

Valeurs possibles : 0 ou 1

Détermine si la fiche intervenant est historisée ou non


### nb_sources

Valeurs possibles : 1 à n

Nombre d'occurences issues de MV_INTERVENANT et annualisées d'un même intervenant pour une année donnée.
Il y aura une occurence par statut, un intervenant ne pouvant pas avoir deux fois le même statut la même année.

### nb_intervenants

Valeurs possibles : 0 à n

Nombre d'intervenants présents dans INTERVENANT ayant le même code et la même année que ceux issus de MV_INTERVENANT.

### nb_statuts_egaux

Valeurs possibles : 0 à n

Nombre de lignes où les statuts issus de MV_INTERVENANT et d'INTERVENANT sont égaux.

### statuts_egaux_id

Type NUMERIC ou NULL

Si au moins un statut est présent à la fois dans la source ET dans OSE, un d'entre eux est remonté ici




## Liste des scénarios

11 scénario ont été identifiés :

### 1 : Lorsque le statut est le même sur la source et la fiche OSE, alors la mise à jour meut se faire

### 2 : On ne doit pas restaurer les intervenants créés dans OSE puis historisés qui ne sont pas remontés par la source

### 3 : Si la source remonte 1 fiche et qu'aucune fiche OSE ne correspond => insertion d'un nouvel intervenant

### 4 : Si pour un intervenant il y a correspondances parfaite des statuts entre la source et OSE

### 5 : Quand il y a 1 source et 1 fiche pour un intervenant, mais que le statut diffère

### 6 : Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut

### 7 : Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut

### 8 : Quand il y a plusieurs sources pour un seul intervenant et qu'une au moins matche
   
### 9 : Quand il y a plusieurs sources pour un seul intervenant et qu'aucun ne matche

### 10 : Quand il y a 2 sources et 2 intervenants et qu'un seul des deux matche

### 11 : Autres cas