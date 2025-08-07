<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

interface ContactInfo {
    id: number;
    type: string;
    value: string;
}

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo[] };
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Contact Info</h1>
            <div class="relative w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm border rounded-lg">
                    <caption class="text-muted-foreground mt-4 text-sm">Contact Information</caption>
                    <thead class="[&_tr]:border-b">
                        <tr>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Type</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="info in contactInfo" :key="info.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.type }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.value }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
