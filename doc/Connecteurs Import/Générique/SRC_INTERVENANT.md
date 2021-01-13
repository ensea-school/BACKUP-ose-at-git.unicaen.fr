# Vue source SRC_INTERVENANT

Pour pouvoir fonctionner, il faut pouvoir gérer simultanément: 
 - les statuts multiples
 - l'annualisation des fiches
 - La synchronisation partielle dans certains cas

SRC_INTERVENANT se base sur MV_INTERVENANT, puis confronte les données à la table INTERVENANT pour présenter à la vue différentielle
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

## Liste des variables nécessaires :

### statut_source_autre

Valeurs possibles : 0 ou 1

Détermine si le statut issu de MV_INTERVENANT est AUTRE ou bien tout autre statut.

### statut_intervenant_autre

Valeurs possibles : 0 ou 1

Détermine si le statut de INTERVENANT est AUTRE ou bien tout autre statut.

### statuts_identiques

Valeurs possibles : 0 ou 1

Détermine si les statuts issus de MV_INTERVENANT et INTERVENANT sont identiques ou non

### intervenant_local

Valeurs possibles : 0 ou 1

Détermine si l'intervenant a été saisi directement dans OSE, auquel cas il ne devra pas être impacté par la synchro, 
ou bien s'il provient d'un précédent import.

### statut_deja_utilise

Valeurs possibles : 0 ou 1

Détermine si le statut issu de MV_INTERVENANT est déjà affecté à l'intervenant dans une autre fiche ou non.

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

### diff_statut_autre

Valeurs possibles : 0 à n

Nombre de lignes où le statut issu de MV_INTERVENANT est à la fois égal à AUTRE et différent de celui issu de INTERVENANT.




## Liste des scénarios

### a

Données présentes uniquement dans le connecteur : à insérer donc.

### c

Une ou plusieurs lignes, et les statuts coincident tous.

### d

Une seule ligne, mais le statut diffère entre la source et la table.

### e

Une ligne en source et plusieurs en table, avec une ligne dont le statut coïncide et pas les autres.

### f

Une ligne en source et plusieurs en table, dont aucun statut ne coïncide avec le statut source.

### g

Plusieures lignes en source, une seule en destination de même statut qu'en source et aucune ligne en source de statut AUTRE.

### h

Plusieures lignes en source, une seule en destination de même statut qu'en source et une ligne en source de statut AUTRE.

### i

Plusieurs lignes de part et d'autre, aucun statut AUTRE en source

### j

Plusieurs lignes de part et d'autre, un statut AUTRE en source

### ...

Tous les scénarios ne sont pas écrits. Il en manque et ceux listés ci-dessus vont probablement évoluer au gré des besoins.


## Reste à faire :

- Figer la liste des scénarios
- Pour chaque scénario, identifier les sous-scenarios ligne par ligne
- Pour chaque scénario et sous-scénarion, identifier l'action à mener
- Dropper les lignes inutiles
- Si plusieurs lignes sources correspondent à une même ligne destination :
  - identifier les valeurs identiques
  - identifier les valeurs différentes (discipline, structure, etc) et prendre à la place la valeur déjà présente dans INTERVENANT
- Ecrire le reste de la vue source
- Prévoir un système de log de syncgro pour savoir pourquoi telle ou telle fiche ne s'est pas synchronisée totalement (données sources multiples, plus de source alors qu'il y a des données dans OSE, etc.)
