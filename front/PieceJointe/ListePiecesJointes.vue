<template>
    <!-- Affichage des messages -->
    <div
        v-for="(message, key) in datasPiecesJointes?.messagesPiecesJointes || []"
        :key="'msg-' + key"
        :class="'messenger alert alert-dismissible alert-' + message.type">
        <span class="fas fa-info-circle"></span>
        {{ message.text }}
        <button
            type="button"
            class="btn-close alert-dismissible"
            title="Fermer cette alerte"
            data-bs-dismiss="alert">
        </button>
    </div>

    <!-- Affichage des pièces jointes -->
    <pieceJointe
        v-if="datasPiecesJointes?.privileges"
        v-for="(pieceJointe, key) in datasPiecesJointes?.piecesJointes || []"
        :datas="pieceJointe"
        :privileges="datasPiecesJointes.privileges"
        :intervenant="intervenant"
        @refresh="getPiecesJointes"
    />
</template>

<script setup>

import {ref, onMounted, computed} from 'vue'
import pieceJointe from './PieceJointe.vue'

const props = defineProps(['intervenant']);

// Refs
const datasPiecesJointes = ref(null)

const urlGetPiecesJointes = computed(() =>
    unicaenVue.url('piece-jointe/intervenant/:intervenant/get-pieces-jointes', {
        intervenant: props.intervenant,
        test: 'test'
    })
)

function getPiecesJointes()
{

    unicaenVue.axios.get(urlGetPiecesJointes.value).then(response => {
        datasPiecesJointes.value = response.data
    }).catch(error => {
        console.error(error)
    })
}

// Initialisation
onMounted(() => {
    getPiecesJointes()
})
</script>

<style scoped>

</style>
