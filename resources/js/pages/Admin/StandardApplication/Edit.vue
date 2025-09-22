<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const { standardApplication } = usePage().props as unknown as { standardApplication: { id: number; name: string; description: string; status: string } };

const form = useForm({
    name: standardApplication.name,
    description: standardApplication.description,
    status: standardApplication.status,
});

function submit() {
    form.put(route('admin.standard-applications.update', standardApplication.id));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Edit Standard Application</h1>
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
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </AppLayout>
</template>
