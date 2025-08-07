<script setup lang="ts">
const props = defineProps({
    modelValue: String,
    placeholder: String,
    rows: {
        type: Number,
        default: 4
    }
})
const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <textarea
        class="w-full border border-gray-300 rounded-md p-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        :rows="rows" :placeholder="placeholder" :value="modelValue"
        @input="$emit('update:modelValue', ($event.target as HTMLTextAreaElement)?.value)" />
</template>
