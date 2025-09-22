<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const form = useForm({
    name: '',
    description: '',
    status: 'pending',
});

function submit() {
    form.post(route('admin.standard-applications.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Standard Application</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label>Name</label>
                    <input v-model="form.name" required />
                </div>
                <div>
                    <label>Description</label>
                    <textarea v-model="form.description" required />
                </div>
                <div>
                    <label>Status</label>
                    <select v-model="form.status" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </AppLayout>
</template>
