<template>
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" :data-bs-target="`#str${structure.id}`" aria-expanded="true" :aria-controls="structure.id">

                <a v-if="structure.canEdit"
                   title="Modifier la structure"
                   :href="saisieUrl"
                   class="btn btn-primary btn-sm"
                   @click.prevent="saisie"><i class="fas fa-pen-to-square"></i></a>

                <a v-if="structure.canDelete"
                   title="Supprimer la structure"
                   :href="deleteUrl"
                   class="btn btn-danger btn-sm"
                   data-content="Êtes-vous sûr de vouloir supprimer la structure ?"
                   data-title="Suppression de la structure"
                   @click.prevent="supprimer"><i class="fas fa-trash-can"></i></a>

                &nbsp;

                {{ structure.libelleLong }} ({{ structure.libelleCourt }})

            </button>
        </h2>
        <div :id="`str${structure.id}`" class="accordion-collapse collapse show">
            <div class="accordion-body">

                <div class="row">
                    <div class="col-md-5"><strong>Code</strong> : </div>
                    <div class="col-md-5">{{ structure.code }}</div>
                </div>
                <div class="row">
                    <div class="col-md-5"><strong>Source</strong> : </div>
                    <div class="col-md-5">{{ structure.source.libelle }}</div>
                </div>
                <div class="row">
                    <div class="col-md-5"><strong>Composante d'enseignement</strong> : </div>
                    <div class="col-md-5">
                        <i v-if="structure.enseignement" class="fas fa-check text-success" />
                        <i v-else-if="!structure.enseignement" class="fas fa-xmark text-danger" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5"><strong>Affichage de l'adresse sur le contrat de travail</strong> : </div>
                    <div class="col-md-5">
                        <i v-if="structure.affAdresseContrat" class="fas fa-check text-success" />
                        <i v-else-if="!structure.affAdresseContrat" class="fas fa-xmark text-danger" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5"><strong>Adresse</strong> : </div>
                    <div class="col-md-5"><pre>{{ structure.adresse }}</pre></div>
                </div>

                <div v-if="hasSousStructures" class="sous-structures">
                    <h4>Sous-structures</h4>
                    <div class="accordion">
                        <structure v-for="structure in structure.structures"
                                   @refresh="refresh"
                                   :key="structure.id"
                                   :structure="structure"
                        ></structure>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: 'Structure',
    props: {
        structure: {required: true}
    },
    data()
    {
        return {
            saisieUrl: unicaenVue.url('structure/voir/:structure', {structure: this.structure.id})+'?tab=edition',
            deleteUrl: unicaenVue.url('structure/delete/:structure', {structure: this.structure.id}),
            liste: this.$parent.liste,
        };
    },
    computed: {
        hasSousStructures: function () {
            return this.structure.structures.length > 0;
        }
    },
    methods: {
        saisie(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.refresh();
            });
        },
        supprimer(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.refresh();
            });
        },
        refresh()
        {
            this.liste.reload();
        }
    }
}
</script>

<style scoped>

.sous-structures {
    padding-left: 4em;
}

</style>