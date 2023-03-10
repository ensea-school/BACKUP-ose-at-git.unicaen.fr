<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="addVolumeHoraire" @editEvent="editVolumeHoraire" @deleteEvent="deleteVolumeHoraire"
                :can-add-event="true"
                :events="realise"/>
    <u-modal id="suivi-form" ref="suiviForm" title="Suivi">
        <template #body>
            <div class="mb-2">
                <label for="mission" class="form-label">Mission</label>
                <select name="mission" id="mission" class="form-select" v-model="vhr.missionId">
                    <option v-for="(label,id) in missions" :key="id" :value="id">{{ label }}</option>
                </select>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <u-form-date name="date" label="Date" v-model="vhr.date" />
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label for="horaire-debut" class="form-label">Horaire de début</label>
                        <input type="time" name="horaire-debut" id="horaire-debut" class="form-control" v-model="vhr.horaireDebut"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label for="horaire-fin" class="form-label">Horaire de fin</label>
                        <input type="time" name="horaire-fin" id="horaire-fin" class="form-control" v-model="vhr.horaireFin"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-2">
                        <label for="heures" class="form-label">Nombre d'heures</label>
                        <input type="number" step="0.01" min="0" name="heures" id="heures" class="form-control" v-model="vhr.heures"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="form-check">
                            <label class="form-label" for="nocturne">Horaire nocturne</label>
                            <input type="checkbox" class="form-check-input" id="nocturne" v-model="vhr.nocturne"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="form-check">
                            <label class="form-label" for="formation">formation</label>
                            <input type="checkbox" class="form-check-input" id="formation" v-model="vhr.formation"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-2">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" v-model="vhr.description"/>
            </div>

            {{ vhr }}
        </template>
        <template #footer>
            <button class="btn btn-primary" @click="saveVolumeHoraire">Enregistrer</button>
        </template>
    </u-modal>
</template>

<script>

import SuiviEvent from './SuiviEvent.vue';

// markRaw utile pour éviter de scanner les changements et préserver les perfs
import { markRaw } from "vue";

export default {
    name: 'Suivi',
    props: {
        intervenant: {type: Number, required: true},
        missions: {type: Object, required: true}
    },
    mounted() {
        this.modal = new bootstrap.Modal(this.$refs.suiviForm.$el,{
            keyboard: false
        });
    },
    data()
    {
        const newVhr = {
            component: markRaw(SuiviEvent),
            color: 'yellow',
            date: null,
            missionId: null,
            horaireDebut: null,
            horaireFin: null,
            heures: null,
            nocturne: false,
            formation: false,
            description: null,
        };

        return {
            modal: null,
            date: new Date(),
            newVhr: newVhr,
            vhr: { ...this.newVhr}, // vhr = clone de newVhr
            vhrIndex: null,
            suiviForm: new Form({
                missionId: {required: true}
            }),
            realise: [
                {
                    component: markRaw(SuiviEvent),
                    color: 'yellow',
                    date: new Date(2023, 1, 5),
                    missionId: null,
                    horaireDebut: null,
                    horaireFin: null,
                    heures: null,
                    nocturne: false,
                    formation: false,
                    description: '5',
                },
                {
                    component: markRaw(SuiviEvent),
                    color: 'red',
                    date: new Date(2023, 1, 6),
                    missionId: null,
                    horaireDebut: null,
                    horaireFin: null,
                    heures: null,
                    nocturne: false,
                    formation: false,
                    description: '6',
                },
                {
                    component: markRaw(SuiviEvent),
                    date: new Date(2023, 1, 7),
                    color: '#d5a515',
                    missionId: null,
                    horaireDebut: null,
                    horaireFin: null,
                    heures: null,
                    nocturne: false,
                    formation: false,
                    description: '7',
                },
                {
                    component: markRaw(SuiviEvent),
                    date: new Date(2023, 2, 8),
                    missionId: null,
                    horaireDebut: null,
                    horaireFin: null,
                    heures: null,
                    nocturne: false,
                    formation: false,
                    description: '8',
                },
            ]
        };
    },
    methods: {
        changeDate(dateObj)
        {
            console.log(dateObj);
            this.date = dateObj;
        },

        addVolumeHoraire(dateObj)
        {
            this.vhr = { ...this.newVhr };
            this.vhr.date = dateObj;
            this.vhrIndex = undefined;
            this.modal.show();
        },

        editVolumeHoraire(calEvent)
        {
            this.vhr = { ...calEvent }; // on clone pour éviter de modifier avant d'enregistrer
            this.vhrIndex = this.realise.indexOf(calEvent);

            this.modal.show();
        },

        saveVolumeHoraire()
        {
            let ok = true;

            if (this.suiviForm.isValid(this.vhr)){
                this.modal.hide();
                if (this.vhrIndex === undefined){
                    this.realise.push(this.vhr);
                }else{
                    this.realise[this.vhrIndex] = this.vhr;
                }
            }else{
                console.log(this.suiviForm.errors);
            }

            // reste le passage et le retour du serveur...
        },

        deleteVolumeHoraire(calEvent)
        {
            const index = this.realise.indexOf(calEvent);
            this.realise.splice(index,1);
            console.log(index);
            console.log(this.realise);
        },
    }
}
</script>

<style scoped>

</style>