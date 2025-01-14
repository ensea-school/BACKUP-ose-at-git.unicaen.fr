<template>
    <h1>Administration des structures</h1>
    <div class="accordion no-intranavigation">
    <structure v-for="structure in structures"
               :key="structure.id"
               :structure="structure"
    ></structure>
    </div>
    <a v-if="canAdd" class="btn btn-primary no-intranavigation"
       :href="ajoutUrl"
       @click.prevent="ajout"
       title="Ajouter une structure"
    >
        <i class="fas fa-pen-to-square"></i>
        Ajouter une structure
    </a>
</template>
<script>

import structure from "./Structure.vue";

export default {
    components: {
        structure
    },
    props: {
        canAdd: {type: Boolean, required: true},
    },
    data()
    {
        return {
            structures: [],
            ajoutUrl: unicaenVue.url('structure/saisie'),
            liste: this,
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
        reload()
        {
            unicaenVue.axios.get(
                unicaenVue.url("structure/liste")
            ).then(response => {
                this.structures = response.data;
            });
        },
    }
}
</script>
<style scoped>

</style>