<template>
    <div :class="{'bg-success':prime.validation,'bg-default':!prime.validation&&!prime.dateRefus,'bg-danger':prime.dateRefus}"
         class="card">
        <div :class="{'text-white':prime.dateRefus}" class="card-header card-header-h3">
            <h5 v-if="prime.id">
                Indemnité de fin de contrat N°{{ numero+1 }}<br/>
            </h5>
            <div>
                <u>Mission(s) concernée(s)</u> :
                <span v-for="(mission, index) in prime.missions">
                        {{ mission.typeMission.libelle }} (Pour {{ mission.structure.libelleCourt }} du <u-date :value="mission.dateDebut"/> au <u-date
                    :value="mission.dateFin"/>)
                    <span v-if="index != prime.missions.length - 1">, </span>
                </span>
            </div>


        </div>
        <form :action="declarationUrl" enctype="multipart/form-data" method="post">
            <div class="card-body">
                <div class="row">


                    <div v-if="!prime.declaration" class="col-md-6">

                        <div class="card text-dark bg-light">
                            <div class="card-header">
                                Dépôt de votre déclaration sur l'honneur
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    Pour <b>bénéficier de votre indemnité de fin de contrat</b>, vous devez déposer une déclaration sur l'honneur (<a
                                    href="">exemple</a>) signée précisant que vous
                                    ne
                                    débutez pas
                                    d'autre contrat dans la fonction au publique à la suite de ces missions.
                                </p>

                                <input ref="file" :disabled="!prime.dateRefus ? false : true" name="files[]" type="file"/>

                            </div>
                            <div class="card-footer d-grid gap-2">

                                <input :disabled="!prime.dateRefus ? false : true" class="btn btn-primary " type="submit" value="Envoyer">
                            </div>
                        </div>
                    </div>
                    <div v-if="prime.declaration" class="col-md-6">

                        <div class="card text-dark bg-light">
                            <div class="card-header">
                                Dépôt de votre déclaration sur l'honneur
                            </div>
                            <div class="card-body">
                                <p class="card-text">Vous pouvez télécharger votre déclaration sur l'honneur ci-dessous : </p>
                                <a :href="telechargerUrl">{{ prime.declaration.nom }}</a>


                            </div>
                            <div class="card-footer" style="text-align:right;">
                                <a v-if="prime.declaration && !prime.validation && !prime.dateRefus"
                                   :href="supprimerUrl"
                                   class="btn btn-danger"
                                   title="Supprimer"
                                   @click.prevent="supprimer">
                                    Supprimer
                                </a>&nbsp;
                                <a v-if="prime.declaration && !prime.validation && this.canGerer  && !prime.dateRefus"
                                   :href="validerUrl"
                                   class="btn btn-success"
                                   title="Valider"
                                   @click.prevent="valider">
                                    Valider
                                </a>&nbsp;
                                <a v-if="prime.declaration && prime.validation && this.canGerer  && !prime.dateRefus"
                                   :href="devaliderUrl"
                                   class="btn btn-danger d-grid gap-2"
                                   title="Dévalider"
                                   @click.prevent="devalider">
                                    Dévalider
                                </a>&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div v-if="!prime.validation">
                            <input :checked="prime.dateRefus" :disabled="prime.validation" name="prime" type="checkbox"
                                   @change="refuser"/>&nbsp;
                            Ou en cochant cette case, <b>je déclare ne pas pouvoir bénéficier d'une indemnité</b> de fin de contrat en raison du démarrage d'un
                            nouveau
                            contrat au sein de la
                            fonction publique à la suite de ces missions.
                            <br/><br/>

                        </div>
                        <div>
                            <div>
                                <label class=" form-label">Suivi de la déclaration : </label>
                            </div>
                            <div v-if="prime.dateRefus">
                                <u-icon name="thumbs-down"
                                        variant="danger"/>
                                Indemnité refusée le
                                <u-date :value="prime.dateRefus"/>
                            </div>
                            <!--Etat du dépôt de la déclaration-->
                            <div v-if="prime.declaration && !prime.dateRefus">
                                <u-icon name="thumbs-up"
                                        variant="success"/>
                                Déclaration déposée le
                                <u-date :value="prime.declaration.histoCreation"/>
                                par {{ prime.declaration.histoCreateur.displayName }}
                            </div>
                            <div v-if="!prime.declaration && !prime.dateRefus">
                                <u-icon name="thumbs-down"
                                        variant="info"/>
                                Aucune déclaration déposée

                            </div>
                            <!--Etat de la validation de la déclaration-->
                            <div v-if="prime.validation && !prime.dateRefus">
                                <u-icon name="thumbs-up"
                                        variant="success"/>
                                Déclaration validée le
                                <u-date :value="prime.validation.histoCreation"/>
                                par {{ prime.validation.histoCreateur.displayName }}
                            </div>
                            <div v-if="!prime.validation && !prime.dateRefus">
                                <u-icon name="thumbs-down"
                                        variant="info"/>
                                Aucune déclaration validée

                            </div>
                            <!--Eligibilité à la prime de fin de contrat-->
                            <div v-if="prime.validation && !prime.dateRefus">
                                <u-icon name="euro-sign"
                                        variant="success"/>
                                Intervenant éligible à une indemnité de fin de contrat
                            </div>
                            <div v-if="prime.dateRefus">
                                <u-icon name="euro-sign"
                                        variant="info"/>
                                Intervenant non éligible à une indemnité de fin de contrat

                            </div>

                        </div>
                    </div>

                </div>
                <!--Gestion de la prime-->
                <div class="row">
                    <!-- Boutons d'actions -->
                    <div class="col-md-12 ">
                      <span class="float-end">
                          <a
                              v-if="!prime.validation && !prime.dateRefus  && this.canGerer"
                              :href="modifierPrimeUrl"
                              class="btn btn-primary"
                              @click.prevent="modifierPrime"
                          >Modifier</a>
                          &nbsp;
                          <a
                              v-if="!prime.validation && !prime.dateRefus && this.canGerer"
                              :href="supprimerPrimeUrl"
                              class="btn btn-danger"
                              @click.prevent="supprimerPrime"
                          >Supprimer</a>
                          </span>
                    </div>
                </div>
            </div>
        </form>
    </div>


</template>

<script>

export default {
    name: "Prime.vue",
    props: {
        prime: {required: true},
        numero: {required: false},
        intervenant: {required: true},
        canGerer: {type: Boolean, required: false},

    },
    data()
    {
        return {
            declarationUrl: unicaenVue.url("prime/:intervenant/declaration-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            supprimerUrl: unicaenVue.url("prime/:intervenant/supprimer-declaration-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            validerUrl: unicaenVue.url("prime/:intervenant/valider-declaration-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            devaliderUrl: unicaenVue.url("prime/:intervenant/devalider-declaration-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            telechargerUrl: unicaenVue.url("prime/:intervenant/telecharger-declaration-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            refuserUrl: unicaenVue.url("prime/:intervenant/refuser-prime/:prime", {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            modifierPrimeUrl: unicaenVue.url('prime/:intervenant/saisie/:prime', {
                intervenant: this.intervenant,
                prime: this.prime.id
            }),
            supprimerPrimeUrl: unicaenVue.url('prime/:intervenant/supprimer-prime/:prime', {
                intervenant: this.intervenant,
                prime: this.prime.id
            })


        };
    },
    computed: {},
    methods: {

        supprimer(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('reload');
            });
        },
        valider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('reload');
            });
        },
        devalider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('reload');
            });
        },
        refuser()
        {
            unicaenVue.axios.post(
                this.refuserUrl
            )
                .then(response => {

                    this.$emit('reload');

                })

        },
        modifierPrime(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.$emit('reload');
            });
        },
        supprimerPrime(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.$emit('reload');
            });

        }

    }


}

</script>

<style scoped>

</style>