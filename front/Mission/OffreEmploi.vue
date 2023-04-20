<template>
    <div class="col" v-if="isPublic && !this.extended">
        <div class="card h-100">
            <div class="card-header">
                <h4> {{ offre.titre }}</h4>
                <span class="badge rounded-pill bg-info">{{ offre.nombreHeures }} heure(s)</span> &nbsp;
                <span v-if="nbPostesRestants > 0" class="badge rounded-pill bg-success">{{ nbPostesRestants }} poste(s) restant(s)</span>&nbsp;
                <span v-if="nbPostesRestants <= 0" class="badge rounded-pill bg-danger">Tous les postes sont pourvus</span>&nbsp;
                <span v-if="offre.validation && !this.public" class="badge rounded-pill bg-success">Valider le <u-date
                        :value="offre.validation.histoCreation"/> par {{ offre.validation.histoCreateur.displayName }}</span>
                <span v-if="!offre.validation && !this.public" class="badge rounded-pill bg-warning"> En attente de validation par la DRH</span>&nbsp;
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
                    <b>Demandé par la composante :</b> {{ offre.structure.libelle }}
                    <br/>
                    <b>Type de mission :</b> {{ offre.typeMission.libelle }}

                </p>
                {{ shortDesc }}

            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a v-if="offre.validation && offre.canValide && !this.public"
                       :href="devaliderUrl"
                       class="btn btn-danger"
                       @click.prevent="devalider">Devalider</a>
                    <a v-if="!offre.validation && offre.canValide  && !this.public"
                       :href="validerUrl"
                       class="btn btn-success"
                       @click.prevent="valider">Valider</a>
                    <a v-if="offre.canSaisie && !this.public"
                       :href="saisirUrl"
                       class="btn btn-primary"
                       @click.prevent="saisir">Modifier</a>
                    <a v-if="offre.canSupprime && !this.public"
                       :href="supprimerUrl"
                       class="btn btn-danger"
                       data-title="Suppression de l'offre"
                       data-content="Êtes-vous sur de vouloir supprimer l'offre ?"
                       @click.prevent="supprimer">Supprimer</a>
                    <a :href="consulterUrl"
                       class="btn btn-primary"
                    >Plus d'information</a>
                    <a v-if="this.public"
                       :href="consulterUrl"
                       class="btn btn-primary"
                    >Plus d'information</a>
                </div>
            </div>
        </div>
    </div>
    <div v-if="this.extended">
        <div v-if="!this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-user"></i>
            <div class="ms-2">
                Vous devez <a :href="connectionLink" class="text-decoration-underline alert-link">être identifé</a> pour pouvoir
                postuler.
            </div>
        </div>
        <div v-if="!offre.canPostuler" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Vous n'avez pas les droits pour postuler à cette offre, merci de contacter votre administration de rattachement.
            </div>
        </div>
        <div v-if="isCandidat" class="alert alert-primary d-flex align-items-center" role="alert">
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
            <b>Demandé par la composante :</b> {{ offre.structure.libelle }}
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

        <h2>Candidats en cours</h2>

        <div>
            <ul>
                <li v-for="candidature in offre.candidatures">
                    {{ candidature.intervenant.prenom+' '+candidature.intervenant.nomUsuel }}
                    <a :href="'/intervenant/code:' + candidature.intervenant.code + '/voir'">Voir</a>
                </li>

            </ul>
        </div>


        <div class="mt-5">
            <a class="btn btn-primary" href="/offre-emploi/public">Retour aux offres</a>&nbsp;
            <a :class="isDisabled" :href="postulerUrl" data-bs-toggle="tooltip" data-bs-placement="top"
               data-bs-original-title="Vous devez être connecté pour postuler">Postuler</a>


        </div>

    </div>

</template>

<script>

export default {
    name: "OffreEmploi.vue",
    props: {
        offre: {required: true},
        public: {type: Boolean, required: false},
        utilisateur: {required: false},
        intervenant: {required: false},
        extended: {type: Boolean, required: false},
        canPostuler: {type: Boolean, required: false},
        canValider: {type: Boolean, required: false},
        canSupprimer: {type: Boolean, required: false},
    },
    data()
    {

        return {
            saisirUrl: unicaenVue.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: unicaenVue.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
            validerUrl: unicaenVue.url('offre-emploi/valider/:offre', {offre: this.offre.id}),
            devaliderUrl: unicaenVue.url('offre-emploi/devalider/:offre', {offre: this.offre.id}),
            consulterUrl: unicaenVue.url('offre-emploi/public/:offre', {offre: this.offre.id}),
            postulerUrl: unicaenVue.url('offre-emploi/postuler/:offre', {offre: this.offre.id})
        };
    },
    computed: {
        isPublic: function () {
            if (this.public === false) {
                return true;
            } else if (this.public === true && this.offre.validation != null) {
                return true;
            } else {
                return false;
            }
        },
        isDisabled: function () {
            if (!this.offre.canPostuler || this.offre.candidats.indexOf(this.intervenant.id) !== -1) {
                return 'btn btn-primary disabled';
            }
            return 'btn btn-primary';
        },
        isCandidat: function () {
            if (this.offre.candidats.indexOf(this.intervenant.id) !== -1) {
                return true;
            }
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

    },

}

</script>

<style scoped>

</style>