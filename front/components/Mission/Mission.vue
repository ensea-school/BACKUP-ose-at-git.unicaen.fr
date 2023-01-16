<template>
    <div :id="mission.id" class="card bg-default">
        <form @submit.prevent="submitForm">
            <div class="card-header form-inline">
                <select class="form-select" v-model="mission.typeMission">
                    <option v-for="(label, value) in options.typeMission" :key="value" :value="value">{{ label }}</option>
                </select>
                &nbsp;, du&nbsp;<input type="date" class="form-control" v-model="mission.dateDebut"/>
                &nbsp;au&nbsp;<input type="date" class="form-control" v-model="mission.dateFin"/>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                        <label class=" form-label" for="structure">Composante en charge du suivi de mission</label>
                        <select class="form-select" v-model="mission.structure">
                            <option v-for="(label, value) in options.structure" :key="value" :value="value">{{ label }}</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label class=" form-label" for="missionTauxRemu">Taux de rémunération</label>
                            <select class="form-select" v-model="mission.missionTauxRemu">
                                <option v-for="(label, value) in options.missionTauxRemu" :key="value" :value="value">{{ label }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-2">
                            <label class=" form-label" for="heures">Heures</label>
                            <input class="form-control" type="text" v-model="mission.heures"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <label class=" form-label" for="description">Descriptif de la mission</label>
                            <input class="form-control" type="text" v-model="mission.description"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-2">
                            <input type="submit" class="btn btn-primary" value="Enregistrer"/>
                            &nbsp;
                            <a class="btn btn-danger" @click="deleteMission">Suppression de la mission</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'Mission',
    props: {
        mission: {required: true},
        options: {type: Object}
    },
    data()
    {
        return {};
    },
    methods: {
        submitForm(event)
        {
            var that = this
            $.ajax({
                type: 'POST',
                submitter: event.submitter,
                msg: 'Enregistrement en cours',
                successMsg: 'Enregistrement effectué',
                url: Util.url('mission/modifier'),
                data: this.mission,
                success: function (response) {
                    that.mission = response.data;
                }
            });
        },
        deleteMission(mission)
        {
            this.$emit('delete', this.mission);
        }
    }
}
</script>

<style scoped>

</style>