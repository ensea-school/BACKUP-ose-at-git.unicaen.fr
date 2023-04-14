<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="ajouter" :can-add-event="canAddMission" :events="suivi"/>
</template>

<script>

import SuiviEvent from './SuiviEvent.vue';

// markRaw utile pour éviter de scanner les changements et préserver les perfs
import {markRaw} from "vue";

export default {
    name: 'Suivi',
    props: {
        intervenant: {type: Number, required: true},
        canAddMission: {type: Boolean, required: true},
    },
    mounted()
    {
        this.refresh();
    },
    data()
    {
        return {
            date: new Date(),
            suivi: []
        };
    },
    methods: {
        changeDate(dateObj)
        {
            this.date = dateObj;
        },

        ajouter(dateObj, event)
        {
            const urlParams = {
                intervenant: this.intervenant,
                date: dateObj.toISOString().slice(0, 10) // date au format Y-m-d
            };
            event.currentTarget.dataset.url = unicaenVue.url('mission/suivi/ajout/:intervenant/:date', urlParams);
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },

        refresh()
        {
            const colors = [
                '#e74c3c',
                '#8e44ad',
                '#3498db',
                '#1abc9c',
                '#2ecc71',
                '#f1c40f',
                '#e67e22',
                '#d35400',
            ];
            let colorIndex = 0;
            let missionsColors = [];

            unicaenVue.axios.get(
                unicaenVue.url("mission/suivi/liste/:intervenant", {intervenant: this.intervenant})
            ).then(response => {
                let newSuivi = [];
                for (let i in response.data) {
                    let missionSuivi = response.data[i];

                    if (undefined === missionsColors[missionSuivi.mission.id]) {
                        missionsColors[missionSuivi.mission.id] = colors[colorIndex];
                        colorIndex++;
                    }
                    if (missionSuivi.valide){
                        missionSuivi.bgcolor = '#d0eddb';
                    }
                    missionSuivi.color = missionsColors[missionSuivi.mission.id];
                    missionSuivi.component = markRaw(SuiviEvent);
                    missionSuivi.date = new Date(missionSuivi.date);
                    missionSuivi.intervenant = this.intervenant;
                    newSuivi.push(missionSuivi);
                }
                this.suivi = newSuivi;
            });
        }
    }
}
</script>

<style scoped>

</style>