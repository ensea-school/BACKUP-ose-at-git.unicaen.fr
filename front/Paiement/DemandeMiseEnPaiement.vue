<template>

    <div v-for="(structure, code) in datasServiceAPayer ">
        <h3>{{ code + ' - ' + structure.libelle }}</h3>
        <div v-for="(etape, code) in structure.etapes">
            <h4>{{ code + ' - ' + etape.libelle }}</h4>
            <div v-for="(enseignement,code) in etape.enseignements">
                <h5>{{ code + ' - ' + enseignement.libelle }}</h5>
                <div v-for="(typeHeure, code) in enseignement.typeHeure">
                    <h6>{{ code + ' - ' + typeHeure.libelle }}</h6>
                </div>
            </div>

        </div>

    </div>
</template>

<script>


import UnicaenVue from "unicaen-vue/js/Client/unicaenVue";

export default {
    name: "DemandeMiseEnPaiement.vue",
    props: {
        intervenant: {required: false},
    },
    data()
    {
        console.log(this.intervenant);
        return {
            datasServiceAPayer: null,
            urlListeServiceAPayer: unicaenVue.url('intervenant/:intervenant/mise-en-paiement/liste-service-a-payer', {intervenant: this.intervenant}),
        }
    },
    computed:
        {},
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
        }
    },
    mounted()
    {
        this.findServiceAPayer();
    }
}
</script>