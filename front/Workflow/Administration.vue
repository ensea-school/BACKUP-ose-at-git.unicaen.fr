<template>
    <h1 class="page-header">Workflow</h1>
    <VueDraggable ref="el" v-model="etapes" direction="vertical" :animation="150" handle=".drag-handle" :scroll="true"
                  @end="tri">
        <div v-for="(etape,index) in etapes">
            <h3><a v-if="canEdit" class="drag-handle" href="#"
                   title="Vous pouvez ordonnancer les étapes du workflow, sous condition qu'il n'y ai pas de blocage ou de dépendance bloquante"><i
                class="fas fa-arrows-up-down ui-sortable-handle"></i></a>
                <a v-if="canEdit" class="perso-btn"
                   title="Personnalisation des libellés & messages"
                   :href="personnalisationUrl + '/' + etape.id"
                   @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>
                {{ etape.libelleAutres }}</h3>
            <div class="dependances">
                <table v-if="etape.dependances.length" class="table table-bordered">
                    <thead>
                    <tr>
                        <th style="width:45%">Étape{{ etape.dependances.length > 1 ? 's' : '' }}
                            antérieure{{ etape.dependances.length > 1 ? 's' : '' }}
                        </th>
                        <th style="width:15%"><abbr
                            title="Périmètre de franchissement à l'échelle d'une composante ou bien de l'établissement
    Si composante, on se fiche de ce qui se passe chez les autres
    Si établissement, tout compte">Périmètre</abbr>
                        </th>
                        <th style="width:45%">Règle de franchissement</th>
                        <th style="width:15%">
                            <abbr title="Filtrage éventuel par type d'intervenant">Intervenant</abbr>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="d in etape.dependances" :class="{ 'inactive': !d.active }">
                        <td>
                            <a v-if="canEdit"
                               :href="saisieUrl + '/' + etape.id + '/' + d.id"
                               title="Modification de la dépendance"
                               @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>
                            <a v-if="canEdit" :href="suppressionUrl + '/' + d.id"
                               data-content="Êtes-vous sur de vouloir supprimer la règle de dépendance ?"
                               data-title="Suppression de la dépendance"
                               title="Suppression de la dépendance"
                               @click.prevent="suppression"><i class="fas fa-trash-can"></i></a>

                            {{ d.etapePrecedante.libelleAutres }}
                        </td>
                        <td class="attrib"><abbr :title="perimetreDescription(d.perimetre.code)">
                            {{ d.perimetre.libelle }}
                        </abbr></td>
                        <td>{{ d.avancementLibelle }}</td>
                        <td class="attrib" v-if="d.typeIntervenant">{{ d.typeIntervenant.libelle }}</td>
                        <td class="attrib" v-else><abbr title="Aucun filtre par type d'intervenant"
                                                        class="type_intervenant_tous">Tous</abbr></td>
                    </tr>
                    </tbody>
                </table>
                <div v-else class="alert alert-warning">Pas de dépendance</div>

                <a v-if="canEdit && index > 0" :href="saisieUrl + '/' + etape.id" class="btn btn-primary btn-sm"
                   @click.prevent="saisie">
                    <i class="fas fa-plus"></i> Ajouter une dépendance</a>
            </div>
            <br/>
        </div>
    </VueDraggable>

</template>
<script setup>
import {ref, onMounted, defineProps} from 'vue';
import {VueDraggable} from 'vue-draggable-plus'

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
const suppressionUrl = unicaenVue.url('workflow/administration/suppression-dependance');
const personnalisationUrl = unicaenVue.url('workflow/administration/modification-etape');

const saisie = (event) => {
    modAjax(event.currentTarget, (widget) => {
        load();
    });
}

const suppression = (event) => {
    popConfirm(event.currentTarget, (response) => {
        load();
    });
}

const tri = (event) => {
    let etapesCodes = [];
    for (const etapeIndex in etapes.value) {
        etapesCodes.push(etapes.value[etapeIndex].code);
    }

    unicaenVue.axios.post(
        unicaenVue.url('workflow/administration/tri'),
        {etapes: etapesCodes}
    ).then(response => {
        etapes.value = response.data;
    });
}

const perimetreDescription = (perimetre) => {
    switch (perimetre) {
        case 'composante':
            return 'La règle de franchissement est limitée à la composante courante, le franchissement dans les autres structures n\'est pas pris en compte';
        case 'etablissement':
            return 'La règle s\'applique quelle que soit la structure des données servant de base de calcul';
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
    margin-left: 5em;
}

.dependances thead th {
    font-weight: 100;
    padding-top: 0px;
    padding-bottom: 0px;
}

.dependances .attrib {
    text-align: center;

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

.drag-handle {
    font-size: 12pt;
}

.perso-btn {
    margin-left: 5px;
    font-size: 12pt;
}

</style>