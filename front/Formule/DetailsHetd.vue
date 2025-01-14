<template>
    <td>
        <div :class="arrondiClass" :title="legende">
            <u-heures :valeur="hetd.valeur"></u-heures>
        </div>
    </td>
</template>
<script>

export default {
    name: 'DetailsHetd',
    components: {},
    props: {
        type: {type: String},
        hetd: {type: Object},
    },
    computed: {
        arrondiClass()
        {
            switch(this.hetd.arrondi){
                case -1: return 'arrondi-defaut';
                case 1: return 'arrondi-exces';
                default: return "";
            }
        },
        legende()
        {
            let titre = "";

            if (this.hetd.arrondi == 1){
                titre = "Arrondi à l'excès";
            }else if (this.hetd.arrondi == -1){
                titre = "Arrondi par défaut";
            }

            if (this.hetd.original != this.hetd.valeur){
                if (titre != ""){
                    titre += "\n";
                }
                titre += "Valeur originale : " + Util.floatToString(this.hetd.original);
            }

            return titre;
        }
    },
}

</script>
<style scoped>

.arrondi-defaut {
    color: #083d77;
}

.arrondi-exces {
    color: #8c1c2e;
}

</style>