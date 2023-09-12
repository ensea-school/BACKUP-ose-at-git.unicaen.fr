<template>
    <prime v-for="(prime, index) in primes" :canValider="canValider" :intervenant="this.intervenant"
           :numero="index"
           :prime="prime"
           @reload="reload"></prime>
    <div v-if="primes.length == 0"> aucune prime<br/><br/></div>
    <div v-if="this.missionsWithoutPrime > 0">
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
        canValider: {type: Boolean, required: false},

    },
    data()
    {
        return {
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
            unicaenVue.axios.get(
                unicaenVue.url("prime/:intervenant/liste", {intervenant: this.intervenant})
            ).then(response => {
                this.primes = response.data;
            });
        },

    }
}
</script>

<style scoped>

</style>