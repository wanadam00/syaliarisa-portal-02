<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
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
}

const { legislation } = usePage().props as unknown as { legislation: Legislation };

const form = useForm({
    title: legislation.title,
    description: legislation.description,
    type: legislation.type,
    details: legislation.details,
    image: null as File | null,
    link: legislation.link ?? '',
    is_visible: Boolean(legislation.is_visible ?? true),
});

// 🖼️ preview state
const preview = ref<string | null>(null);

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
    form.post(route('admin.legislations.update', legislation.id), {
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Legislation information updated.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to update legislation information.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Legislation</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

                <!-- Type -->
                <div class="flex flex-col space-y-1">
                    <label for="type" class="font-medium">Type of legislation<span class="text-red-500">*</span></label>
                    <select id="type" v-model="form.type"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled>-- Select Type --</option>
                        <option value="OSHA 1994">OSHA 1994</option>
                        <option value="Factories & Machinery Act 1967 (FMA 1967)">Factories & Machinery Act 1967 (FMA
                            1967)</option>
                        <option value="Environmental Quality Act 1974">Environmental Quality Act 1974</option>
                    </select>
                    <span v-if="form.errors.type" class="text-sm text-red-600">
                        {{ form.errors.type }}
                    </span>
                </div>

                <!-- Details -->
                <div class="flex flex-col space-y-1">
                    <label for="details" class="font-medium">Details</label>
                    <textarea id="details" v-model="form.details" rows="5"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    <span v-if="form.errors.details" class="text-sm text-red-600">
                        {{ form.errors.details }}
                    </span>
                </div>

                <!-- Title -->
                <div class="flex flex-col space-y-1">
                    <label for="title" class="font-medium">Title<span class="text-red-500">*</span></label>
                    <input id="title" v-model="form.title" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.title" class="text-sm text-red-600">
                        {{ form.errors.title }}
                    </span>
                </div>

                <!-- Description -->
                <div class="flex flex-col space-y-1">
                    <label for="description" class="font-medium">Description<span class="text-red-500">*</span></label>
                    <textarea id="description" v-model="form.description" rows="5"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
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
                <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox"
                        class="h-4 w-4 border rounded text-blue-600" />
                    <label for="is_visible" class="font-medium">Visible</label>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                        :disabled="form.processing">
                        <span v-if="form.processing">Updating...</span>
                        <span v-else>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
