<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import Swal from 'sweetalert2';

interface Legislation {
    id: number;
    title: string;
    description: string;
    type: string;
    details: string;
    image: string | null;
    link: string | null;
    is_visible: boolean;
    display_mode?: 'group' | 'individual';
}

const { legislation, types } = usePage().props as unknown as { legislation: Legislation, types: string[] };

const form = useForm({
    title: legislation.title,
    description: legislation.description,
    type: legislation.type,
    details: legislation.details,
    image: null as File | null,
    link: legislation.link ?? '',
    is_visible: Boolean(legislation.is_visible ?? true),
    display_mode: legislation.display_mode ?? 'group',
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
// new category input
const newCategory = ref('');

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0] || null;
    form.image = file;

    if (file) {
        preview.value = URL.createObjectURL(file);
    } else {
        preview.value = null;
    }
}

function submit() {
    // If admin chose to add a new category, use that value as the type
    if (form.type === '__add_new__') {
        form.type = newCategory.value || '';
    }
    form.post(route('admin.legislations.update', legislation.id), {
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Guidelines & References information updated.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to update guidelines & references information.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <h1 class="text-2xl font-bold mb-6">Edit Legislation</h1> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">


                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

                    <!-- Type -->
                    <!-- <div class="flex flex-col space-y-1">
                        <label for="type" class="font-medium">Type of legislation<span
                                class="ml-1 text-red-500">*</span></label>
                        <select id="type" v-model="form.type"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled>-- Select Type --</option>
                            <option v-for="(t, idx) in types" :key="idx" :value="t">{{ t }}</option>
                            <option value="__add_new__">+ Add New Category</option>
                        </select>
                        <div v-if="form.type === '__add_new__'" class="mt-2">
                            <input v-model="newCategory" type="text" placeholder="Type new category"
                                class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                            <p class="text-sm text-gray-500 mt-1">Enter a new category name and it will be used as the
                                type.</p>
                        </div>
                        <span v-if="form.errors.type" class="text-sm text-red-600">
                            {{ form.errors.type }}
                        </span>
                    </div> -->

                    <!-- Details -->
                    <!-- <div class="flex flex-col space-y-1">
                        <label for="details" class="font-medium">Details</label>
                        <QuillEditor v-model:content="form.details" content-type="html" theme="snow"
                            :toolbar="customToolbar" placeholder="e.g., Section 15, Subsection 3"
                            class="border rounded-md" />
                        <span v-if="form.errors.details" class="text-sm text-red-600">
                            {{ form.errors.details }}
                        </span>
                    </div> -->

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
                        <QuillEditor v-model:content="form.description" content-type="html" theme="snow"
                            :toolbar="customToolbar" placeholder="Provide a brief description of the legislation"
                            class="border rounded-md" />
                        <!-- <textarea id="description" v-model="form.description" rows="5"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"></textarea> -->
                        <span v-if="form.errors.description" class="text-sm text-red-600">
                            {{ form.errors.description }}
                        </span>
                    </div>

                    <!-- Type -->
                    <!-- <div class="flex flex-col space-y-1">
                    <label for="type" class="font-medium">Type</label>
                    <input id="type" v-model="form.type" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                    <span v-if="form.errors.type" class="text-sm text-red-600">
                        {{ form.errors.type }}
                    </span>
                </div> -->

                    <!-- Link -->
                    <div class="flex flex-col space-y-1">
                        <label for="link" class="font-medium">Link</label>
                        <input id="link" v-model="form.link" type="url"
                            placeholder="https://www.example.com/legislation"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <span v-if="form.errors.link" class="text-sm text-red-600">
                            {{ form.errors.link }}
                        </span>
                    </div>

                    <!-- Image Upload -->
                    <div class="flex flex-col space-y-1">
                        <label for="image" class="font-medium">Image</label>
                        <input id="image" type="file" accept="image/*" @change="handleFileChange"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <div v-if="form.errors.image" class="text-sm text-red-600">
                            {{ form.errors.image }}
                        </div>

                        <!-- Show preview if new image uploaded -->
                        <div v-if="preview" class="mt-2">
                            <p class="text-sm text-gray-500">New image preview:</p>
                            <img :src="preview" alt="New preview" class="h-20 rounded-md border object-cover" />
                        </div>

                        <!-- Otherwise show old image -->
                        <div v-else-if="legislation.image" class="mt-2">
                            <p class="text-sm text-gray-500">Current image:</p>
                            <img :src="`/storage/${legislation.image}`" alt="Legislation image"
                                class="h-20 rounded-md border object-cover" />
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

                    <!-- Display Mode -->
                    <!-- <div class="flex flex-col space-y-1">
                        <label for="display_mode" class="font-medium">Display Mode</label>
                        <select id="display_mode" v-model="form.display_mode"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="group">Grouped (default)</option>
                            <option value="individual">Individual (separate card)</option>
                        </select>
                        <p class="text-sm text-gray-500">Choose whether this legislation appears in a grouped card by
                            type or as its own card.</p>
                    </div> -->

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
