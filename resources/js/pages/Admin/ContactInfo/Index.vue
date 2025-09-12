<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

interface ContactInfo {
    id: number;
    address: string;
    phone: string;
    email: string;
    map_embed: Text | null;
    business_hours: string | null;
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
                                Address</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Phone Number</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Email</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Location</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Business Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="info in contactInfo" :key="info.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.address }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.phone }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.email }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.map_embed }}
                                <!-- <iframe v-if="info.map_embed" :src="info.map_embed" width="100%" height="200"
                                    style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
                            </td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ info.business_hours }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
