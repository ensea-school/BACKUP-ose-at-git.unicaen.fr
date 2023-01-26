<template>
    <mission v-for="mission in missions" @supprimer="supprimer" :key="mission.id" :mission="mission"></mission>
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
        axios.get(
            Util.url("mission/liste/:intervenant", {intervenant: this.intervenant})
        ).then(response => {
            this.missions = response.data;
        });
    },
    methods: {
        ajout(event)
        {
            modAjax(event.target, (widget) => {
                axios.get(
                    Util.url("mission/get/:mission", {mission: this.mission.id})
                ).then(response => {
                    this.missions.push(response.data);
                });
            });
        },
        supprimer(mission)
        {
            const index = this.missions.indexOf(mission);
            this.missions.splice(index, 1);
        }
    }
}
</script>

<style scoped>

</style>