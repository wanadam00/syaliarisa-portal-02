<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';
import Swal from 'sweetalert2';

interface HomeSection {
    id: number;
    title: string;
    top_details: string;
    top_image: string | null;
    bottom_details: string;
    bottom_image: string | null;
    // is_visible: boolean;
}

const { homeSection, flash } = usePage().props as unknown as {
    homeSection: HomeSection;
    flash?: { success?: string; error?: string };
};

const form = useForm({
    title: homeSection.title ?? '',
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

// ✅ Watch for Laravel flash messages (optional but useful)
watchEffect(() => {
    if (flash?.success) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: flash.success,
            confirmButtonColor: '#3085d6',
        });
    }

    if (flash?.error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: flash.error,
            confirmButtonColor: '#d33',
        });
    }
});

// ✅ Form submission with better feedback
function submit() {
    form.post(route('admin.home-sections.store'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Saved Successfully!',
                text: 'Home section has been updated or created.',
                confirmButtonColor: '#3085d6',
            });
        },
        onError: (errors) => {
            // Laravel validation or business rule errors
            const firstError = Object.values(errors)[0] as string;
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: firstError || 'Unable to add detail to the home section.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Add Home Section
                        </h1>
                    </div>
                </div>
            </div> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Title -->
                    <div class="flex flex-col space-y-1">
                        <label for="title" class="font-medium">Title<span class="ml-1 text-red-500">*</span></label>
                        <input id="title" v-model="form.title" type="text" placeholder="Enter title..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.title" class="text-sm text-red-600">
                            {{ form.errors.title }}
                        </span>
                    </div>

                    <!-- Top Details -->
                    <div class="flex flex-col space-y-1">
                        <label for="top_details" class="font-medium">Top Details<span
                                class="ml-1 text-red-500">*</span></label>
                        <textarea id="top_details" v-model="form.top_details" type="text" rows="3"
                            placeholder="Enter top details..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.top_details" class="text-sm text-red-600">
                            {{ form.errors.top_details }}
                        </span>
                    </div>

                    <!-- Top Background Upload -->
                    <div class="flex flex-col space-y-1">
                        <label for="top_image" class="font-medium">Top Background<span
                                class="ml-1 text-red-500">*</span></label>
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
                        <label for="department" class="font-medium">Bottom Details<span
                                class="ml-1 text-red-500">*</span></label>
                        <textarea id="department" v-model="form.bottom_details" type="text" rows="3"
                            placeholder="Enter bottom details..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.bottom_details" class="text-sm text-red-600">
                            {{ form.errors.bottom_details }}
                        </span>
                    </div>

                    <!-- Bottom Image Upload -->
                    <div class="flex flex-col space-y-1">
                        <label for="bottom_image" class="font-medium">Bottom Image<span
                                class="ml-1 text-red-500">*</span></label>
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
                    <!-- <div class="flex items-center space-x-3">
                    <label for="is_visible" class="font-medium text-gray-700 dark:text-gray-300">Visible</label>
                    <button type="button" @click="form.is_visible = !form.is_visible" :class="[
                        'relative inline-flex h-6 w-11 items-center rounded-full transition',
                        form.is_visible ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'
                    ]">
                        <span :class="[
                            'inline-block h-4 w-4 transform rounded-full bg-white transition',
                            form.is_visible ? 'translate-x-6' : 'translate-x-1'
                        ]" />
                    </button>
                </div> -->

                    <!-- Submit -->
                    <div class="inline-flex justify-end w-full">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                            :disabled="form.processing">
                            <span v-if="form.processing">Saving...</span>
                            <span v-else>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
