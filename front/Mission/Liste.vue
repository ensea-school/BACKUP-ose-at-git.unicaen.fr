<template>
    <mission v-for="mission in missions" @supprimer="supprimer" @refresh="refresh" :key="mission.id" :mission="mission"></mission>
    <a v-if="canAddMission" class="btn btn-primary" :href="ajoutUrl" @click.prevent="ajout">Ajout d'une nouvelle mission</a>
</template>

<script>

import mission from './Mission.vue';

export default {
    components: {
        mission
    },
    props: {
        intervenant: {type: Number, required: true},
        canAddMission: {type: Boolean, required: true},
    },
    data()
    {
        return {
            missions: [],
            ajoutUrl: Util.url('mission/ajout/:intervenant', {intervenant: this.intervenant})
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
        supprimer(mission)
        {
            this.reload();
        },
        refresh(mission)
        {console.log(mission);
            let index = Util.json.indexById(this.missions, mission.id);
            this.missions[index] = mission;
        },
        reload()
        {
            axios.get(
                Util.url("mission/liste/:intervenant", {intervenant: this.intervenant})
            ).then(response => {
                this.missions = response.data;
            });
        },
    }
}
</script>

<style scoped>

</style>