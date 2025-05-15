<template>
    <h1 class="page-header">Workflow</h1>

    <div v-for="etape in etapes">
        <h3>{{ etape.libelleAutres }}</h3>
        <div class="dependances">
            <table v-if="etape.dependances" class="table table-bordered">
                <tr>
                    <th>Étape(s) antérieure(s)</th>
                    <th><abbr
                        title="Le test ne se fait qu'au sein d'une même composante ou sur des étapes non attachées à des composantes">Locale</abbr>
                    </th>
                    <th><abbr title="Franchissement impératif pour toutes les composantes concernées">Intégrale</abbr>
                    </th>
                    <th><abbr
                        title="La dépendance impose à l'étape précédente d'être franchie à plus de 0%">Obligatoire</abbr>
                    </th>
                    <th><abbr title="L'étape peut n'être que partiellement franchie">Partielle</abbr></th>
                    <th>Intervenant</th>
                </tr>
                <tr v-for="d in etape.dependances">
                <td>
                    <a v-if="canEdit" href=""
                       class="ajax-modal" data-event="dependances-saisie"><i
                        class="fas fa-pen-to-square"></i></a>
                    <a v-if="canEdit" href=""
                       class="ajax-modal" data-event="dependances-suppression"><i class="fas fa-trash-can"></i></a>
                    {{ d }}
                </td>
                <td class="attrib"></td>
                <td class="attrib"></td>
                <td class="attrib"></td>
                <td class="attrib"></td>
                <td class="attrib"></td>
                </tr>
            </table>
            <div v-else class="alert alert-warning">Pas de dépendance</div>

            <a v-if="canEdit" href="" class="btn btn-primary btn-sm ajax-modal" data-event="dependances-saisie"><i
                class="fas fa-plus"></i> Ajouter une dépendance</a>

        </div>
    </div>

    <pre>
    {{ etapes }}
</pre>
</template>
<script setup>
import {ref, onMounted, defineProps} from 'vue';

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

onMounted(() => {
    load();
});

</script>
<style scoped>

.dependances {
    width: 60em;
    margin-left: 5em;
}

.dependances .attrib {
    text-align: center;
    width: 7em;
}

.dependances tr.inactive {
    opacity: .3;
    background-color: #eee;
}

</style>