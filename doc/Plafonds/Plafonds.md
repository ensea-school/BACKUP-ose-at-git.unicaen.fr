# Plafonds

## Principes généraux

Les plafonds permettent de cadrer la saisie des services (et du référentiel) 
en fixant des règles à plusieurs niveaux.

Chaque plafond peut être activé ou non de plusieures manières :
- Désactivé : le plafond n'est pas mis en œuvre
- Indicateur : le plafond n'est pas affiché, il n'est utilisé que pour les indicateurs
- Informatif : s'il y a dépassement, OSE vous l'indiquera, sans pour autant bloquer la saisie des heures
- Bloquant : L'application refusera d'enregistrer toute heure qui entrainerait le dépassement du plafond

Les plafonds ont un périmètre, dont voici la liste :
- Composante : le calcul s'effectue à l'échelle de la composante
- Intervenant : idem, mais au niveau de chaque intervenant
- Elément : le plafond fera des tests au niveau de l'élément pédagogique
- Volume horaire : tests effectués par volume horaire (15h CM en Maths par exempel)
- Référentiel : calcul fait par fonction référentielle

Enfin, chaque plafond peut être calculé selon deux contextes différents :
- Prévisionnel : valable en saisie de service prévisionnel
- Réalisé : valable pour le service réalisé

## Visualisation

Pour chaque plafond, deux indicateurs sont automatiquement créés :
- un pour le prévisionnel
- un pour le réalisé

Il y a en plus de cela : 

- Pour le plafond par intervenant :
une jauge sur la page de saisie de service permet de savoir où on en est.

- Pour le plafond par volume horaire :
une jauge est affichée dans le formulaire de la saisie de service, au plus près des heures saisies.

## Administration

Les plafonds peuvent être créés/modifiés/supprimés par un adfministrateurs

Leur application (désactivé, informatif ou bloquant) pourra être gérés par un administratif

Le guide administrateur vous donnera la marche à suivre de manière plus détaillée pour configurer vos plafonds.  

## Mise en œuvre technique

Dans OSE queqques plafonds vous sont proposés en standard. Ils sont désactivés par défaut.
Il vous revient de les activer ou non selon vos besoins.

Mais vous avez aussi la possibilité de créer vos propres plafonds.

Concrètement, un plafond se compose :
- d'un code unique qui permet de distinguer facilement ce dernier
- d'un libellé
- de son périmètre
- d'une requête SQL qui permet de le calculer

Les requêtes SQL sont normalisées par périmètre, c'est-à-dire qu'elles doivent comporter des colonnes
bien spécifiques, dépendant du périmètre.
Les requêtes que vous écrirez peuvent comporter plus de colonnes, mais celles listées ci-dessous sont obligatoires.

Liste des colonnes spécifiques par périmètre :

### Composante

| Colonne | Type (ou référence) | Description |
| ------- | ------------------- | ----------- |
| ANNEE_ID               | => ANNEE.ID               | Année universitaire | 
| STRUCTURE_ID           | => STRUCTURE.ID           | Identifiant de la composante concernée | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, STRUCTURE_ID, TYPE_VOLUME_HORAIRE_ID]   



### Intervenant

| Colonne | Type (ou référence) | Description |
| ------- | ------------------- | ----------- |
| ANNEE_ID               | => ANNEE.ID               | Année universitaire | 
| INTERVENANT_ID         | => INTERVENANT.ID         | Identifiant de l'intervenant concerné | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, INTERVENANT_ID, TYPE_VOLUME_HORAIRE_ID]



### Elément

| Colonne | Type (ou référence) | Description |
| ------- | ------------------- | ----------- |
| ANNEE_ID               | => ANNEE.ID               | Année universitaire | 
| ELEMENT_PEDAGOGIQUE_ID | => ELEMENT_PEDAGOGIQUE.ID | Identifiant de l'élément pédagogique concerné | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, ELEMENT_PEDAGOGIQUE_ID, TYPE_VOLUME_HORAIRE_ID]



### Volume horaire

| Colonne | Type (ou référence) | Description |
| ------- | ------------------- | ----------- |
| ANNEE_ID               | => ANNEE.ID               | Année universitaire | 
| ELEMENT_PEDAGOGIQUE_ID | => ELEMENT_PEDAGOGIQUE.ID | Identifiant de l'élément pédagogique concerné | 
| TYPE_INTERVENTION_ID   | => TYPE_INTERVENTION.ID   | Identifiant du type d'intervention (CM, TED, TP, etc.) |
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, ELEMENT_PEDAGOGIQUE_ID, TYPE_INTERVENTION_ID, TYPE_VOLUME_HORAIRE_ID]



### Référentiel

| Colonne | Type (ou référence) | Description |
| ------- | ------------------- | ----------- |
| ANNEE_ID                | => ANNEE.ID                | Année universitaire | 
| FONCTION_REFERENTIEL_ID | => FONCTION_REFERENTIEL.ID | Identifiant de la fonction référentielle visée | 
| TYPE_VOLUME_HORAIRE_ID  | => TYPE_VOLUME_HORAIRE.ID  | Prévisionnel ou réalisé |
| PLAFOND                 | FLOAT NOT NULL             | Valeur du plafond en heures |
| HEURES                  | FLOAT NOT NULL             | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, FONCTION_REFERENTIEL_ID, TYPE_VOLUME_HORAIRE_ID]
