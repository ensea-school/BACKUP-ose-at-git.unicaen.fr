<template>
    <prime v-for="(prime, index) in primes" :canGerer="canGerer" :intervenant="this.intervenant"
           :numero="index"
           :prime="prime"
           @reload="reload"></prime>
    <div v-if="!load" class="text-secondary text-center fs-6   " style="text-align:center;"> Chargement en cours...<br/><br/></div>
    <div v-if="primes.length == 0 && load" class="text-secondary text-center fs-6   " style="text-align:center;"> Aucune prime de fin de mission
        actuellement...<br/><br/></div>
    <div v-if="this.missionsWithoutPrime > 0 && this.canGerer">
        <a :href="ajoutUrl" class=" btn btn-primary" @click.prevent="ajout">Créer une nouvelle prime</a>
    </div>
</template>

<script>

import prime from './Prime.vue';

export default {
    components: {
        prime
    },
    props: {
        intervenant: {type: Number, required: true},
        numero: {type: Number, required: false},
        missionsWithoutPrime: {type: Number, required: false},
        canGerer: {type: Boolean, required: false},

    },
    data()
    {

        return {
            load: false,
            primes: [],
            ajoutUrl: unicaenVue.url('prime/:intervenant/saisie/', {intervenant: this.intervenant})
        };
    },
    mounted()
    {

        this.reload();
        console.log(this.primes.length)
    },

    methods: {
        ajout(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.reload();
            });
        },

        reload()
        {
            this.load = false;
            this.primes = [];
            unicaenVue.axios.get(
                unicaenVue.url("prime/:intervenant/liste", {intervenant: this.intervenant})
            ).then(response => {
                this.primes = response.data;
                this.load = true;
            });
        },

    }
}
</script>

<style scoped>

</style>