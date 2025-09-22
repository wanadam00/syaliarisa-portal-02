<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Legislation List</h1>
            <div class="flex justify-end mb-4">
                <a href="/admin/legislations/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Create
                </a>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="legislation in legislations" :key="legislation.id">
                            <td class="px-4 py-2">{{ legislation.id }}</td>
                            <td class="px-4 py-2">{{ legislation.title }}</td>
                            <td class="px-4 py-2">{{ legislation.type }}</td>
                            <td class="px-4 py-2">
                                <span
                                    :class="legislation.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ legislation.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a :href="`/admin/legislations/${legislation.id}/edit`"
                                    class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded mr-2 transition">
                                    Edit
                                </a>

                                <button @click="deleteLegislation(legislation.id)"
                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

interface Legislation {
    id: number;
    title: string;
    type: string;
    is_visible: boolean;
}

function deleteLegislation(id: number) {
    if (confirm('Are you sure you want to delete this legislation?')) {
        router.delete(route('admin.legislations.destroy', id), {
            onSuccess: () => {
                router.visit(route('admin.legislations.index'));
            },
        });
    } else {
        router.visit(route('admin.legislations.index'));
    }
}

const { legislations } = usePage().props as unknown as { legislations: Legislation[] };
</script>
