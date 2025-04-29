<template>
    <div>
        <h1>Liste pieces jointes</h1>
        <piece-jointe/>

    </div>
</template>

<script>

import taux from './PieceJointe.vue';
import PieceJointe from "@/PieceJointe/PieceJointe.vue";

export default {
    components: {PieceJointe},
    props: {},
    data()
    {
        return {
            listePiecesJointes: [],
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {

        reload()
        {
            /*    unicaenVue.axios.get(
                    unicaenVue.url("taux/liste-taux")
                ).then(response => {
                    this.listeTaux = response.data;
                });*/
        },
    }
}
</script>

<style scoped>

</style>