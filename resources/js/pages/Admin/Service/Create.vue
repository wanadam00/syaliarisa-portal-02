<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; // Importing the route helper

interface Service {
    id: number;
    type: string;
    title: string;
    summary: string;
    details: string;
    image: string | null;
    is_visible: boolean;
}

const form = useForm({
    type: '',
    title: '',
    summary: '',
    details: '',
    image: '',
    is_visible: true,
});
function submit() {
    form.post(route('admin.services.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Service</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label>Type</label>
                    <select v-model="form.type" required>
                        <option value="">Select type</option>
                        <option value="health_safety">Health & Safety</option>
                        <option value="training">Training</option>
                        <option value="engineering">Engineering</option>
                        <option value="environmental">Environmental</option>
                    </select>
                </div>
                <div>
                    <label>Title</label>
                    <input v-model="form.title" required />
                </div>
                <div>
                    <label>Summary</label>
                    <textarea v-model="form.summary" required />
                </div>
                <div>
                    <label>Details</label>
                    <textarea v-model="form.details" required />
                </div>
                <div>
                    <label>Image (URL)</label>
                    <input v-model="form.image" />
                </div>
                <div>
                    <label><input type="checkbox" v-model="form.is_visible" /> Visible</label>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </AppLayout>
</template>
