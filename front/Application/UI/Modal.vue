<template>
    <div :id="id" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" :class="dialogClasses">
            <div class="modal-content" :class="{'modal-content-scrollable': scrollable}">
                <div class="vue-modal-layout">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';

const props = defineProps({
    id: {type: String, required: true},
    size: {type: String, default: 'lg', validator: value => ['sm', 'lg', 'xl', 'fullscreen'].includes(value)},
    scrollable: {type: Boolean, default: true},
    centered: {type: Boolean, default: false},
});

const dialogClasses = computed(() => ({
    [`modal-${props.size}`]: Boolean(props.size),
    'modal-dialog-scrollable': props.scrollable,
    'modal-dialog-centered': props.centered,
}));
</script>

<style scoped>
.modal-content-scrollable {
    height: auto;
    max-height: calc(80vh - 3.5rem);
}

.vue-modal-layout {
    display: flex;
    flex: 1 1 auto;
    flex-direction: column;
    min-height: 0;
    overflow: hidden;
}

:deep(.modal-body) {
    flex: 1 1 auto;
    min-height: 0;
    overflow-y: auto;
}

:deep(.modal-dialog-scrollable) {
    height: auto;
    max-height: calc(80vh - 3.5rem);
}

:deep(.modal-dialog-centered.modal-dialog-scrollable) {
    min-height: 0;
}

@media (max-width: 575.98px) {
    .modal-content-scrollable,
    :deep(.modal-dialog-scrollable) {
        max-height: calc(80vh - 1rem);
    }
}
</style>
