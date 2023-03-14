# Installation de l'environnement de développement de OSE

OSE a besoin de npm et node.js

NPM doit être installé sur la machine au moyen du gestionnaire de packages

Pour utiliser la version 19 de node sur Ubuntu, utiliser la commande :

```bash
curl -fsSL https://deb.nodesource.com/setup_19.x | sudo -E bash -
```

Reste à l'installer en passant par le gestionnaire de paquets de la distribution.
```bash
apt install npm
```

Attention : l'utilisation de npm doit se faire toujours depuis la machine hôte et pas à l'intérieur du conteneurDocker.

Il faut ensuite installer les dépendances (Vue, Vite, Sass, etc.)

Dans le dossier du projet :
```bash
npx npm install
```

Reste à démarrer Vite en mode dév :

```bash
npx vite dev
```

Ceci permet de faire du "hot loading". C'est-à-dire que les modifications du composant peuvent se voir dans le navigateur sans avoir à rafraichir la page.



## Commandes usuelles

### Mise à jour des dépendances Node

Le fichier package.json gère les dépendances et la config de NPM
Si on veut mettre à jour les dépendances (équivalent de composer update), il faut lancer

```bash
npx npm update
```

### Préparation pour le déploiement

Vite est un outil de développement web rapide qui permet de créer facilement
des projets web avec Vue.js ou autre.
Il offre un serveur de développement local, le rechargement en direct et la compilation en temps réel
et peut être configuré avec Babel et TypeScript.

Avec Vite, il faut lancer un build afin de générer les fichiers destinés à la production.

```bash
npx vite build
```

Les fichiers ainsi générés seront placés dans le répertoire public/dist.
Enfin, commitez le tout et testez avant de déployer!!!



## Création de composants Vue

Ose embarque BootstrapVue.
A privilégier pour utiliser des composants Boottrap.

Sites officiels :
- https://vuejs.org/
- https://vitejs.dev/
- https://bootstrap-vue.org/

Doc Vue de Stéphane : 
- https://git.unicaen.fr/bouvry/presentation-dev/-/blob/master/src/vuejs.md

Les composants doivent être placés dnas le répertoire front/components.
Ils peuvent être placés dans des sous-répertoires.

### Ajouter un composant Vue depuis une view Laminas

Une aide de vue permet d'ajouter un composant.

Exemple :

```php
echo $this->vue('mission/liste', [
    'intervenant'   => $intervenant->getId(),
    'canAddMission' => $canAddMission,
    'options'       => [
        'typeMission' => $missionForm->get('typeMission')->getValueOptions(),
        'structure'   => $missionForm->get('structure')->getValueOptions(),
        'tauxRemu'    => $missionForm->get('tauxRemu')->getValueOptions(),
    ],
]);
```

Si on veut insérer plusieurs composants, il faut faire :

```php
// on démarre une vue-app
echo $this->vue()->begin();


// affichage des composants
echo $this->vue()->component('monPremierComposant', [/* tableau de propriétés */]);
echo $this->vue()->component('monSecondComposant', [/* tableau de propriétés */]);

// on termine la vue-app
echo $this->vue()->end();
```

L'exemple ci-dessus charge et affiche le composant situé dans le fichier front/components/Mission/Liste.vue
On lui transmet les propriétés intervenant, canAddMission et un tableau d'options.

### Utiliser un composant Vue depuis un autre :
<template>
    <!-- Utilisation du "popover" comme sous-composant -->
    <popover title="Mon titre cool"></popover>
</template>

<script>

// import "absolu, depuis le répertoire /front/components
import popover from '@components/Application/Popover.vue';

// import relatif, depuis le répertoire du composant actuel
import popover from '../../Application/Popover.vue';

// import d'un composant dans le même répertoire que l'actuel
import popover from './Popover.vue';

// dans tous les cas, on a choisi d'appeler localement le composant "popover" 

export default {
    name: 'Mission',
    components: {
        popover // on déclare l'usage du composant ici
    }
}

</script>


## Communication front/serveur en AJAX avec axios

Site officiel : https://axios-http.com/fr/docs/intro

Depuis un morceau de code javascript :
```js

// Données à poster
let data = {
    'param1' => 'mon premier paramètre',
    'param2' => 'mon deuxième paramètre'
};

// URL à utiliser. Ici, on la construit à partir d'une route avec Util.url
let url = Util.url('mission/modifier/:intervenant', {intervenant: 1000000}); 

// on utilise axios pour poster et récupérer le résultat
axios.post(
    url,
    data
).then((response) => {
    
    // récup des données renvoyées par le serveur
    console.log(response.data);
    
    // on peut aussi récupérer les messages envoyés par le flashMessenger :
    console.log(response.messages);
});
```

Depuis une action de contrôleur Laminas :

```php
    public function modifierAction()
    {
        // on récupère les données postées
        // le $this->params()->fromPost() ne fonctionne pas ici, il faut passer par axios, mais la syntaxe est identique
        $input = $this->axios()->fromPost();
        
        var_dump($input);

        // on crée un tableau de données de sortie
        $output = [
            'param1' => 'mon premier paramètre',
            'param2' => 'mon deuxième paramètre',
            // ...
        ];

        
        // Axios capte les messages envoyés par le flashMessenger et il les transfère au client
        $this->flashMessenger()->addSuccessMessage('Youpi, super!');

        // On utilise le plugin axios pour renvoyer $donnees en client qui récupèrera les valeurs dans le response.data
        return $this->axios()->send($output);
    }
```