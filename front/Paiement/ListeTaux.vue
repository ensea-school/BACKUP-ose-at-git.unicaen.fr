<template>
    <div v-for="taux in listeTaux">
        <taux v-if="!taux.tauxRemu" @supprimer="supprimer" @refreshListe="refreshListe" :key="taux.id" :taux="taux" :listeTaux="listeTaux"></taux>
    </div>
    <a v-if="canEditTaux" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'un nouveau taux</a>
</template>

<script>

import taux from './Taux.vue';

export default {
    components: {
        taux
    },
    props: {
        canEditTaux: {type: Boolean, required: true},
    },
    data()
    {
        return {
            listeTaux: [],
            ajoutUrl: Util.url('taux/saisir')
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {
        ajout(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.reload();
            });
        },
        supprimer()
        {
            this.reload();
        },
        refreshListe()
        {
            this.reload();
        },
        refresh(taux)
        {
            let index = Util.json.indexById(this.listeTaux, taux.id);
            this.listeTaux[index] = taux;
        },
        reload()
        {
            axios.get(
                Util.url("taux/liste-taux")
            ).then(response => {
                this.listeTaux = response.data;
            });
        },
    }
}
</script>

<style scoped>

</style>