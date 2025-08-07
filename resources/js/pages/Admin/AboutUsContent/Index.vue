<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

interface AboutUsContent {
    id: number;
    title: string;
    content: string;
}

const { aboutUsContents } = usePage().props as unknown as { aboutUsContents: AboutUsContent[] };
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">About Us</h1>
            <div class="relative w-full overflow-x-auto">
                <table class="w-full caption-bottom text-sm border rounded-lg">
                    <caption class="text-muted-foreground mt-4 text-sm">About Us Content</caption>
                    <thead class="[&_tr]:border-b">
                        <tr>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Title</th>
                            <th class="text-foreground h-10 px-2 text-left align-middle font-medium whitespace-nowrap">
                                Content</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in aboutUsContents" :key="item.id"
                            class="hover:bg-muted/50 border-b transition-colors">
                            <td class="p-2 align-middle whitespace-nowrap">{{ item.title }}</td>
                            <td class="p-2 align-middle whitespace-nowrap">{{ item.content }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
