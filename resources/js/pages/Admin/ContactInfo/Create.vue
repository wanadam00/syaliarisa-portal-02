<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    type: '',
    value: ''
});
function submit() {
    form.post(route('admin.contact-info.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Contact Info</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-lg">
                <div>
                    <label class="block mb-1 font-medium">Type</label>
                    <input v-model="form.type" required class="w-full border rounded-md p-2" />
                </div>
                <div>
                    <label class="block mb-1 font-medium">Value</label>
                    <input v-model="form.value" required class="w-full border rounded-md p-2" />
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </AppLayout>
</template>
