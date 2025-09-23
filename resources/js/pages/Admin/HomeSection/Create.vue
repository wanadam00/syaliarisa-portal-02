<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface HomeSection {
    id: number;
    top_details: string;
    top_image: string | null;
    bottom_details: string;
    bottom_image: string | null;
    // is_visible: boolean;
}

const { homeSection } = usePage().props as unknown as { homeSection: HomeSection };

const form = useForm({
    top_details: homeSection.top_details ?? '',
    top_image: null as File | null,
    bottom_details: homeSection.bottom_details ?? '',
    bottom_image: null as File | null,
    // is_visible: homeSection.is_visible,
});

// 🖼️ preview state
const topPreview = ref<string | null>(null);
const bottomPreview = ref<string | null>(null);

function handleFileChangeTop(e: Event) {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.top_image = file;

    if (file) {
        topPreview.value = URL.createObjectURL(file); // Show preview
    } else {
        topPreview.value = null; // Reset if no file
    }
}

function handleFileChangeBottom(e: Event) {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.bottom_image = file;

    if (file) {
        bottomPreview.value = URL.createObjectURL(file); // Show preview
    } else {
        bottomPreview.value = null; // Reset if no file
    }
}

function submit() {
    form.post(route('admin.home-sections.store'), {
        forceFormData: true, // 🔑 ensures file uploads
        onSuccess: () => {
            topPreview.value = null; // reset preview after successful update
            bottomPreview.value = null; // reset preview after successful update
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Top Deatils -->
                <div class="flex flex-col space-y-1">
                    <label for="name" class="font-medium">Top Details</label>
                    <input id="name" v-model="form.top_details" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.top_details" class="text-sm text-red-600">
                        {{ form.errors.top_details }}
                    </span>
                </div>

                <!-- Top Background Upload -->
                <div class="flex flex-col space-y-1">
                    <label for="top_image" class="font-medium">Top Background</label>
                    <input id="top_image" type="file" accept="image/*" @change="handleFileChangeTop"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                    <div v-if="form.errors.top_image" class="text-sm text-red-600">
                        {{ form.errors.top_image }}
                    </div>

                    <!-- Show preview if new background uploaded -->
                    <div v-if="topPreview" class="mt-2">
                        <p class="text-sm text-gray-500">New background preview:</p>
                        <img :src="topPreview" alt="New preview" class="h-20 rounded-md border" />
                    </div>

                    <!-- Otherwise show old background -->
                    <div v-else-if="homeSection.top_image" class="mt-2">
                        <p class="text-sm text-gray-500">Current background:</p>
                        <img :src="`/storage/${homeSection.top_image}`" alt="Top Background"
                            class="h-20 rounded-md border" />
                    </div>
                </div>

                <!-- Bottom Details -->
                <div class="flex flex-col space-y-1">
                    <label for="department" class="font-medium">Bottom Details</label>
                    <input id="department" v-model="form.bottom_details" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.bottom_details" class="text-sm text-red-600">
                        {{ form.errors.bottom_details }}
                    </span>
                </div>

                <!-- Bottom Image Upload -->
                <div class="flex flex-col space-y-1">
                    <label for="bottom_image" class="font-medium">Bottom Image</label>
                    <input id="bottom_image" type="file" accept="image/*" @change="handleFileChangeBottom"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                    <div v-if="form.errors.bottom_image" class="text-sm text-red-600">
                        {{ form.errors.bottom_image }}
                    </div>
                    <!-- Show preview if new image uploaded -->
                    <div v-if="bottomPreview" class="mt-2">
                        <p class="text-sm text-gray-500">New image preview:</p>
                        <img :src="bottomPreview" alt="New preview" class="h-20 rounded-md border" />
                    </div>
                    <!-- Otherwise show old image -->
                    <div v-else-if="homeSection.bottom_image" class="mt-2">
                        <p class="text-sm text-gray-500">Current image:</p>
                        <img :src="`/storage/${homeSection.bottom_image}`" alt="Bottom Image"
                            class="h-20 rounded-md border" />
                    </div>
                </div>

                <!-- Visible Checkbox -->
                <!-- <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox" class="h-4 w-4 border rounded" />
                    <label for="is_visible" class="font-medium">Visible</label>
                </div> -->

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                        :disabled="form.processing">
                        <span v-if="form.processing">Submiting...</span>
                        <span v-else>Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
