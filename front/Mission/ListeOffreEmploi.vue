<template>
    <div v-for="offre in listeOffre">
        <offreEmploi :key="offre.id" :taux="offre"
                     :listeTaux="listeOffreEmploi"></offreEmploi>
    </div>
    <a v-if="canEditTaux" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'un nouveau taux</a>
</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
    },
    props: {
        canEditTaux: {type: Boolean, required: true},
    },
    data()
    {
        return {
            listeOffre: [],
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
            modAjax(event.target, (widget) => {
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