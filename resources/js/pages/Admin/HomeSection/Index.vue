<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage, router } from '@inertiajs/vue3';

interface HomeSection {
    id: number;
    top_details: string;
    top_image: string | null;
    bottom_details: string | null;
    bottom_image: string | null;
    is_visible: boolean;
}

function deleteSection(id: number) {
    if (confirm('Are you sure you want to delete this section?')) {
        router.delete(route('admin.home-sections.destroy', id));
    }
}

const { sections } = usePage().props as unknown as { sections: HomeSection[] };
</script>

<template>
    <AppLayout>
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Home Sections Management</h1>

            <!-- Create Button -->
            <div class="flex justify-end mb-4">
                <Link :href="route('admin.home-sections.create')"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
                + Add Section
                </Link>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-3">Top Details</th>
                            <th class="px-4 py-3">Top Background</th>
                            <th class="px-4 py-3">Bottom Details</th>
                            <th class="px-4 py-3">Bottom Image</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="section in sections" :key="section.id">
                            <td class="px-4 py-2 max-w-[200px] truncate">{{ section.top_details }}</td>
                            <td class="px-4 py-2">
                                <img v-if="section.top_image" :src="section.top_image" alt="Top Image"
                                    class="h-10 w-16 rounded object-cover" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <td class="px-4 py-2 max-w-[200px] truncate">{{ section.bottom_details }}</td>
                            <td class="px-4 py-2">
                                <img v-if="section.bottom_image" :src="section.bottom_image" alt="Bottom Image"
                                    class="h-10 w-16 rounded object-cover" />
                                <span v-else class="italic text-gray-400">No image</span>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    :class="section.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ section.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <Link :href="route('admin.home-sections.edit', section.id)"
                                    class="inline-flex items-center px-3 py-1 bg-yellow-500 hover:bg-yellow-600 text-white rounded mr-2 transition">
                                Edit
                                </Link>
                                <button @click="deleteSection(section.id)" type="button"
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
