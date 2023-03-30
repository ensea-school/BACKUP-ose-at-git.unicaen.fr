<template>
    <div class="row row-cols-1 row-cols-md-2 g-4">
        <offreEmploi v-for="offre in offres" @supprimer="supprimer" @refresh="refresh" :key="offre.id" :offre="offre" :public="this.public"></offreEmploi>
    </div>
    <br/>
    <a class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajouter une nouvelle offre</a>
</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
    },
    props: {
        public: {type: Boolean, required: true},
        canAddOffreEmploi: {type: Boolean, required: true},
    },
    data()
    {
        return {
            offres: [],
            ajoutUrl: Util.url('offre-emploi/saisir'),
        };
    },

    mounted()
    {
        this.reload();
        console.log(this.offres);
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

        refresh(offre)
        {
            console.log(offre);
            let index = Util.json.indexById(this.offres, offre.id);
            this.offres[index] = offre;
        },

        reload()
        {
            axios.get(
                Util.url("offre-emploi/liste")
            ).then(response => {
                this.offres = response.data;
                console.log(this.offres);
            });
        },
    }
}
</script>

<style scoped>

</style>