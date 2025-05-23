<template>
    <h1 class="page-header">Workflow</h1>
    <VueDraggable ref="el" v-model="etapes" direction="vertical" :animation="150" handle=".drag-handle" :scroll="true" @end="tri">
    <div v-for="(etape,index) in etapes">
        <h3><a v-if="canEdit" class="drag-handle" href="#" title="Vous pouvez ordonnancer les étapes du workflow, sous condition qu'il n'y ai pas de blocage ou de dépendance bloquante"><i class="fas fa-arrows-up-down ui-sortable-handle"></i></a> {{ etape.libelleAutres }}</h3>
        <div class="dependances">
            <table v-if="etape.dependances.length" class="table table-bordered">
                <tr>
                    <th style="width:60%">Étape{{ etape.dependances.length > 1 ? 's' : '' }}
                        antérieure{{ etape.dependances.length > 1 ? 's' : '' }}
                    </th>
                    <th style="width:20%"><abbr
                        title="Périmètre de franchissement à l'échelle d'une composante ou bien de l'établissement
Si composante, on se fiche de ce qui se passe chez les autres
Si établissement, tout compte">Périmètre</abbr>
                    </th>
                    <th style="width:20%"><abbr title="Si désactivé, alors aucun test n'est effectué

Si débuté alors l'étape devra être franchie à plus de 0%
Par exemple au moins 1h de service devra être saisie

Si partiel: l'étape peut avoir été franchie à 100 sur au moins un élément
Par exemple si on a plusieurs contrats ou avenants, si l'un d'entre eux est terminé alors ça passe

Si intégral: l'ensemble des éléments de l'étape doivent avoir été franchis
Par exemple si on a plusieurs contrat et avenants, tous doivent être terminés">Avancement</abbr>
                    </th>
                    <th style="width:20%"><abbr title="Filtrage éventuel par type d'intervenant">Intervenant</abbr></th>
                </tr>
                <tr v-for="d in etape.dependances" :class="{ 'inactive': !d.active }">
                    <td>
                        <a v-if="canEdit"
                           :href="saisieUrl + '/' + etape.id + '/' + d.id"
                           @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>
                        <a v-if="canEdit" href=""
                           class="ajax-modal" data-event="dependances-suppression"><i class="fas fa-trash-can"></i></a>
                        {{ d.etapePrecedante.libelleAutres }}
                    </td>
                    <td class="attrib"><abbr :title="perimetreDescription(d.perimetre.code)">{{
                            d.perimetre.libelle
                        }}</abbr></td>
                    <td class="attrib"><abbr
                        :title="avancementDescription(d.avancement)">{{ avancementLibelle(d.avancement) }}</abbr>
                    </td>
                    <td class="attrib" v-if="d.typeIntervenant">{{ d.typeIntervenant.libelle }}</td>
                    <td class="attrib" v-else><abbr title="Aucun filtre par type d'intervenant"
                                                    class="type_intervenant_tous">Tous</abbr></td>
                </tr>
            </table>
            <div v-else class="alert alert-warning">Pas de dépendance</div>

            <a v-if="canEdit && index > 0" :href="saisieUrl + '/' + etape.id" class="btn btn-primary btn-sm"
               @click.prevent="saisie">
                <i class="fas fa-plus"></i> Ajouter une dépendance</a>

        </div>
        <br />
    </div>
    </VueDraggable>

</template>
<script setup>
import {ref, onMounted, defineProps} from 'vue';
import { VueDraggable } from 'vue-draggable-plus'

const props = defineProps({
    canEdit: {type: Boolean},
});

const etapes = ref([]);

const load = () => {
    unicaenVue.axios.get(
        unicaenVue.url('workflow/administration/data')
    ).then(response => {
        etapes.value = response.data;
    });
};

const saisieUrl = unicaenVue.url('workflow/administration/saisie-dependance');

const saisie = (event) => {
    modAjax(event.currentTarget, (widget) => {
        load();
    });
}

const tri = (event) => {
    let etapesCodes = [];
    for(const etapeIndex in etapes.value){
        console.log(etapes.value[etapeIndex].code);
    }
}

const perimetreDescription = (perimetre) => {
    switch (perimetre) {
        case 'composante':
            return 'La règle de franchissement est limitée à la composante courante, le franchissement dans les autres structures n\'est pas pris en compte';
        case 'etablissement':
            return 'La règle s\'applique quelle que soit la structure des données servant de base de calcul';
    }
}


const avancementDescription = (avancement) => {
    switch (avancement) {
        case 0:
            return 'Aucun contrôle de franchissement ne sera effectué, l\'étape précédante doit juste être accessible pour que la règle soit validée';
        case 1:
            return 'L\'étape précédante doit être accessible et franchie à plus de 0%';
        case 2:
            return 'L\'étape précédante doit avoir un de ses items au moins franchi à 100% (un contrat par exemple)';
        case 3:
            return 'L\'étape précédante doit être franchie à 100%';
    }
}


const avancementLibelle = (avancement) => {
    switch (avancement) {
        case 0:
            return 'Aucun contrôle';
        case 1:
            return 'Débuté';
        case 2:
            return 'Partiel';
        case 3:
            return 'Intégral';
    }
}


onMounted(() => {
    load();
});

</script>
<style scoped>

td a {
    padding: 3px;
}

th {
    border: 1px rgb(224, 224, 224) solid;
    padding: .5em;
}

td {
    border: 1px rgb(224, 224, 224) solid;
    padding: .5em;
}

.type_intervenant_tous {
    color: gray;
    font-style: italic;
}

.dependances {
    width: 60em;
    margin-left: 5em;
}

.dependances .attrib {
    text-align: center;
    width: 7em;
}

.dependances .actions {
    text-align: center;
    width: 1em;
    text-wrap: nowrap;
}

.dependances tr.inactive {
    opacity: .3;
    background-color: #eee;
}

</style>