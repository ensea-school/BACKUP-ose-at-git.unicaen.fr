<template>
    <p>Mon ID : {{ monId }}</p>

    <h2>Contacts</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="c in contacts">
            <td>{{ c.nom }}</td>
            <td>{{ c.prenom }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "MonTest",
    props: {
        monId: {required: true, type: Number}
    },
    data()
    {
        return {
            contacts: []
        };
    },
    mounted()
    {
        unicaenVue.axios.get(
            unicaenVue.url("mission/test-data/:monId", {monId: this.monId})
        ).then(response => {
            this.contacts = response.data;
        });
    }
}

</script>

<style scoped>

</style>