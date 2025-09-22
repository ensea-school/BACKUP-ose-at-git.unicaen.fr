<template>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" id="navbar">
        <div class="container">
            <a class="navbar-brand" :href="data.appUrl" :title="data.appTitle"><h1 class="title">{{ data.appName }}</h1></a>
            <span class="annee"><select v-model="data.annee" onchange="Util.changementAnnee(this.value)">
                <option v-for="(label, value) in data.annees" :value="value">
                    {{ label }}
                </option>
            </select></span>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item" v-for="page in data.menuItems">
                        <a :class="{'nav-link':true, active:page.active}" :href="page.url">
                            {{  page.label }}
                        </a>
                    </li>

                </ul>
                <Connexion :data="data.connexion" />
            </div>
        </div>
    </nav>
</template>

<script>
import Connexion from "@/Application/Connexion.vue";

export default {
    name: "Navbar",
    components: {Connexion},
    props: {
        data: Object
    },
    data()
    {
        return {
            identity: '',
            currentRoleId: null,
            selectedStructures: {}
        };
    },
    methods: {

    },
    computed: {

    }
}
</script>

<style scoped>


</style>