<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

interface Employee {
    id: number;
    name: string;
    position: string;
    photo: string | null;
    is_visible: boolean;
}

function deleteEmployee(id: number) {
    if (confirm('Are you sure you want to delete this employee?')) {
        router.delete(`/admin/employees/${id}`);
    }
}


const { employees } = usePage().props as unknown as { employees: Employee[] };
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Employee List</h1>
            <div class="flex justify-end mb-4">
                <a href="/admin/employees/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Create
                </a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Position</th>
                            <th class="px-4 py-3">Photo</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="employee in employees" :key="employee.id">
                            <td class="px-4 py-2">{{ employee.id }}</td>
                            <td class="px-4 py-2">{{ employee.name }}</td>
                            <td class="px-4 py-2">{{ employee.position }}</td>
                            <td class="px-4 py-2">
                                <img v-if="employee.photo" :src="employee.photo" alt="Photo"
                                    class="h-10 w-10 rounded-full object-cover" />
                                <span v-else class="italic text-gray-400">No photo</span>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    :class="employee.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ employee.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a :href="`/admin/employees/${employee.id}/edit`"
                                    class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded mr-2 transition">
                                    Edit
                                </a>
                                <form :action="`/admin/employees/${employee.id}`" method="POST" class="inline">
                                    <input type="hidden" name="_method" value="DELETE" />
                                    <button @click="deleteEmployee(employee.id)"
                                        class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
