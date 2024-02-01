<template>
    <demande-mise-en-paiement-structure v-for="(structure, code) in datasServiceAPayer"
                                        :datas="structure"
                                        :intervenant="intervenant"
                                        @refresh="findServiceAPayer"/>
</template>

<script>

import DemandeMiseEnPaiementStructure from './DemandeMiseEnPaiementStructure.vue';


export default {

    name: "DemandeMiseEnPaiement",
    components: {DemandeMiseEnPaiementStructure},
    props: {
        intervenant: {required: false},
    },
    data()
    {
        return {
            datasServiceAPayer: null,
            urlListeServiceAPayer: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/liste-service-a-payer', {intervenant: this.intervenant}),
        }
    },

    methods: {
        findServiceAPayer()
        {

            unicaenVue.axios.get(this.urlListeServiceAPayer)
                .then(response => {
                    this.datasServiceAPayer = response.data;
                })
                .catch(error => {
                    console.error(error);
                })

        },

    },
    mounted()
    {
        this.findServiceAPayer();
    },
    updated()
    {
        $('.selectpicker').selectpicker('render');
    }


}

</script>