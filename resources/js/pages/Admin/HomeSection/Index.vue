<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';

interface HomeSection {
    id: number;
    top_details: string;
    top_image: string | null;
    bottom_details: string | null;
    bottom_image: string | null;
    is_visible: boolean;
}

const { sections } = usePage().props as unknown as { sections: HomeSection[] };
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Home Sections Management</h1>
            <Link :href="route('admin.home-sections.create')" class="btn btn-primary mb-4">Add Section</Link>
            <div class="relative w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm border rounded-lg">
                    <caption class="text-muted-foreground mt-4 text-sm">List of Home Sections</caption>
                    <thead class="[&_tr]:border-b">
                        <tr>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Top Details</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Top Background</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Bottom Details</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Bottom Image</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Visible</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="section in sections" :key="section.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ section.top_details }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ section.top_image }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ section.bottom_details }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ section.bottom_image }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ section.is_visible ? 'Yes' : 'No' }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">
                                <Link :href="route('admin.home-sections.edit', section.id)"
                                    class="btn btn-sm btn-secondary">Edit</Link>
                                <form :action="route('admin.home-sections.destroy', section.id)" method="post"
                                    class="inline">
                                    <input type="hidden" name="_method" value="delete" />
                                    <button type="submit" class="btn btn-sm btn-danger ml-2">Delete</button>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
