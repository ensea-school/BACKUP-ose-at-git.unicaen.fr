# Plafonds

## Principes généraux

Les plafonds permettent de cadrer la saisie des services (et du référentiel)
en fixant des règles à plusieurs niveaux.

Un plafond se caractérise par un numéro qui lui est spécifique, un libellé, un message d'avertissement,
un périmètre et une requête SQL qui permet de le calculer.

Chaque plafond peut être activé ou non de plusieurs manières :

- Désactivé : le plafond n'est pas mis en œuvre
- Indicateur : le plafond n'est pas affiché, il n'est utilisé que pour les indicateurs
- Informatif : s'il y a dépassement, OSE vous l'indiquera, sans pour autant bloquer la saisie des heures
- Bloquant : L'application refusera d'enregistrer toute heure qui entraînerait le dépassement du plafond

Les plafonds ont un périmètre, dont voici la liste :

- Composante : le calcul s'effectue à l'échelle de la composante, configurable par composante
- Intervenant : idem, mais au niveau de chaque intervenant, comfigurable par statut
- Elément : le plafond fera des tests au niveau de l'élément pédagogique, confogirable par élément pédagogique
- Volume horaire : tests effectués par volume horaire (15h CM en Maths par exempel), configurable par élément
  pédagogique
  et par type d'intervention
- Référentiel : calcul fait par fonction référentielle pour chaque intervenant, configurable par fonction référentielle
- Type de mission : calcul fait par type de mission, configurable par type de mission

Enfin, chaque plafond peut être calculé selon deux contextes différents :

- Prévisionnel : valable en saisie de service prévisionnel
- Réalisé : valable pour le service réalisé

## Visualisation

Pour chaque plafond, deux indicateurs sont automatiquement créés :

- un pour le prévisionnel
- un pour le réalisé

De plus, au niveau de la fiche de saisie de services, un encart avec des jauges indique, par plafond, où en est
l'intervenant.
Seuls les plafonds informatifs ou bloquants sont affichés.

Les jauges peuvent prendre plusieurs couleurs. Si le plafond est informatif, la jauge sera verte si le plafond n'est pas
dépassé,
et orange s'il y a dépassement. Si le plafond est bloquant, la jauge sera bleue, ou rouge en cas de dépassement.

## Administration

Les plafonds peuvent être créés/modifiés/supprimés par un administrateur.
Leur application (désactivé, informatif ou bloquant) pourra être également gérée par un administratif.

Le guide administrateur vous donnera la marche à suivre de manière plus détaillée pour configurer vos plafonds.

## Mise en œuvre technique

Dans OSE quelques plafonds vous sont proposés en standard. Ils sont désactivés par défaut.
Il vous revient de les activer ou non selon vos besoins.

Mais vous avez aussi la possibilité de créer vos propres plafonds.

Concrètement, un plafond se compose :

- d'un numéro unique qui permet de distinguer facilement ce dernier
- d'un libellé
- d'un message qui s'affichera à droite des jauges et en alerte si dépassement à la saisie de service ou de référentiel
- de son périmètre
- d'une requête SQL qui permet de le calculer

Le message peut contenir une variable ":sujet" qui sera remplacé par la valeur correspondante selo le contexte.
Par exemple, pour un plafond de périmètre intervenant, ":sujet" sera remplacé par le libellé du statut de l'intervenant.
Pour un périmètre Composante, ":sujet" sera remplacé par le libellé court de la composante concernée.
Idem avec le libellé des fonctions référentielles, des libellés d'éléments pédagogiqures, etc.

Les requêtes SQL sont normalisées par périmètre, c'est-à-dire qu'elles doivent comporter des colonnes bien spécifiques,
dépendant du périmètre. Les requêtes que
vous écrirez peuvent comporter plus de colonnes, mais celles listées ci-dessous sont obligatoires, sauf PLAFOND dans
certains cas.

Liste des colonnes spécifiques par périmètre :

### Composante

Le calcul se fait par structure.
La saisie des valeurs des plafonds se fait dans la page d'administration des structures.

| Colonne                | Type (ou référence)       | Description                               |
|------------------------|---------------------------|-------------------------------------------|
| ANNEE_ID               | => ANNEE.ID               | Année universitaire                       | 
| STRUCTURE_ID           | => STRUCTURE.ID           | Identifiant de la composante concernée    | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé                   |
| PLAFOND*               | FLOAT NOT NULL            | Valeur du plafond en heures               |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, STRUCTURE_ID, TYPE_VOLUME_HORAIRE_ID]

(*) La colonne PLAFOND est facultative et ne doit être fournie que si ce plafond dépend d'un calcul. A défaut la valeur retenue est celle saisie dans l'IHM d'
administration des structures pour le plafond donné.

### Intervenant

Le calcul se fait par intervenant.
La saisie des valeurs des plafonds se fait dans la page d'administration des statuts.

| Colonne                | Type (ou référence)       | Description                               |
|------------------------|---------------------------|-------------------------------------------|
| ANNEE_ID               | => ANNEE.ID               | Année universitaire                       | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé                   |
| INTERVENANT_ID         | => INTERVENANT.ID         | Identifiant de l'intervenant concerné     | 
| PLAFOND*               | FLOAT NOT NULL            | Valeur du plafond en heures               |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, INTERVENANT_ID]

(*) La colonne PLAFOND est facultative et ne doit être fournie que si ce plafond dépend d'un calcul (% du service dû,
etc.).
A défaut la valeur retenue est celle renseignée dans l'IHM d'administration du statut relatif à l'intervenant pour le
plafond donné.

#### Exemple type de requête SQL

La requête ci-dessous calcule le plafond selon le nombre d'heures total HETD par intervenant

```sql
SELECT
  i.annee_id                             annee_id,
  fr.type_volume_horaire_id              type_volume_horaire_id,
  i.id                                   intervenant_id,
  fr.total                               heures
FROM
  intervenant                     i
  JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
  JOIN formule_resultat_intervenant fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
```

### Référentiel

Le calcul se fait par intervenant ET par fonction référentielle. 

| Colonne                 | Type (ou référence)        | Description                                    |
|-------------------------|----------------------------|------------------------------------------------|
| ANNEE_ID                | => ANNEE.ID                | Année universitaire                            | 
| TYPE_VOLUME_HORAIRE_ID  | => TYPE_VOLUME_HORAIRE.ID  | Prévisionnel ou réalisé                        |
| FONCTION_REFERENTIEL_ID | => FONCTION_REFERENTIEL.ID | Identifiant de la fonction référentielle visée |
| INTERVENANT_ID          | => INTERVENANT.ID          | Identifiant de l'intervenant concerné          |
| PLAFOND*                | FLOAT NOT NULL             | Valeur du plafond en heures                    |
| HEURES                  | FLOAT NOT NULL             | Heures calculées pour l'intervenant donné      |

Attention : il doit y avoir unicité de la
clé [ANNEE_ID, TYPE_VOLUME_HORAIRE_ID, FONCTION_REFERENTIEL_ID, INTERVENANT_ID]

(*) La colonne PLAFOND est facultative et ne doit être fournie que si ce plafond, dépend d'un calcul spécifique.
A défaut, la valeur retenue sera celle saisie dans l'IHM d'administration de la fonction référentielle pour le plafond
donné.

#### Exemple type de requête SQL pour les plafonds par fonctions référentielles

La requête ci-dessous calcule le nombre d'heures réelles de référentiel par intervenant & par fonction

```sql
SELECT
  fr.annee_id                annee_id,
  vhr.type_volume_horaire_id type_volume_horaire_id,
  fr.id                      fonction_referentiel_id,
  sr.intervenant_id          intervenant_id,
  sum(vhr.heures)            heures
FROM
  volume_horaire_ref vhr
  JOIN service_referentiel sr ON sr.id = vhr.service_referentiel_id
  JOIN fonction_referentiel fr ON fr.id = sr.fonction_id
GROUP BY
  fr.annee_id,
  vhr.type_volume_horaire_id,
  sr.intervenant_id,
  fr.id
```


### Types de missions

| Colonne                | Type (ou référence)       | Description                               |
|------------------------|---------------------------|-------------------------------------------|
| ANNEE_ID               | => ANNEE.ID               | Année universitaire                       | 
| TYPE_MISSION_ID        | => TYPE_MISSION.ID        | Identifiant du type de mission visé       | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé                   |
| PLAFOND*               | FLOAT NOT NULL            | Valeur du plafond en heures               |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, TYPE_MISSION_ID, TYPE_VOLUME_HORAIRE_ID]

* Si la colonne PLAFOND n'est pas fournie par la requête, alors la valeur du plafond sera celle saisie dans OSE pour le
  type de mission donné

(*) La colonne PLAFOND est facultative et ne doit être fournie que si ce plafond dépend d'un calcul (% du service dû,
etc.). A défaut la valeur retenue est
celle saisie dans l'IHM d'administration du type de mission pour le plafond donné.

### Elément

| Colonne                | Type (ou référence)       | Description                                                    |
|------------------------|---------------------------|----------------------------------------------------------------|
| ANNEE_ID               | => ANNEE.ID               | Année universitaire                                            | 
| ELEMENT_PEDAGOGIQUE_ID | => ELEMENT_PEDAGOGIQUE.ID | Identifiant de l'élément pédagogique concerné                  | 
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé                                        |
| PLAFOND_ETAT_ID        | => PLAFOND_ETAT.ID        | Etat du plafond (désactivé, indicateur, informatif ou bloquant |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures                                    |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné                      |

Attention : il doit y avoir unicité de la clé [ANNEE_ID, ELEMENT_PEDAGOGIQUE_ID, TYPE_VOLUME_HORAIRE_ID]

### Volume horaire

| Colonne                | Type (ou référence)       | Description                                                    |
|------------------------|---------------------------|----------------------------------------------------------------|
| ANNEE_ID               | => ANNEE.ID               | Année universitaire                                            | 
| ELEMENT_PEDAGOGIQUE_ID | => ELEMENT_PEDAGOGIQUE.ID | Identifiant de l'élément pédagogique concerné                  | 
| TYPE_INTERVENTION_ID   | => TYPE_INTERVENTION.ID   | Identifiant du type d'intervention (CM, TED, TP, etc.)         |
| TYPE_VOLUME_HORAIRE_ID | => TYPE_VOLUME_HORAIRE.ID | Prévisionnel ou réalisé                                        |
| PLAFOND_ETAT_ID        | => PLAFOND_ETAT.ID        | Etat du plafond (désactivé, indicateur, informatif ou bloquant |
| PLAFOND                | FLOAT NOT NULL            | Valeur du plafond en heures                                    |
| HEURES                 | FLOAT NOT NULL            | Heures calculées pour l'intervenant donné                      |

Attention : il doit y avoir unicité de la
clé [ANNEE_ID, ELEMENT_PEDAGOGIQUE_ID, TYPE_INTERVENTION_ID, TYPE_VOLUME_HORAIRE_ID]
