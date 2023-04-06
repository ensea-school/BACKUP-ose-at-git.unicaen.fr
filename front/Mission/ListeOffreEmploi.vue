<template>
    <div v-if="!extended" class="row row-cols-1 row-cols-md-2 g-4 mb-3">
        <offreEmploi v-for="offre in offres" @supprimer="supprimer" @refresh="refresh" :key="offre.id" :offre="offre" :public="this.public"
                     :canModifier="this.canModifier"
                     :canValider="this.canValider"
                     :canSupprimer="this.canSupprimer"></offreEmploi>
    </div>
    <a v-if="!this.public && this.canModifier" class=" btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajouter une nouvelle offre</a>
    <div v-if="extended">
        <offreEmploi v-for="offre in offres" :key="offre.id" :offre="offre" :canPostuler="this.canPostuler" :extended="extended"
                     :public="this.public" :utilisateur="this.utilisateur"></offreEmploi>
    </div>
    <br/>
</template>

<script>

import offreEmploi from './OffreEmploi.vue';

export default {
    components: {
        offreEmploi
    },
    props: {
        public: {type: Boolean, required: true},
        id: {type: Number, required: false},
        utilisateur: {required: false},
        canModifier: {type: Boolean, required: false},
        canPostuler: {type: Boolean, required: false},
        canValider: {type: Boolean, required: false},
        canSupprimer: {type: Boolean, required: false},


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

    },
    computed: {
        extended: function ()
        {
            if (this.id) {
                return true;
            }
            return false
        }
    },
    methods: {
        ajout(event)
        {
            modAjax(event.target, (widget) => {
                this.reload();
            });
        }
        ,
        supprimer()
        {
            this.reload();
        }
        ,

        refresh(offre)
        {
            let index = Util.json.indexById(this.offres, offre.id);
            this.offres[index] = offre;
        }
        ,

        reload()
        {
            if (this.id) {
                axios.get(
                    Util.url("offre-emploi/get/:offreEmploi", {offreEmploi: this.id})
                ).then(response => {
                    this.offres = [response.data];

                });

            } else {
                axios.get(
                    Util.url("offre-emploi/liste")
                ).then(response => {
                    this.offres = response.data;

                });
            }


        }
        ,
    }
}
</script>

<style scoped>

</style>