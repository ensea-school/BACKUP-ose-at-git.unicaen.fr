<template>
    <u-calendar :date="date" @changeDate="changeDate" @addEvent="addVolumeHoraire" :events="realise"/>
    <u-modal id="suivi-form" title="Suivi">
        <template #body>
            <u-tabs tab="mission" :tabs="{mission:'Mission',formation:'Formation',conges:'Congés'}">
                <template #mission>
                    <div class="mb-2">
                        <label for="mission" class="form-label">Mission</label>
                        <select name="mission" id="mission" class="form-select" v-model="vhr.missionId">
                            <option v-for="(label,id) in missions" :key="id" :value="id">{{ label }}</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="nocturne" v-model="vhr.nocturne">
                            <label class="form-label" for="nocturne">Horaire nocturne</label>
                        </div>
                    </div>
                </template>
                <template #formation>
                    Formation 2
                </template>
                <template #conges>
                    Congés 2
                </template>
            </u-tabs>
        </template>
        <template #footer>

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
                missionId: null,
                nocturne: false,
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
        test()
        {
            console.log(this.lignes());
        },

        changeDate(dateObj)
        {
            console.log(dateObj);
            this.date = dateObj;
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