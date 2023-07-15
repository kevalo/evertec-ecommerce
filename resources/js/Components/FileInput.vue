<script setup>
import { onMounted, ref } from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        class=" p-0 file-input file-input-secondary file-input-sm file-input-bordered w-full max-w-xs"
        @input="$emit('update:modelValue', $event.target.files[0])"
        ref="input"
        type="file"
    />
</template>
