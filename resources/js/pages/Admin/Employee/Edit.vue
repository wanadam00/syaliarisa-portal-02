<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

interface Employee {
    id: number;
    name: string;
    position: string;
    photo: string | null;
    is_visible: boolean;
}

const { employee } = usePage().props as unknown as { employee: Employee };
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>
            <form method="POST" :action="`/admin/employees/${employee.id}`">
                <input type="hidden" name="_method" value="PUT" />
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg" :value="employee.name"
                        required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Position</label>
                    <input type="text" name="position" class="w-full px-3 py-2 border rounded-lg"
                        :value="employee.position" required />
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Photo URL</label>
                    <input type="text" name="photo" class="w-full px-3 py-2 border rounded-lg"
                        :value="employee.photo ?? ''" />
                </div>
                <div class="mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_visible" class="mr-2" :checked="employee.is_visible" />
                        Visible
                    </label>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold">Update</button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
