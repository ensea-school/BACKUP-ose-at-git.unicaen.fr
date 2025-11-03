<template>
    <div class="event-content">
        <p class="mission">{{ event.mission.libelleMission || event.mission.libelleCourt }} <span class="badge bg-success" v-if="event.valide">Validé</span></p>
        <p class="horaires">
            de {{ heureDebut }} à {{ heureFin }}, soit {{ heures }} heure{{ heures < 2 ? '' : 's' }}
                <span class="badge bg-secondary" v-if="event.formation">En formation</span>
        </p>
        <p class="description" v-if="event.description">{{ event.description }}</p>
    </div>
    <div class="event-actions">
        <div class="btn-group btn-group-sm">
            <button v-if="event.canEdit" class="btn btn-light" @click="modifier" data-title="Modifier le suivi"
                    title="Modifier le suivi"
                    :data-url="modifierUrl">
                <u-icon name="pen-to-square"/>
            </button>
            <button v-if="event.canValider" class="btn btn-light" @click="valider" data-title="Valider le suivi"
                    title="Valider le suivi"
                    :data-url="validerUrl">
                <u-icon name="check" class="text-success"/>
            </button>
            <button v-if="event.canDevalider" class="btn btn-light" @click="devalider" data-title="Dévalider le suivi"
                    title="Dévalider le suivi" :data-url="devaliderUrl"
                    data-content="Voulez-vous vraiment dévalider ce suivi ?">
                <u-icon name="xmark" class="text-danger"/>
            </button>
            <button v-if="event.canSupprimer" class="btn btn-light" @click="supprimer" data-title="Supprimer le suivi"
                    title="Supprimer le suivi" :data-url="supprimerUrl"
                    data-content="Voulez-vous vraiment supprimer ce suivi ?">
                <u-icon name="trash-can" class="text-danger"/>
            </button>
        </div>
    </div>
    <u-confirm-dialog ref="confirmDialog"/>
</template>

<script>
export default {
    name: "SuiviEvent",
    props: {
        event: {type: Object, required: true},
    },
    data()
    {
        return {
            suivi: this.$parent.$parent,
            modifierUrl: unicaenVue.url('mission/suivi/modifier/:id', {id: this.event.id}),
            supprimerUrl: unicaenVue.url('mission/suivi/supprimer/:id', {id: this.event.id}),
            validerUrl: unicaenVue.url('mission/suivi/valider/:id', {id: this.event.id}),
            devaliderUrl: unicaenVue.url('mission/suivi/devalider/:id', {id: this.event.id}),
        };
    },
    computed: {
        heureDebut()
        {
            return this.event.heureDebut.toString().replace(':', 'h');
        },
        heureFin()
        {
            return this.event.heureFin.toString().replace(':', 'h');
        },
        heures()
        {
            return Util.floatToString(this.event.heures);
        },
    },
    methods: {
        modifier(event)
        {
            modAjax(event.currentTarget, (widget) => {
                this.suivi.refresh();
            });
        },

        async supprimer(event)
        {
            const url = event.currentTarget.dataset.url;
            const title = event.currentTarget.dataset.title;
            const content = event.currentTarget.dataset.content;
            const confirmed = await this.$refs.confirmDialog.open(
                content,
                title
            );
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.suivi.refresh();
                }
            }

        },

        valider(event)
        {
            unicaenVue.axios.get(this.validerUrl).then(response => {
                this.suivi.refresh();
            });
        },

        async devalider(event)
        {
            const url = event.currentTarget.dataset.url;
            const title = event.currentTarget.dataset.title;
            const content = event.currentTarget.dataset.content;
            const confirmed = await this.$refs.confirmDialog.open(
                content,
                title
            );
            if (!url) {
                console.error("Aucune URL trouvée sur le bouton !");
                return;
            }
            if (confirmed) {
                const response = await unicaenVue.axios.get(url);
                if (response && response.data) {
                    this.suivi.refresh();
                }
            }
        },

    },
}
</script>

<style scoped>

.event-content {
    flex-grow: 1;
}

.event-content.valide {
    background-color:yellow;
}

.event-content p {
    margin-bottom: .2rem;
}

.event-content p.mission {
    font-weight: bold;
}

.event-content p.horaires {
    font-style: italic;
    font-weight: lighter;
}

.event-actions {
    align-self: flex-start;
}

</style>