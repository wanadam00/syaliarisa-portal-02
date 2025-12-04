<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import Swal from 'sweetalert2';

interface StandardApplication {
    id: number;
    title: string;
    description: string;
    logo: string | null;
    link: string;
    is_visible: boolean;
}

const { standardApplication } = usePage().props as unknown as { standardApplication: StandardApplication };

const form = useForm({
    title: standardApplication.title ?? '',
    description: standardApplication.description ?? '',
    logo: null as File | null,
    link: standardApplication.link ?? '',
    is_visible: Boolean(standardApplication.is_visible ?? true),
});

const customToolbar = [
    ['bold', 'italic', 'underline'],
    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
    [{ 'align': '' }, { 'align': 'center' }, { 'align': 'right' }, { 'align': 'justify' }],
    ['link'],
    ['clean']
];

// 🖼️ preview state
const preview = ref<string | null>(null);

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.logo = file;

    if (file) {
        preview.value = URL.createObjectURL(file); // Show preview
    } else {
        preview.value = null; // Reset if no file
    }
}

function submit() {
    form.post(route('admin.standard-applications.update', standardApplication.id), {
        forceFormData: true, // 🔑 ensures file uploads
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Standard application updated.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to update standard aplication.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <h1 class="text-2xl font-bold mb-6">Edit Standard Applications</h1> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">


                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Title -->
                    <div class="flex flex-col space-y-1">
                        <label for="title" class="font-medium">Title<span class="ml-1 text-red-500">*</span></label>
                        <input id="title" v-model="form.title" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.title" class="text-sm text-red-600">
                            {{ form.errors.title }}
                        </span>
                    </div>
                    <!-- Description -->
                    <div class="flex flex-col space-y-1">
                        <label for="description" class="font-medium">Description<span
                                class="ml-1 text-red-500">*</span></label>
                        <QuillEditor v-model:content="form.description" content-type="html" theme="snow" :toolbar="customToolbar"
                            placeholder="Enter description..." class="border rounded-md" />
                        <!-- <textarea id="description" v-model="form.description" rows="3"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                            required></textarea> -->
                        <span v-if="form.errors.description" class="text-sm text-red-600">
                            {{ form.errors.description }}
                        </span>
                    </div>
                    <!-- Link -->
                    <div class="flex flex-col space-y-1">
                        <label for="link" class="font-medium">Link</label>
                        <input id="link" v-model="form.link" type="url" placeholder="https://example.com"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <span v-if="form.errors.link" class="text-sm text-red-600">
                            {{ form.errors.link }}
                        </span>
                    </div>

                    <!-- Logo Upload -->
                    <div class="flex flex-col space-y-1">
                        <label for="logo" class="font-medium">Logo</label>
                        <input id="logo" type="file" accept="image/*" @change="handleFileChange"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <div v-if="form.errors.logo" class="text-sm text-red-600">
                            {{ form.errors.logo }}
                        </div>

                        <!-- Show preview if new logo uploaded -->
                        <div v-if="preview" class="mt-2">
                            <p class="text-sm text-gray-500">New logo preview:</p>
                            <img :src="preview" alt="New preview" class="h-20 rounded-md border" />
                        </div>

                        <!-- Otherwise show old logo -->
                        <div v-else-if="standardApplication.logo" class="mt-2">
                            <p class="text-sm text-gray-500">Current logo:</p>
                            <img :src="`/storage/${standardApplication.logo}`" alt="Standard logo"
                                class="h-20 rounded-md border" />
                        </div>
                    </div>

                    <!-- Visible Checkbox -->
                    <div class="flex items-center space-x-3">
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
                    </div>

                    <!-- Submit -->
                    <div class="inline-flex justify-end w-full">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                            :disabled="form.processing">
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
