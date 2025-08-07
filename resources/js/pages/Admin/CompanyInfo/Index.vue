<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router } from '@inertiajs/vue3';

interface CompanyInfo {
    id: number;
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
}

const { companyInfo } = usePage().props as unknown as {
    companyInfo: CompanyInfo[];
};

function handleDelete(id: number) {
    if (confirm('Are you sure you want to delete this company info?')) {
        router.delete(`/admin/company-info/${id}`);
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Company Info List</h1>

            <div class="flex justify-end mb-4">
                <a href="/admin/company-info/create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                    + Create
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Background</th>
                            <th class="px-4 py-3">Vision</th>
                            <th class="px-4 py-3">Mission</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="info in companyInfo" :key="info.id">
                            <td class="px-4 py-2">{{ info.id }}</td>
                            <td class="px-4 py-2 max-w-xs whitespace-pre-wrap">{{ info.background }}</td>
                            <td class="px-4 py-2 max-w-xs whitespace-pre-wrap">{{ info.vision }}</td>
                            <td class="px-4 py-2 max-w-xs whitespace-pre-wrap">{{ info.mission }}</td>
                            <td class="px-4 py-2">
                                <span
                                    :class="info.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ info.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <a :href="`/admin/company-info/${info.id}/edit`"
                                    class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded mr-2 transition">
                                    Edit
                                </a>
                                <button @click="handleDelete(info.id)"
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
