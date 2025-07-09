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
                    :title="formatWhyNonAtteignable(etape)">

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
                    <span class="float-end">
                        <a v-if="etapeHasStructures(etape)" data-bs-toggle="collapse"
                           :href="'#fdr-structures-'+etape.code" title="Afficher le détail par composantes">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="fas fa-eye"></span>&nbsp;
                        </a>

                        <span v-if="etape.realisationPourc == 0 && etape.atteignable"
                              class="number number-positif text-danger"
                              title="À faire">0%</span>

                        <span v-if="etape.realisationPourc == 0 && !etape.atteignable"
                              class="number number-positif text-danger fas fa-xmark"
                              title="Non fait"></span>

                        <span v-if="etape.realisationPourc > 0 && etape.realisationPourc < 100"
                              class="number number-positif text-warning"
                              title="En cours">{{ etape.realisationPourc }}%</span>

                        <span v-if="etape.realisationPourc == 100"
                              class="number number-positif text-success fas fa-check"
                              title="Fait"></span>
                    </span>

                    <div v-if="etapeHasStructures(etape)" class="row collapse" :id="'fdr-structures-'+etape.code">
                        <div class="col-md-4 col-md-offset-8">
                            <ul class="list-group">
                                <li v-for="etapeStructure in etape.structures" class="list-group-item">
                                    <span>{{ etapeStructure.libelle }}</span>
                                    <span class="float-end">
                                        <span v-if="etapeStructure.realisationPourc == 0 && etape.atteignable"
                                              class="number number-positif text-danger"
                                              title="À faire">0%</span>

                                        <span v-if="etapeStructure.realisationPourc == 0 && !etape.atteignable"
                                              class="number number-positif text-danger fas fa-xmark"
                                              title="Non fait"></span>

                                        <span
                                            v-if="etapeStructure.realisationPourc > 0 && etapeStructure.realisationPourc < 100"
                                            class="number number-positif text-warning"
                                            title="En cours">{{
                                                etapeStructure.realisationPourc
                                            }}%</span>

                                        <span v-if="etapeStructure.realisationPourc == 100"
                                              class="number number-positif text-success fas fa-check"
                                              title="Fait"></span>
                                    </span>
                                </li>
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
    if (etape.atteignable || etape.whyNonAtteignable.length == 0) {
        return "";
    }

    let explication = "Non atteignable :";

    for (const index in etape.whyNonAtteignable) {
        explication += "\n    - " + etape.whyNonAtteignable[index];
    }

    return explication;
}

const etapeHasStructures = (etape) => {
    let count = 0;
    for (const i in etape.structures) {
        count++;
        if (count > 1){
            return true;
        }
    }
    return false;
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