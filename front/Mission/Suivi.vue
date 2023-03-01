<template>
    <u-calendar :date="date" @changeDate="changeDate" :events="realise" />
    <button @click="test()">Test</button>
</template>

<script>

import SuiviEvent from './SuiviEvent.vue';

export default {
    name: 'Suivi',
    props: {
        intervenant: {type: Number, required: true},
    },
    data(){
        return {
            date: new Date(),
            realise: [
                {
                    component: SuiviEvent,
                    date: new Date(2023,1,5),
                    texte:'Coucou, le 50 février 2023',
                },
                {
                    component: SuiviEvent,
                    date: new Date(2023,1,5),
                    texte:'Coucou, le 505 février 2023',

                },
                {
                    component: SuiviEvent,
                    date: new Date(2023,1,6),
                    texte:'Coucou, le 60 février 2023'
                },
                {
                    component: SuiviEvent,
                    date: new Date(2023,2,6),
                    texte:'Coucou, le 6 mars 2023',

                },
            ]
        };
    },
    methods: {
        test()
        {
            console.log(this.lignes());
        },

        changeDate(dateObj)
        {
            console.log(dateObj);
            this.date = dateObj;
        }
    }
}
</script>

<style scoped>

</style>