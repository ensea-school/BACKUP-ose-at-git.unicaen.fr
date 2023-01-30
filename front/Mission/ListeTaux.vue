<template>
    <taux v-for="taux in listeTaux" @refresh="refresh" :key="taux.id" :taux="taux"></taux>
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
            ajoutUrl: "" //Util.url('mission/ajout/:intervenant', {intervenant: this.intervenant})
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
            this.listeTaux = {
                1: {code: 'SMIC'},
                2: {code: 'Taux 1'},
                3: {code: 'Taux 2'},
            }

            /*axios.get(
                Util.url("mission/liste-taux", {intervenant: this.intervenant})
            ).then(response => {
                this.listeTaux = response.data;
            });*/
        },
    }
}
</script>

<style scoped>

</style>