<template>
    <div v-for="taux in listeTaux">
        <taux v-if="!taux.missionTauxRemu" :key="taux.id" :taux="taux" :listeTaux="listeTaux"></taux>
    </div>
    <a v-if="canAddTaux" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'un nouveau taux</a>
</template>

<script>

import taux from './Taux.vue';

export default {
    components: {
        taux
    },
    props: {
        canAddTaux: {type: Boolean, required: true},
    },
    data()
    {
        return {
            listeTaux: [],
            ajoutUrl: Util.url('missions-taux/saisir')
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {
        ajout(event)
        {
            modAjax(event.target, (widget) => {
                this.reload();
            });
        },
        supprimer(taux)
        {
            this.reload();
        },
        refresh(taux)
        {
            this.listeTaux[taux.id] = taux;
        },
        reload()
        {

            axios.get(
                Util.url("missions-taux/liste-taux",)
            ).then(response => {
                this.listeTaux = response.data;
            });
        },
    }
}
</script>

<style scoped>

</style>