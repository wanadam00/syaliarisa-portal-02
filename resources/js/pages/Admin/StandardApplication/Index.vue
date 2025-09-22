<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

interface StandardApplication {
    id: number;
    name: string;
    description: string;
    status: string;
}

const { standardApplications } = usePage().props as unknown as { standardApplications: StandardApplication[] };
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Standard Applications Management</h1>
            <Link :href="route('admin.standard-applications.create')" class="btn btn-primary mb-4">Add Standard
            Application
            </Link>
            <div class="relative w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm border rounded-lg">
                    <caption class="text-muted-foreground mt-4 text-sm">List of Standard Applications</caption>
                    <thead class="[&_tr]:border-b">
                        <tr>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Name
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Description
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Status
                            </th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="application in standardApplications" :key="application.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ application.name }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ application.description }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ application.status }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">
                                <Link :href="route('admin.standard-applications.edit', application.id)"
                                    class="btn btn-sm btn-secondary">
                                Edit
                                </Link>
                                <form :action="route('admin.standard-applications.destroy', application.id)"
                                    method="post" class="inline">
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
