<script setup lang="ts">
import { onMounted, ref } from 'vue';

const model = defineModel();

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input ref="input" class="rounded-md border-gray-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" v-model="model" />
</template>
