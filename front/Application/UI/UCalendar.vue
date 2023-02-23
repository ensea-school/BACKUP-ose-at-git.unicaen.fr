<template>
    <select>
        <option v-for="m in listeMois()" :value="m.id">{{ m.libelle }}</option>
    </select>
    <select>
        <option v-for="a in listeAnnees()" :value="a">{{ a }}</option>
    </select>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th v-for="j in listeJours()" class="cal-th">{{ j.libelle }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="l in lignes()">
            <td v-for="j in listeJours()" class="cal-td" :data-numero="l*7+j.id"></td>
        </tr>
        </tbody>
    </table>
</template>

<script>

export default {
    name: 'UCalendar',
    props: {
        date: {type: Date, required: true},
    },
    methods: {
        listeJours()
        {
            let jours = [];

            const dateObj = new Date();

            for (let i = 1; i <= 7; i++) {
                dateObj.setDate(dateObj.getDate() - dateObj.getDay() + (i == 7 ? 0 : i));
                let nomJour = dateObj.toLocaleDateString('fr-FR', {weekday: 'long'});
                jours.push({id: i, libelle: nomJour});
            }

            return jours;
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

        lignes()
        {
            return [0,1,2,3,4];
        },
    }
}
</script>

<style scoped>

.cal-th {
    width: 10%;
}

.cal-td {
    width: 10%;
    min-height: 10em;
    height: 10em;
}

</style>