<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="addVolumeHoraire" @editEvent="editVolumeHoraire" @deleteEvent="deleteVolumeHoraire"
                :can-add-event="true"
                :events="suivi"/>
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

        addVolumeHoraire(dateObj, event)
        {
            event.currentTarget.dataset.url = Util.url('intervenant/:intervenant/missions-suivi-saisie', {intervenant:this.intervenant});
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },

        editVolumeHoraire(calEvent, event)
        {
            event.currentTarget.dataset.url = Util.url('intervenant/:intervenant/missions-suivi-saisie/:guid', {intervenant:this.intervenant,guid:calEvent.guid});
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
            axios.get(
                Util.url("intervenant/:intervenant/missions-suivi-data", {intervenant: this.intervenant})
            ).then(response => {
                let newSuivi = [];
                for (let i in response.data){
                    let missionSuivi = response.data[i];

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