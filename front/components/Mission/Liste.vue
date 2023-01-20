<template>
    <mission v-for="mission in missions" @delete="deleteMission" :key="mission.id" :mission="mission"></mission>
    <a v-if="canAddMission" class="btn btn-primary" @click="addMission">Ajout d'une nouvelle mission</a>
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
            nextMissionId: -1
        };
    },
    methods: {
        addMission()
        {
            this.missions.push({id: this.nextMissionId--});
        },
        deleteMission(mission)
        {
            const index = this.missions.indexOf(mission);
            this.missions.splice(index, 1);
        }
    },
    mounted()
    {
        axios.get(
            Util.url("mission/liste/:intervenant", {intervenant: this.intervenant})
        ).then(response => {
            this.missions = response.data;
        });
    }
}
</script>

<style scoped>

</style>