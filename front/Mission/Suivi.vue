<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="addVolumeHoraire" :can-add-event="true" :events="suivi"/>
</template>

<script>

import SuiviEvent from './SuiviEvent.vue';

// markRaw utile pour éviter de scanner les changements et préserver les perfs
import {markRaw} from "vue";

export default {
    name: 'Suivi',
    props: {
        intervenant: {type: Number, required: true},
    },
    mounted()
    {
        this.refresh();
    },
    data()
    {
        return {
            date: new Date(),
            suivi: [],
            // component: markRaw(SuiviEvent),
            // color: 'yellow',
            // date: new Date(2023, 1, 5),
            // missionId: null,
            // horaireDebut: null,
            // horaireFin: null,
            // heures: null,
            // nocturne: false,
            // formation: false,
            // description: '5',
        };
    },
    methods: {
        changeDate(dateObj)
        {
            this.date = dateObj;
        },

        toto()
        {
            console.log('prout');
        },

        addVolumeHoraire(dateObj, event)
        {
            const urlParams = {
                intervenant: this.intervenant,
                date: dateObj.toISOString().slice(0, 10) // date au format Y-m-d
            };
            event.currentTarget.dataset.url = unicaenVue.url('intervenant/:intervenant/missions-suivi-ajout/:date', urlParams);
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },

        editVolumeHoraire(calEvent, event)
        {
            const urlParams = {
                intervenant: this.intervenant,
                id: calEvent.id
            };
            event.currentTarget.dataset.url = unicaenVue.url('intervenant/:intervenant/missions-suivi-modification/:id', urlParams);
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },

        saveVolumeHoraire(event)
        {
            console.log('submit!!!');
            event.preventDefault();

            this.modal.hide();
            this.vhr.date = new Date(this.vhr.date);
            if (this.vhrIndex === undefined) {
                this.realise.push(this.vhr);
            } else {
                this.realise[this.vhrIndex] = this.vhr;
            }

            // reste le passage et le retour du serveur...
        },

        deleteVolumeHoraire(calEvent, event)
        {
            const index = this.realise.indexOf(calEvent);
            this.realise.splice(index, 1);
            console.log(index);
            console.log(this.realise);
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

            //  SuiviEvent.data.suivi = this;
            unicaenVue.axios.get(
                unicaenVue.url("intervenant/:intervenant/missions-suivi-data", {intervenant: this.intervenant})
            ).then(response => {
                let newSuivi = [];
                for (let i in response.data) {
                    let missionSuivi = response.data[i];

                    if (undefined === missionsColors[missionSuivi.mission.id]){
                        missionsColors[missionSuivi.mission.id] = colors[colorIndex];
                        colorIndex++;
                    }
                    missionSuivi.color = missionsColors[missionSuivi.mission.id];
                    missionSuivi.component = markRaw(SuiviEvent);
                    missionSuivi.date = new Date(missionSuivi.date);
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