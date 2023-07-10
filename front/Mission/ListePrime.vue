<template>
    <prime v-for="contrat in contratsPrime" :key="contrat.CONTRAT_ID" :contrat="contrat" :intervenant="this.intervenant"
           @reload="reload"></prime>
</template>

<script>

import prime from './Prime.vue';

export default {
    components: {
        prime
    },
    props: {
        intervenant: {type: Number, required: true},
    },
    data()
    {
        return {
            contratsPrime: [],
        };
    },
    mounted()
    {
        this.reload();
    },
    methods: {

        reload()
        {
            unicaenVue.axios.get(
                unicaenVue.url("intervenant/:intervenant/get-contrat-prime", {intervenant: this.intervenant})
            ).then(response => {
                this.contratsPrime = response.data;
            });
        },

    }
}
</script>

<style scoped>

</style>