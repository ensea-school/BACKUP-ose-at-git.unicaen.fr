<template>
    <div class="calendar">
        <div class="recherche">
            <div class="recherche btn-group">
                <button class="btn btn-light" id="prevMois" @click="prevMois" title="Mois précédant">
                    <u-icon name="chevron-left"/>
                </button>

                <select class="form-select btn btn-light" id="otherMois" v-model="mois">
                    <option v-for="m in listeMois()" :value="m.id">{{ m.libelle }}</option>
                </select>

                <select class="form-select btn btn-light" id="otherAnnee" v-model="annee">
                    <option v-for="a in listeAnnees()" :value="a">{{ a }}</option>
                </select>

                <button class="btn btn-light" id="nextMois" @click="nextMois" title="Mois suivant">
                    <u-icon name="chevron-right"/>
                </button>
            </div>
        </div>

        <table class="table table-bordered table-hover table-sm">
            <tr v-for="jour in listeJours" :data-jour="jour">
                <th class="nom-jour">
                    {{ nomJour(jour) }}
                </th>
                <th class="numero-jour">
                    <div class="num-jour badge bg-secondary rounded-circle">{{ jour < 10 ? '0' + jour.toString() : jour }}</div>
                </th>
                <td>
                    <div v-for="event in eventsByJour(jour)">
                        <component :is="event.component" :event="event"/>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</template>

<script>

export default {
    name: 'UCalendar',
    props: {
        date: {type: Date, required: true},
        events: {type: Array, required: true},
    },
    data()
    {
        const dateObj = new Date(this.date);

        return {
            mois: dateObj.getMonth() + 1,
            annee: dateObj.getFullYear(),
        };
    },
    computed: {
        listeJours()
        {
            const dateObj = new Date(this.date);

            dateObj.setDate(1);
            dateObj.setMonth(dateObj.getMonth() + 1);
            dateObj.setDate(dateObj.getDate() - 1);

            let nombreJours = dateObj.getDate();

            return Array.from({length: nombreJours}, (v, k) => k + 1)
        }
    },
    watch: {
        date: function (newVal, oldVal) { // watch it
            const dateObj = new Date(this.date);

            this.mois = dateObj.getMonth() + 1;
            this.annee = dateObj.getFullYear();
        },
        mois: function (newVal, oldVal) { // watch it
            const dateObj = new Date(this.date);
            dateObj.setMonth(newVal - 1);

            this.$emit('changeDate', dateObj);
        },
        annee: function (newVal, oldVal) { // watch it
            const dateObj = new Date(this.date);
            dateObj.setFullYear(newVal);

            this.$emit('changeDate', dateObj);
        }
    },
    methods: {
        nomJour(numJour)
        {
            const dateObj = new Date(this.date);
            dateObj.setDate(numJour);
            return dateObj.toLocaleString("fr-FR", {weekday: "short"});
        },

        listeMois()
        {
            let mois = [];

            const dateObj = new Date();

            for (let i = 1; i <= 12; i++) {
                dateObj.setMonth(i - 1);
                let nomMois = dateObj.toLocaleString("fr-FR", {month: "long"});
                mois.push({id: i, libelle: nomMois});
            }

            return mois;
        },

        listeAnnees()
        {
            const dateObj = new Date();
            const annee = dateObj.getFullYear();
            const range = 1;

            let annees = [];

            for (let a = annee - range; a <= annee + range; a++) {
                annees.push(a);
            }

            return annees;
        },

        nombreJours()
        {
            const dateObj = new Date(this.date);

            dateObj.setDate(1);
            dateObj.setMonth(dateObj.getMonth() + 1);
            dateObj.setDate(dateObj.getDate() - 1);

            return dateObj.getDate();
        },

        prevMois()
        {
            const dateObj = new Date(this.date);
            dateObj.setMonth(dateObj.getMonth() - 1);

            this.$emit('changeDate', dateObj);
        },

        nextMois()
        {
            const dateObj = new Date(this.date);
            dateObj.setMonth(dateObj.getMonth() + 1);

            this.$emit('changeDate', dateObj);
        },

        eventsByJour(jour)
        {
            const dateObj = new Date(this.date);

            let res = [];
            for (let e in this.events) {
                let event = this.events[e];
                if (event.date.getFullYear() === dateObj.getFullYear()
                    && event.date.getMonth() + 1 === dateObj.getMonth() + 1
                    && event.date.getDate() === jour
                ) {
                    res.push(event);
                }
            }
            return res;
        },
    }
}
</script>

<style scoped>

.recherche {
    text-align: center;
}

.recherche .btn-group {
    box-shadow: none;
    margin: auto;
}

.recherche select.btn {
    padding-right: 3em;
}

th.nom-jour {
    width: 1%;
}

th.numero-jour {
    width: 1%;
    padding-right: .5em;
}

.recherche {
    justify-content: center;
    padding-bottom: 5px;
}

</style>