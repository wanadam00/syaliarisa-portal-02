<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

interface Employee {
    id: number;
    name: string;
    position: string;
    department: string;
    photo: string | null;
    is_visible: boolean;
}

const { employee } = usePage().props as unknown as { employee: Employee };

const form = useForm({
    name: employee.name ?? '',
    position: employee.position ?? '',
    department: employee.department ?? '',
    photo: null as File | null,
    is_visible: employee.is_visible,
});

function submit() {
    form.post(route('admin.employees.update', employee.id), {
        forceFormData: true, // 🔑 ensures file uploads
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Name -->
                <div class="flex flex-col space-y-1">
                    <label for="name" class="font-medium">Name</label>
                    <input id="name" v-model="form.name" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.name" class="text-sm text-red-600">
                        {{ form.errors.name }}
                    </span>
                </div>

                <!-- Department -->
                <div class="flex flex-col space-y-1">
                    <label for="department" class="font-medium">Deparment</label>
                    <input id="department" v-model="form.department" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.department" class="text-sm text-red-600">
                        {{ form.errors.department }}
                    </span>
                </div>

                <!-- Position -->
                <div class="flex flex-col space-y-1">
                    <label for="position" class="font-medium">Position</label>
                    <input id="position" v-model="form.position" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.position" class="text-sm text-red-600">
                        {{ form.errors.position }}
                    </span>
                </div>

                <!-- Photo Upload -->
                <div class="flex flex-col space-y-1">
                    <label for="photo" class="font-medium">Photo</label>
                    <input id="photo" type="file" accept="image/*"
                        @change="e => form.photo = (e.target as HTMLInputElement).files?.[0] || null" />
                    <div v-if="form.errors.photo" class="text-sm text-red-600">
                        {{ form.errors.photo }}
                    </div>
                    <div v-if="employee.photo" class="mt-2">
                        <img :src="`/storage/${employee.photo}`" alt="Employee photo" class="h-20 rounded-md border" />
                    </div>
                </div>

                <!-- Visible Checkbox -->
                <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox" class="h-4 w-4 border rounded" />
                    <label for="is_visible" class="font-medium">Visible</label>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                        :disabled="form.processing">
                        <span v-if="form.processing">Updating...</span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
