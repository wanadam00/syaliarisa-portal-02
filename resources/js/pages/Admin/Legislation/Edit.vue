<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

interface Legislation {
    id: number;
    title: string;
    description: string;
    type: string;
    details: string;
    image: string | null;
    link: string | null;
    is_visible: boolean;
}

const { legislation } = usePage().props as unknown as { legislation: Legislation };

const form = useForm({
    title: legislation.title,
    description: legislation.description,
    type: legislation.type,
    details: legislation.details,
    image: legislation.image ?? '',
    link: legislation.link ?? '',
    is_visible: legislation.is_visible,
});

function submit() {
    form.put(route('admin.legislations.update', legislation.id));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Legislation</h1>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Title -->
                <div class="flex flex-col space-y-1">
                    <label for="title" class="font-medium">Title</label>
                    <input id="title" v-model="form.title" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <!-- Description -->
                <div class="flex flex-col space-y-1">
                    <label for="description" class="font-medium">Description</label>
                    <textarea id="description" v-model="form.description"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Type -->
                <div class="flex flex-col space-y-1">
                    <label for="type" class="font-medium">Type</label>
                    <input id="type" v-model="form.type" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required />
                </div>

                <!-- Details -->
                <div class="flex flex-col space-y-1">
                    <label for="details" class="font-medium">Details</label>
                    <textarea id="details" v-model="form.details"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Image -->
                <div class="flex flex-col space-y-1">
                    <label for="image" class="font-medium">Image URL</label>
                    <input id="image" v-model="form.image" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Link -->
                <div class="flex flex-col space-y-1">
                    <label for="link" class="font-medium">Link</label>
                    <input id="link" v-model="form.link" type="url"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>

                <!-- Visible Checkbox -->
                <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox" class="h-4 w-4 border rounded" />
                    <label for="is_visible" class="font-medium">Visible</label>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
