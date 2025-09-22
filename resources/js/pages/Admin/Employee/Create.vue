<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    position: '',
    photo: '',
    is_visible: true
});

function submit() {
    form.post(route('admin.employees.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Add Employee</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Name -->
                <div class="flex flex-col space-y-1">
                    <label for="name" class="font-medium">Name</label>
                    <input id="name" v-model="form.name" type="text" name="name"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <!-- Position -->
                <div class="flex flex-col space-y-1">
                    <label for="position" class="font-medium">Position</label>
                    <input id="position" v-model="form.position" type="text" name="position"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <!-- Photo -->
                <div class="flex flex-col space-y-1">
                    <label for="photo" class="font-medium">Photo URL</label>
                    <input id="photo" v-model="form.photo" type="text" name="photo"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Visible Checkbox -->
                <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox" name="is_visible"
                        class="h-4 w-4 border rounded" />
                    <label for="is_visible" class="font-medium">Visible</label>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
