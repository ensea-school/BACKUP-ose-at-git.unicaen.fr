<template>

    <div v-if="!this.extended" class="col">
        <div class="card h-100">
            <div class="card-header">
                <h4> {{ offre.titre }}</h4>
                <span class="badge rounded-pill bg-info">{{ offre.nombreHeures }} heure(s)</span> &nbsp;
                <!--                <span v-if="nbPostesRestants > 0" class="badge rounded-pill bg-success">{{ nbPostesRestants }} poste(s) restant(s)</span>&nbsp;
                                <span v-if="nbPostesRestants <= 0" class="badge rounded-pill bg-danger">Tous les postes sont pourvus</span>&nbsp;-->
                <span v-if="offre.validation" class="badge rounded-pill bg-success">Validée le <u-date
                    :value="offre.validation.histoCreation"/> par {{
                        offre.validation.histoCreateur.displayName
                    }}</span>
                <span v-if="!offre.validation" class="badge rounded-pill bg-warning"> En attente de validation par la DRH</span>&nbsp;
            </div>

            <div class="card-body">
                <p class="bg-light" style="padding:5px;">
                    <b>Créée le : </b>
                    <u-date :value="offre.histoCreation"/>
                    par {{ offre.histoCreateur.displayName }}<br/>
                    <b>Période à pourvoir : </b>du
                    <u-date :value="offre.dateDebut"/>
                    au
                    <u-date :value="offre.dateFin"/>
                    <br/>
                    <b>Demandée par la composante :</b> {{ offre.structure.libelleCourt }}
                    <br/>
                    <b>Type de mission :</b> {{ offre.typeMission.libelle }}

                </p>
                {{ shortDesc }}

            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <a :href="consulterUrl"
                       class="btn btn-primary"
                       title="Consulter "
                    >
                        <u-icon name="eye"/>
                        Voir
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div v-if="this.extended">
        <h1 class="page-header">{{ offre.titre }}</h1>
        <div v-if="!this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-user"></i>
            <div class="ms-2">
                Vous devez <a :href="connectionLink" class="text-decoration-underline alert-link">être identifé</a> pour
                pouvoir
                postuler.
            </div>
        </div>
        <div v-if="!offre.canPostuler && this.utilisateur" class="alert alert-primary d-flex align-items-center"
             role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Vous n'avez pas les droits pour postuler à cette offre, merci de contacter votre administration de
                rattachement.
            </div>
        </div>
        <div v-if="isCandidat && this.utilisateur" class="alert alert-primary d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-xmark"></i>
            <div class="ms-2">
                Vous avez déjà postulé à cette offre.
            </div>
        </div>

        <p class="bg-light" style="padding:10px;">
            <b>Créée le : </b>
            <u-date :value="offre.histoCreation"/>
            par {{ offre.histoCreateur.displayName }}<br/>
            <b>Période à pourvoir : </b>du
            <u-date :value="offre.dateDebut"/>
            au
            <u-date :value="offre.dateFin"/>
            <br/>
            <b>Date limite de candidature :</b>
            <u-date :value="offre.dateLimite"/>
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
        <p v-html="this.descriptionHtml"></p>
        <p v-if="this.decretText" class="alert alert-info">
            <input id="decret" v-model="decret" name="decret" type="checkbox">&nbsp;
            <span v-html="this.decretText"></span>

        </p>
        <br/>
        <div v-if="this.canVoirCandidature">
            <h5><strong>Liste des candidats :</strong></h5>

            <table class="table table-bordered ">
                <thead>
                <tr>
                    <th>Intervenant</th>
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
                    <td><a :href="urlVoir(candidature)">
                        {{ candidature.intervenant.prenom + ' ' + candidature.intervenant.nomUsuel }}</a></td>
                    <td> <span v-if="candidature.validation" class="badge rounded-pill bg-success">Acceptée le <u-date
                        :value="candidature.validation.histoCreation"/> par {{
                            candidature.validation.histoCreateur.displayName
                        }}</span>
                        <span v-if="!candidature.validation && candidature.motif !== null"
                              class="badge rounded-pill bg-danger">{{ candidature.motif }}</span>
                        <span v-if="!candidature.validation && candidature.motif === null"
                              class="badge rounded-pill bg-warning">En attente d'acceptation</span>
                    </td>
                    <td v-if="this.canValiderCandidature">
                        <a :href="urlVoirCandidature(candidature)"
                           class="btn btn-primary"
                           title="Consulter "
                        >
                            <u-icon name="eye"/>
                            Voir
                        </a>

                    </td>
                </tr>
                </tbody>

            </table>
        </div>


        <div class="mt-5">
            <a :href="offreEmploiUrl" class="btn btn-secondary">Retour aux offres</a>&nbsp;
            <a v-if="this.canPostuler" :class="!decret?'disabled':''" :href="postulerUrl"
               class="btn btn-primary"
               data-bs-original-title="Vous devez être connecté pour postuler"
               data-bs-placement="top"
               data-bs-toggle="tooltip">Postuler</a>&nbsp;
            <a v-if="offre.canModifier"
               :href="saisirUrl"
               class="btn btn-primary"
               title="Modifier"
               @click.prevent="saisir">
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
               data-content="Êtes-vous sur de vouloir supprimer l'offre ?"
               data-title="Suppression de l'offre"
               title="Supprimer"
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
        decretText: {type: String, required: false},
    },
    data()
    {


        return {
            saisirUrl: unicaenVue.url('offre-emploi/saisir/:offre', {offre: this.offre.id}),
            supprimerUrl: unicaenVue.url("offre-emploi/supprimer/:offre", {offre: this.offre.id}),
            validerUrl: unicaenVue.url('offre-emploi/valider/:offre', {offre: this.offre.id}),
            devaliderUrl: unicaenVue.url('offre-emploi/devalider/:offre', {offre: this.offre.id}),
            consulterUrl: unicaenVue.url('offre-emploi/detail/:offre', {offre: this.offre.id}),
            offreEmploiUrl: unicaenVue.url('offre-emploi'),
            postulerUrl: unicaenVue.url('offre-emploi/postuler/:id', {id: this.offre.id}),
            decret: false,


        };
    },
    computed: {
        isCandidat: function () {
            return false;
        },
        shortDesc: function () {
            if (!this.offre.description) {
                return '';
            }
            let shorDesc = this.offre.description.substr(0, 200);
            if (this.offre.description.length > 200) {
                shorDesc += '...';
            }
            return shorDesc;
        },
        descriptionHtml: function () {
            if (!this.offre.description) {
                return '';
            }
            return this.offre.description.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },
        connectionLink: function () {
            let url = 'auth/connexion?redirect=' + window.location.href;
            return unicaenVue.url(url);

        },


    },
    mounted()
    {
        if (!this.decretText) {
            this.decret = true;
        }
    },

    methods: {
        saisir(event)
        {
            modAjax(event.target, (widget) => {
                this.refresh();
            });
        },
        urlVoir: function (candidature) {
            return unicaenVue.url('intervenant/:code/voir', {code: 'code:' + candidature.intervenant.code})
        },
        urlVoirCandidature: function (candidature) {
            return unicaenVue.url('intervenant/:code/candidature', {code: 'code:' + candidature.intervenant.code})
        },
        urlAccepterCandidature: function (candidature) {
            return unicaenVue.url('offre-emploi/accepter-candidature/:id', {id: candidature.id})
        },
        urlRefuserCandidature: function (candidature) {
            return unicaenVue.url('offre-emploi/refuser-candidature/:id', {id: candidature.id})
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
            popConfirm(event.currentTarget, (response) => {
                this.$emit('supprimer', this.offre);
            });
        },
        postuler(event)
        {
            popConfirm(event.currentTarget, (response) => {
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
        },
        refuserCandidature(event)
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