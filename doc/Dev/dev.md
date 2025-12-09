# Installation de l'environnement de développement de OSE

## Si vous utilisez dev-infra
```bash
git clone https://git.unicaen.fr/open-source/OSE.git ose-dev
cd ose-dev
cp config.local.php.default config.local.php
cp .env.example .env
cp doc/Docker/dev-infra/docker-compose.yaml .
make start
```

- Configurez votre [config.local.php](../config.local.php)
- Configurez votre [.env](../.env).
- [Configurez & redémarrez votre DevInfra](Docker/dev-infra/dev-infra.md)
- Accédez à l'application : [https://ose-dev.localhost.unicaen.fr](https://ose-dev.localhost.unicaen.fr)

## Si vous n'utilisez pas DevInfra

```bash
git clone https://git.unicaen.fr/open-source/OSE.git ose-dev
cd ose-dev
cp config.local.php.default config.local.php 
cp .env.example .env
cp docker-compose.yaml.example docker-compose.yaml
make start
```

- Configurez votre [config.local.php](../config.local.php)
- Configurez votre [.env](../.env).
- Accédez à l'application : [http://localhost:8080](http://localhost:8080)

## Commandes usuelles

OSE est doté d'un Makefile qui vous permettra de lancer la plupart des commandes usuelles.

```bash
# affiche la doc du makefile
make help
```

## Préparation pour le déploiement

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


Sites officiels :
- https://vuejs.org/
- https://vitejs.dev/

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

// URL à utiliser. Ici, on la construit à partir d'une route avec unicaenVue.url
let url = unicaenVue.url('mission/modifier/:intervenant', {intervenant: 1000000}); 

// on utilise axios pour poster et récupérer le résultat
unicaenVue.axios.post(
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
        return new AxiosModel($output);
    }
```