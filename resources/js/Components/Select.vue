<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue', 'options', 'text']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <select
        class="border-gray-300 focus:border-primary focus:outline-none p"
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        ref="input"
    >
        <option value="">Seleccionar {{ text }}</option>
        <option v-for="opt in options" :value="opt['id']">{{opt['name']}}</option>
    </select>
</template>
