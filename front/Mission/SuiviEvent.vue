<template>
    <div class="event-content">
        <p class="mission">{{ event.mission.libelleCourt }}</p>
        <p class="horaires">
            de {{ heureDebut }} à {{ heureFin }}, soient {{ heures }} heures
            <b-badge v-if="event.nocturne">Nocturne</b-badge>
            <b-badge v-if="event.formation">En formation</b-badge>
        </p>
        <p class="description" v-if="event.description">{{ event.description }}</p>
    </div>
    <div class="event-actions">
        <div class="btn-group btn-group-sm">
            <button class="btn btn-light" @click="modifier" title="Modifier le suivi">
                <u-icon name="pen-to-square"/>
            </button>
            <button class="btn btn-light" @click="valider" title="Valider le suivi">
                <u-icon name="check" class="text-success"/>
            </button>
            <button class="btn btn-light" @click="devalider" title="Dévalider le suivi"
                    data-content="Voulez-vous vraiment dévalider ce suivi ?">
                <u-icon name="xmark" class="text-danger"/>
            </button>
            <button class="btn btn-light" @click="supprimer" title="Supprimer le suivi"
                    data-content="Voulez-vous vraiment supprimer ce suivi ?">
                <u-icon name="trash-can" class="text-danger"/>
            </button>
        </div>
    </div>
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
            const urlParams = {
                intervenant: this.event.intervenant,
                id: this.event.id
            };
            event.currentTarget.dataset.url = unicaenVue.url('mission/suivi/modification/:id', urlParams);
            modAjax(event.currentTarget, (widget) => {
                this.suivi.refresh();
            });
        },

        supprimer(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.suivi.refresh();
            });
        },

        valider(event)
        {
            this.suivi.refresh();
        },

        devalider(event)
        {
            popConfirm(event.currentTarget, (response) => {
                this.suivi.refresh();
            });
        },

    },
}
</script>

<style scoped>

.event-content {
    flex-grow: 1;
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