<template>
    <mission v-for="mission in missions" @supprimer="supprimer" @refresh="refresh" :key="mission.id" :mitem="mission"></mission>
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
        this.refresh();
    },
    methods: {
        ajout(event)
        {
            modAjax(event.target, (widget) => {
                let newId = widget.contentDiv.find('form').data('id');
                if (newId){
                    axios.get(
                        Util.url("mission/get/:mission", {mission: newId})
                    ).then(response => {
                        this.missions.push(response.data);
                    });
                }
            });
        },
        supprimer(mission)
        {
            const index = this.missions.indexOf(mission);
            this.missions.splice(index, 1);
        },
        refresh()
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