<template>
    <h2>Feuille de route
        <a href="#" @click.prevent="refresh"
           style="font-size:12pt"
           title="Mettre à jour la feuille de route"
        >
            <i class="fas fa-arrows-rotate"></i>
        </a>
    </h2>

    <div class="feuille-de-route row">
        <div class="col-md-9">
            <ul v-if="feuilleDeRoute" class="list-group">

                <li v-for="etape in feuilleDeRoute"
                    :class="{'list-group-item': true, 'after-courante': !etape.atteignable, 'list-group-item-warning': etape.courante }"
                    title="$this->getWhyNonAtteignable($etape)">

                    <!-- Numéro d'étape -->
                    <span class="label label-primary">{{ etape.numero }}</span>

                    <!-- Libellé et lien éventuel -->
                    <a v-if="etape.url && etape.allowed"
                       title="Cliquez sur ce lien pour accéder à la page correspondant à cette étape" :href="etape.url">
                        <abbr v-if="etape.whyNonAtteignable.length > 0" :title="formatWhyNonAtteignable(etape)">{{
                                etape.libelle
                            }}</abbr><span v-else>{{ etape.libelle }}</span>
                    </a>
                    <span v-else>
                        <abbr v-if="etape.whyNonAtteignable.length > 0" :title="formatWhyNonAtteignable(etape)">{{
                                etape.libelle
                            }}</abbr><span v-else>{{ etape.libelle }}</span>
                    </span>

                    <!-- Indicateur -->
                    <span v-if="etape.realisationPourc == 0" title="À faire" class="text-danger float-end">
                        <span class="number number-positif">0%</span>
                    </span>

                    <span v-if="etape.realisationPourc == 100" title="Fait" class="text-success float-end">
                        <span class="text-success fas fa-check"></span>
                    </span>

                    <span v-if="etape.realisationPourc > 0 && etape.realisationPourc < 100"
                          title="En cours, cliquez pour afficher le détail par composante"
                          class="text-warning float-end"><a data-bs-toggle="collapse"
                                                            :href="'#collapse-'+etape.code+'-'+187"><span
                        class="fas fa-eye"></span> <span class="number number-positif">50%</span></a></span>
                    <div class="row collapse" :id="'collapse-'+etape.code+'-'+187">
                        <div class="col-md-4 col-md-offset-8">
                            <ul class="list-group">
                                <li class="list-group-item"><span title="">Carré International</span><span
                                    title="à faire" class="text-danger float-end"><span
                                    class="number number-positif">0%</span></span></li>
                                <li class="list-group-item"><span title="">IAE Caen</span><span title="Fait"
                                                                                                class="text-success float-end"><span
                                    class="text-success fas fa-check"></span></span></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-else class="alert alert-info">
                La feuille de route ne comporte aucune étape
            </div>
        </div>

    </div>
</template>
<script setup>

import {defineProps, onMounted, ref} from "vue";

const props = defineProps({
    intervenant: {type: Number},
});

const feuilleDeRoute = ref([]);

const load = () => {
    unicaenVue.axios.get(
        unicaenVue.url('workflow/feuille-de-route-data/:intervenant', {intervenant: props.intervenant})
    ).then(response => {
        feuilleDeRoute.value = response.data;
    });
};

const refresh = () => {
    unicaenVue.axios.get(
        unicaenVue.url('workflow/feuille-de-route-refresh/:intervenant', {intervenant: props.intervenant})
    ).then(response => {
        feuilleDeRoute.value = response.data;
    });
};

const formatWhyNonAtteignable = (etape) => {
    if (etape.atteignable || etape.whyNonAtteignable.length == 0){
        return "";
    }

    let explication = "Non atteignable :";

    for( const index in etape.whyNonAtteignable){
        explication += "\n    - " + etape.whyNonAtteignable[index];
    }

    return explication;
}

onMounted(() => {
    load();
});

</script>
<style scoped>

.feuille-de-route li.after-courante {
    opacity: .5;
    background-color: #eee;
}

.feuille-de-route li span.label {
    margin-right: 1em;
    display: block;
    float: left;
    min-width: 3em;
    line-height: 1.4em;
}

.feuille-de-route li div.row {
    padding-top: 1em;
}

.feuille-de-route li div ul {
    margin-bottom: .3em;
}

.feuille-de-route li div ul li {
    padding-top: .2em;
    padding-bottom: .2em;
}

.feuille-de-route li div ul li span {
    margin-left: -0.1em;
}

.feuille-de-route li div ul li span.pull-right {
    margin-left: 1em;
}

</style>