<template>
    {{ formatted }}
</template>

<script>
export default {
    name: "UDate",
    props: {
        'value': {required: false, type: [String,Date]},
        'format': {required: false, type: String},
    },
    mounted(){
        this.formatted = this.formatage(this.value);
    },
    data(){
        return {
            formatted: undefined,
        };
    },
    watch: {
        'value': function(val){
            this.formatted = this.formatage(val);
        },
    },
    methods: {
        formatage(val){
            if (val === undefined) {
                return undefined;
            }
            let date = new Date(val);

            const year = date.getFullYear();
            const month = (date.getMonth() + 1).toString().padStart(2, '0');
            const day = date.getDate().toString().padStart(2, '0');
            const hour = date.getHours().toString().padStart(2, '0');
            const min =  date.getMinutes().toString().padStart(2, '0');
            const sec =  date.getSeconds().toString().padStart(2, '0');

            switch(this.format){
                case 'datetime':
                    return `${day}/${month}/${year} à ${hour}:${min}`;
                case 'time':
                    return `${hour}:${min}:${sec}`;
            }
            // format 0 : DATE par défaut
            return `${day}/${month}/${year}`;
        }
    },
}
</script>

<style scoped>

</style>