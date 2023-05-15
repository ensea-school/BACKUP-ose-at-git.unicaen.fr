<template>

    <div class="col" v-if="!this.extended && offre.canVisualiser">
        <div class="card h-100">
            <div class="card-header">
                <h4> {{ offre.titre }}</h4>
                <span class="badge rounded-pill bg-info">{{ offre.nombreHeures }} heure(s)</span> &nbsp;
                <span v-if="nbPostesRestants > 0" class="badge rounded-pill bg-success">{{ nbPostesRestants }} poste(s) restant(s)</span>&nbsp;
                <span v-if="nbPostesRestants <= 0" class="badge rounded-pill bg-danger">Tous les postes sont pourvus</span>&nbsp;
                <span v-if="offre.validation" class="badge rounded-pill bg-success">Valider le <u-date
                        :value="offre.validation.histoCreation"/> par {{ offre.validation.histoCreateur.displayName }}</span>
                <span v-if="!offre.validation" class="badge rounded-pill bg-warning"> En attente de validation par la DRH</span>&nbsp;
            </div>

            <div class="card-body">
                <p class="bg-light" style="padding:5px;">
                    <b>Crée le : </b>
                    <u-date :value="offre.histoCreation"/>
                    par {{ offre.histoCreateur.displayName }}<br/>
                    <b>Période à pourvoir : </b>du
                    <u-date :value="offre.dateDebut"/>
                    au
                    <u-date :value="offre.dateFin"/>
                    <br/>
                    <b>Demandé par la composante :</b> {{ offre.structure.libelleCourt }}
                    <br/>
                    <b>Type de mission :</b> {{ offre.typeMission.libelle }}

                </p>
                {{ shortDesc }}

            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <a :href="consulterUrl"
                       title="Consulter "
                       class="btn btn-primary"
                    >
                        <u-icon name="eye"/>
                        Voir
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div v-if="this.extended && offre.canVisualiser">
        <h1 class="page-header">{{ offre.titre }}</h1>
        <div v-if="!this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-user"></i>
            <div class="ms-2">
                Vous devez <a :href="connectionLink" class="text-decoration-underline alert-link">être identifé</a> pour pouvoir
                postuler.
            </div>
        </div>
        <div v-if="!offre.canPostuler && this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Vous n'avez pas les droits pour postuler à cette offre, merci de contacter votre administration de rattachement.
            </div>
        </div>
        <div v-if="isCandidat && this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Vous avez déjà postulé à cette offre.
            </div>
        </div>
        <div v-if="!isCandidat && nbPostesRestants <= 0" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Tous les postes pour cette offre ont été pourvu.
            </div>
        </div>
        <p class="bg-light" style="padding:10px;">
            <b>Crée le : </b>
            <u-date :value="offre.histoCreation"/>
            par {{ offre.histoCreateur.displayName }}<br/>
            <b>Période à pourvoir : </b>du
            <u-date :value="offre.dateDebut"/>
            au
            <u-date :value="offre.dateFin"/>
            <br/>
            <b>Demandé par la composante :</b> {{ offre.structure.libelleCourt }}
            <br/>
            <b>Type de mission :</b> {{ offre.typeMission.libelle }}
            <br/>
            <b>Nombre d'heures pour la mission :</b> {{ offre.nombreHeures }} heure(s)
            <br/>
            <b>Nombre de postes à pourvoir :</b> {{ offre.nombrePostes }} poste(s)
            <br/>

        </p>

        {{ offre.description }}
        <br/><br/>
        <div v-if="this.canVoirCandidature">
            <h5><strong>Liste des candidats :</strong></h5>

            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th>Intervenant</th>
                    <th>Composante</th>
                    <th>Etat</th>
                    <th v-if="canValiderCandidature">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="offre.candidatures.length == 0">
                    <td v-if="canValiderCandidature" colspan="4" style="text-align:center;">Aucune candidature</td>
                    <td v-if="!canValiderCandidature" colspan="3" style="text-align:center;">Aucune candidature</td>
                </tr>
                <tr v-for="candidature in offre.candidatures">
                    <td><a :href="'/intervenant/code:' + candidature.intervenant.code + '/voir'">
                        {{ candidature.intervenant.prenom+' '+candidature.intervenant.nomUsuel }}</a></td>
                    <td>{{ candidature.intervenant.structure.libelleLong }}</td>
                    <th> <span v-if="candidature.validation" class="badge rounded-pill bg-success">Accepter le <u-date
                            :value="candidature.validation.histoCreation"/> par {{
                            candidature.validation.histoCreateur.displayName
                        }}</span>
                        <span v-if="!candidature.validation" class="badge rounded-pill bg-warning">En attente d'acceptation</span>
                    </th>
                    <td v-if="this.canValiderCandidature">
                        <a :href="'/offre-emploi/accepter-candidature/' + candidature.id" v-if="!candidature.validation"
                           title="Accepter la candidature"
                           class="btn btn-success"
                           data-title="Accepter la candidature"
                           data-content="Etes vous sûre de vouloir accepter cette candidature ?"
                           @click.prevent="validerCandidature">Accepter </a>&nbsp;
                        <a :href="'/offre-emploi/refuser-candidature/' + candidature.id"
                           title="Refuser la candidature"
                           class="btn btn-danger"
                           data-title="Refuser la candidature"
                           data-content="Etes vous sûre de vouloir refuser cette candidature ?"
                           @click.prevent="refuserCandidature">Refuser </a>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>


        <div class="mt-5">
            <a class="btn btn-primary" href="/offre-emploi">Retour aux offres</a>&nbsp;
            <a v-if="this.canPostuler" :href="'/offre-emploi/postuler/' + offre.id" data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-original-title="Vous devez être connecté pour postuler">Postuler</a>&nbsp;
            <a v-if="offre.canModifier"
               :href="saisirUrl"
               class="btn btn-primary"
               @click.prevent="saisir"
               title="Modifier">
                <u-icon name="pen-to-square"/>
                Modifier
            </a>&nbsp;
            <a v-if="offre.validation && offre.canValider"
               :href="devaliderUrl"
               class="btn btn-danger"
               title="Devalider"
               @click.prevent="devalider">
                <u-icon name="thumbs-down"/>
                Devalider
            </a>&nbsp;
            <a v-if="!offre.validation && offre.canValider"
               :href="validerUrl"
               class="btn btn-success"
               title="Valider"
               @click.prevent="valider">
                <u-icon name="thumbs-up"/>
                Valider
            </a>&nbsp;
            <a v-if="offre.canSupprimer"
               :href="supprimerUrl"
               class="btn btn-danger"
               title="Supprimer"
               data-title="Suppression de l'offre"
               data-content="Êtes-vous sur de vouloir supprimer l'offre ?"
               @click.prevent="supprimer">
                <u-icon name="trash"/>
                Supprimer
            </a>


        </div>

    </div>

</template>

<script>

export default {
    name: "OffreEmploi.vue",
    props: {
        offre: {required: true},
        utilisateur: {required: false},
        extended: {type: Boolean, required: false},
        canModifier: {type: Boolean, required: false},
        canPostuler: {type: Boolean, required: false},
        canValider: {type: Boolean, required: false},
        canSupprimer: {type: Boolean, required: false},
        canVoirCandidature: {type: Boolean, required: false},
        canValiderCandidature: {type: Boolean, required: false},
    },
    data()
    {

        return {
            saisirUrl: unicaenVue.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: unicaenVue.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
            validerUrl: unicaenVue.url('offre-emploi/valider/:offre', {offre: this.offre.id}),
            devaliderUrl: unicaenVue.url('offre-emploi/devalider/:offre', {offre: this.offre.id}),
            consulterUrl: unicaenVue.url('offre-emploi/detail/:offre', {offre: this.offre.id}),

        };
    },
    computed: {
        isCandidat: function () {
            return false;
        },
        shortDesc: function () {
            var shorDesc = this.offre.description.substr(0, 200);
            if (this.offre.description.length > 200) {
                shorDesc += '...';
            }
            return shorDesc;
        },
        connectionLink: function () {
            return '/auth/connexion?redirect='+window.location.href;
        },
        nbPostesRestants: function () {
            return this.offre.nombrePostes-this.offre.candidaturesValides.length;
        }
    },

    methods: {
        saisir(event)
        {
            modAjax(event.target, (widget) => {
                this.refresh();
            });
        },
        refresh()
        {
            unicaenVue.axios.get(
                unicaenVue.url("offre-emploi/get/:offreEmploi", {offreEmploi: this.offre.id})
            ).then(response => {
                this.$emit('refresh', response.data);
            });
        },
        supprimer(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('supprimer', this.offre);
            });
        },
        postuler(event)
        {
            popConfirm(event.target, (response) => {
                this.$emit('postuler', this.offre);
            });
        },
        valider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refresh', response.data);
            });
        },
        devalider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refresh', response.data);
            });
        },
        validerCandidature(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('refresh', response.data);
            });
        }

    },

}

</script>

<style scoped>

</style>