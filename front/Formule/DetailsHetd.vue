<template>
    <td :class="arrondiClass" :title="legende">
        <u-heures :valeur="hetd.valeur"></u-heures>
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
            }
            if (this.hetd.original != this.hetd.valeur){
                return 'arrondi';
            }
            return "";
        },
        legende()
        {
            let titre = "";

            if (this.hetd.arrondi == 1){
                titre = "L'arrondisseur de règle de calcul a procédé à un arrondi à l'excès";
            }else if (this.hetd.arrondi == -1){
                titre = "L'arrondisseur de règle de calcul a procédé à un arrondi par troncature";
            }

            if (this.hetd.original != this.hetd.valeur){
                if (titre != ""){
                    titre += "\n";
                }
                titre += "Valeur originale : " + Util.floatToString(this.hetd.original, 15);
            }

            return titre;
        }
    },
}

</script>
<style scoped>

.arrondi {
    text-decoration: underline dotted;
}

.arrondi-defaut {
    color: blue;
    background-color: #E6E6FF;
    text-decoration: underline dotted;
}

.arrondi-exces {
    color: red;
    background-color: #FFE6E6;
    text-decoration: underline dotted;
}

</style>