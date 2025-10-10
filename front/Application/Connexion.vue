<template>
    <a v-if="data.connecte" class="navbar-link" id="user-current-info" href="#">
        <span id="user-status">
            <span :class="roleIcon"></span>
            <span class="caret">&nbsp;</span>
            <span id="user-status-name"><strong>{{ data.utilisateurNom }}</strong>, {{ data.roleNom }}</span>
        </span>
    </a>
    <span v-if="data.connecte">|</span>
    <a class="navbar-link user-connection" :href="connexionUrl" :title="connexionUrl">{{ connexionContent }}</a>

    <b-popover
        :click="true"
        :close-on-hide="true"
        :delay="{show: 0, hide: 0}"
        target="user-current-info"
    >
        <template #title>Utilisateur connecté à l'application</template>

        <!-- Rôles & structures -->
        <div>
            <strong>Profil utilisateur :</strong>
        </div>
        <div>
            <form method="POST" class="user-profile-select-form">
                <div v-for="(role,id) in data.roles" :key="id" class="radio">
                    <label>
                        <input type="radio" name="role" class="user-profile-select-input"
                               title="Cliquez pour changer de profil courant"
                               :value="id"
                               @click="userProfileChange(id)"
                               v-model="currentRoleId">{{ role.libelle }}</label>&nbsp;
                    <select v-if="data.roles[id].peutChangerStructure" class="user-profile-select-input-structure"
                            v-model="selectedStructures[id]"
                            @change="userProfileChange(id)"
                            title="Cliquez pour sélectionner la structure associée au profil Administrateur">
                        <option value="-1">- toutes structures -</option>
                        <option v-for="(libelle,id) in data.structures" :key="id" :value="id">{{ libelle }}</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Usurpation -->
        <div v-if="data.usurpationEnabled && !data.usurpationEnCours">
            <form :action="startUsurpationUrl">
                <div>
                    <strong>Usurpation d'identité :</strong>
                </div>
                <div class="mb-2">
                    <input type="text" name="identity" v-model="identity" class="user-usurpation-input form-control"
                           placeholder="Identifiant utilisateur" value=""/>
                </div>
                <div class="mb-2">
                    <input type="submit" name="submit" :disabled="!identity"
                           class="user-usurpation-submit btn btn-danger form-control" value="Usurper"/>
                </div>
            </form>
        </div>
        <div v-if="data.usurpationEnabled && data.usurpationEnCours">
            <b-button variant="danger" :href="stopUsurpationUrl">Stopper l'usurpation</b-button>
        </div>
    </b-popover>
</template>

<script>
export default {
    name: "Connexion",
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
    mounted()
    {
        this.currentRoleId = this.data.roleId;
        for (let id in this.data.roles) {
            this.selectedStructures[id] = -1;
        }
        this.selectedStructures[this.currentRoleId] = this.data.structureId ?? -1;
    },
    methods: {
        userProfileChange(roleId)
        {
            let structureId = this.selectedStructures[roleId];
            if (-1 == structureId) {
                structureId = null;
            }
            if (roleId != this.currentRoleId) {
                this.currentRoleId = roleId;
            }

            document.body.classList.add("wait-cursor");
            unicaenVue.axios.post(
                this.selectionProfilUrl,
                {role: roleId, structure: structureId, route: this.data.route}
            ).then((response) => {
                if (true === response.data.needGoHome) {
                    window.location.href = unicaenVue.url('');
                }else{
                    window.location.reload();
                }
            });
        },
    },
    computed: {
        roleIcon()
        {
            if (this.data.usurpationEnCours) {
                return 'fa fa-theater-masks';
            } else {
                return 'fa fa-user';
            }
        },
        roleContent()
        {
            return "coucou!!!";
        },
        connexionUrl()
        {
            if (this.data.connecte) {
                return unicaenVue.url('auth/deconnexion');
            } else {
                return unicaenVue.url('auth/connexion');
            }
        },
        connexionTitle()
        {
            if (this.data.connecte) {
                return "Supprime les informations de connexion";
            } else {
                return "Affiche le formulaire d'authentification";
            }
        },
        connexionContent()
        {
            if (this.data.connecte) {
                return "Déconnexion";
            } else {
                return "Connexion";
            }
        },
        startUsurpationUrl()
        {
            return unicaenVue.url('utilisateur/usurper-identite');
        },
        stopUsurpationUrl()
        {
            return unicaenVue.url('utilisateur/stopper-usurpation');
        },
        selectionProfilUrl()
        {
            return unicaenVue.url('utilisateur/selectionner-profil');
        },
    }
}
</script>

<style scoped>

.user-profile-select-form {
    padding-left: 25px;
}

</style>