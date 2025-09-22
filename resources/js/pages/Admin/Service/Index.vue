<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; // Corrected import for the route helper

interface Service {
    id: number;
    type: string;
    title: string;
    summary: string;
    details: string;
    image: string | null;
    is_visible: boolean;
}

const { services } = usePage().props as unknown as { services: Service[] };
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Services Management</h1>
            <Link :href="route('admin.services.create')" class="btn btn-primary mb-4">Add Service</Link>
            <div class="relative w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm border rounded-lg">
                    <caption class="text-muted-foreground mt-4 text-sm">List of Services</caption>
                    <thead class="[&_tr]:border-b">
                        <tr>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Type
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Title
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Visible
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="service in services" :key="service.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ service.type }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ service.title }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ service.is_visible ? 'Yes' : 'No' }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">
                                <Link :href="route('admin.services.edit', service.id)" class="btn btn-sm btn-secondary">
                                Edit
                                </Link>
                                <form :action="route('admin.services.destroy', service.id)" method="post"
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
