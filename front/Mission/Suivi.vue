<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="addVolumeHoraire" :events="realise"/>
    <u-modal id="suivi-form" title="Suivi">
        <template #body>
            <u-tabs :tab="vhr.type" @changeTab="changeTab" :tabs="{mission:'Mission',formation:'Formation',conges:'Congés'}">
                <template #mission>
                    <div class="mb-2">
                        <label for="mission" class="form-label">Mission</label>
                        <select name="mission" id="mission" class="form-select" v-model="vhr.missionId">
                            <option v-for="(label,id) in missions" :key="id" :value="id">{{ label }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="horaire-debut" class="form-label">Horaire de début</label>
                                <input type="time" name="horaire-debut" id="horaire-debut" class="form-control" v-model="vhr.horaireDebut"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="horaire-fin" class="form-label">Horaire de fin</label>
                                <input type="time" name="horaire-fin" id="horaire-fin" class="form-control" v-model="vhr.horaireFin"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label for="heures" class="form-label">Nombre d'heures</label>
                                <input type="number" step="0.01" name="heures" id="heures" class="form-control" v-model="vhr.heures"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label" for="nocturne">Horaire nocturne</label><br/>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="nocturne" v-model="vhr.nocturne"/>
                                </div>
                            </div>
                        </div>
                    </div>

                </template>
                <template #formation>
                    <div class="mb-2">
                        <label for="heures-formation" class="form-label">Nombre d'heures</label>
                        <input type="number" step="0.01" name="heures-formation" id="heures-formation" class="form-control" v-model="vhr.heuresFormation"/>
                    </div>
                </template>
                <template #conges>
                    <div>
                        <label for="periode-conges" class="form-label">Période des congés</label>
                    </div>
                    <div class="btn-group" role="group" aria-label="Période de congés">
                        <input type="radio" class="btn-check" autocomplete="off"
                               name="conges-periode" id="conges-matin" value="matin"
                               :checked="vhr.congesPeriode == 'matin'">
                        <label class="btn btn-outline-primary" for="conges-matin" @click="changeCongesPeriode" data-value="matin">Matin</label>

                        <input type="radio" class="btn-check" autocomplete="off"
                               name="conges-periode" id="conges-aprem" value="aprem"
                               :checked="vhr.congesPeriode == 'aprem'">
                        <label class="btn btn-outline-primary" for="conges-aprem" @click="changeCongesPeriode" data-value="aprem">Après-midi</label>

                        <input type="radio" class="btn-check" autocomplete="off"
                               name="conges-periode" id="conges-jour" value="jour"
                               :checked="vhr.congesPeriode == 'jour'">
                        <label class="btn btn-outline-primary" for="conges-jour" @click="changeCongesPeriode" data-value="jour">Toute la journée</label>
                    </div>
                </template>
            </u-tabs>
            <div class="mb-2">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" v-model="vhr.description" />
            </div>

            {{ vhr }}
        </template>
        <template #footer>
            <button class="btn btn-primary">Enregistrer</button>
        </template>
    </u-modal>
</template>

<script>

import SuiviEvent from './SuiviEvent.vue';

export default {
    name: 'Suivi',
    props: {
        intervenant: {type: Number, required: true},
        missions: {type: Array, required: true}
    },
    data()
    {
        return {
            date: new Date(),
            vhr: {
                date: null,
                horaireDebut: null,
                horaireFin: null,
                type: "mission",
                missionId: null,
                nocturne: false,
                heures: null,
                heuresFormation: null,
                congesPeriode: "jour",
                description: null,
            },
            realise: [
                {
                    component: SuiviEvent,
                    date: new Date(2023, 1, 5),
                    texte: 'Coucou, le 50 février 2023',
                },
                {
                    component: SuiviEvent,
                    date: new Date(2023, 1, 5),
                    texte: 'Coucou, le 505 février 2023',
                },
                {
                    component: SuiviEvent,
                    date: new Date(2023, 1, 6),
                    texte: 'Coucou, le 60 février 2023'
                },
                {
                    component: SuiviEvent,
                    date: new Date(2023, 2, 6),
                    texte: 'Coucou, le 6 mars 2023',
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

        changeTab(tab)
        {
            this.vhr.type = tab;
        },

        changeCongesPeriode(event)
        {
            this.vhr.congesPeriode = event.target.dataset['value'];
        },

        addVolumeHoraire(dateObj)
        {
            console.log(dateObj);

        },
    }
}
</script>

<style scoped>

</style>