<template>
    <ul class="nav nav-tabs" role="tablist">
        <li v-for="(label,id) in tabs" class="nav-item" role="presentation">
            <a class="nav-link" :class="id === currentTab ? 'active' : ''" data-bs-togggle="tab" :data-bs-target="'#'+id" type="button" role="tab" :aria-controls="id" aria-selected="true" @click="selectTab">{{ label }}</a>
        </li>
    </ul>
    <div class="tab-content">
        <div v-for="(label,id) in tabs" :key="id" class="tab-pane fade show" :class="id === currentTab ? 'active' : ''" :id="id" role="tabpanel"
             :aria-labelledby="id+'-tab'">
            <slot :name="id"></slot>
        </div>
    </div>
</template>

<script>

export default {
    name: "UTabs",
    props: {
        tab: {type: String, required: true},
        tabs: {type: Array, required: true}
    },
    data(){
        return {
            currentTab: this.tab
        };
    },
    mounted(){

    },
    methods: {
        selectTab(event){
            this.currentTab = event.target.attributes.getNamedItem('aria-controls').value;
        },
    }
}
</script>

<style scoped>

</style>