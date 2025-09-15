<template>
    <div><a
        v-if="etapeLibelle"
        class="btn btn-primary"
        title="Cliquez sur ce bouton pour accéder à la page correspondant à cette étape de la feuille de route"
        :href="etapeUrl">{{ etapeLibelle}}</a></div>
</template>
<script setup>

import {defineProps, onMounted, ref} from "vue";

const props = defineProps({
    intervenant: {type: Number},
    etape: {type: String},
});

const etapeUrl = ref("");
const etapeLibelle = ref("");

const load = () => {
    unicaenVue.axios.post(
        unicaenVue.url(
            'workflow/feuille-de-route-nav/:intervenant',
            {intervenant: props.intervenant}
        ),
        {etape: props.etape}
    ).then(response => {
        if (response.data) {
            etapeLibelle.value = response.data.libelle;
            etapeUrl.value = response.data.url;
        }
    });
};

onMounted(() => {
    load();

    // écoute d'un événement pour pouvoir être chargé depuis l'extérieur
    window.addEventListener("Workflow/Nav.refresh", (event) => {
        load();
    });
});

</script>
<style scoped>

</style>