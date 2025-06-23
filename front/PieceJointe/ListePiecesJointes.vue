<template>

    <pieceJointe v-for="(pieceJointe, key) in datasPiecesJointes"
                 :datas="pieceJointe"
                 :intervenant="intervenant"
                 @refresh="getPiecesJointes"></pieceJointe>


</template>

<script>

import pieceJointe from './PieceJointe.vue';


export default {
    components: {
        pieceJointe
    },
    props: {
        intervenant: {required: true},
    },
    data()
    {
        return {
            datasPiecesJointes: null,
            urlGetPiecesJointes: unicaenVue.url('piece-jointe/intervenant/:intervenant/get-pieces-jointes', {intervenant: this.intervenant}),

        }

    },
    mounted()
    {
        this.getPiecesJointes();
    },
    methods: {
        getPiecesJointes()
        {
            unicaenVue.axios.get(this.urlGetPiecesJointes).then(response => {
                this.datasPiecesJointes = response.data;
            }).catch(error => {
                console.error(error);
            })

        },
    }
}
</script>

<style scoped>

</style>