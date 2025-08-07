<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    background: '',
    vision: '',
    mission: '',
    is_visible: true
});

function submit() {
    form.post(route('admin.company-info.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Company Info</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-2xl">

                <!-- Background -->
                <div>
                    <label class="block mb-1 font-medium">Background</label>
                    <textarea v-model="form.background" class="w-full border rounded-md p-2" rows="4" />
                </div>

                <!-- Vision -->
                <div>
                    <label class="block mb-1 font-medium">Vision</label>
                    <textarea v-model="form.vision" class="w-full border rounded-md p-2" rows="3" />
                </div>

                <!-- Mission -->
                <div>
                    <label class="block mb-1 font-medium">Mission</label>
                    <textarea v-model="form.mission" class="w-full border rounded-md p-2" rows="3" />
                </div>

                <!-- Is Visible -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.is_visible" id="is_visible" />
                    <label for="is_visible" class="font-medium">Visible on Website</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </AppLayout>
</template>
