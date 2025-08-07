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
        <div class="p-6 max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Add Employee</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-2xl">
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Name</label>
                    <input v-model="form.name" type="text" name="name" class="w-full px-3 py-2 border rounded-lg" required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Position</label>
                    <input v-model="form.position" type="text" name="position" class="w-full px-3 py-2 border rounded-lg" required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Photo URL</label>
                    <input v-model="form.photo" type="text" name="photo" class="w-full px-3 py-2 border rounded-lg" />
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input v-model="form.is_visible" type="checkbox" name="is_visible" class="mr-2" checked />
                        Visible
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Save</button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
