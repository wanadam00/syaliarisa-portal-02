<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css'

interface ServiceImage {
    id: number;
    url: string;
}

interface Service {
    id: number;
    type: string;
    title: string;
    summary: string;
    details: string;
    link: string; // 🆕 video link
    images: ServiceImage[];
    is_visible: boolean;
}

const { service } = usePage().props as unknown as { service: Service };

const form = useForm({
    type: service.type ?? '',
    title: service.title ?? '',
    summary: service.summary ?? '',
    details: service.details ?? '',
    link: service.link ?? '', // 🆕 bind video link
    images: [] as File[],
    deleted_images: [] as number[],
    is_visible: Boolean(service.is_visible ?? true),
});

const previews = ref<string[]>([]);

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    const files = Array.from(target.files || []);
    form.images = files;
    previews.value = files.map(file => URL.createObjectURL(file));
}

function toggleDeleteImage(id: number) {
    if (form.deleted_images.includes(id)) {
        form.deleted_images = form.deleted_images.filter(imgId => imgId !== id);
    } else {
        form.deleted_images.push(id);
    }
}

function submit() {
    form.post(route('admin.services.update', service.id), {
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Service information updated.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to update service information.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Service</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">

                <!-- Type -->
                <div class="flex flex-col space-y-1">
                    <label for="type" class="font-medium">Type of services<span class="text-red-500">*</span></label>
                    <select id="type" v-model="form.type"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                        <option value="" disabled>-- Select Position --</option>
                        <option value="Health & Safety">Health & Safety</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Training">Training</option>
                        <option value="Environmental">Environmental</option>
                    </select>
                    <span v-if="form.errors.type" class="text-sm text-red-600">
                        {{ form.errors.type }}
                    </span>
                </div>

                <!-- Title -->
                <div class="flex flex-col space-y-1">
                    <label for="title" class="font-medium">Title<span class="text-red-500">*</span></label>
                    <input id="title" v-model="form.title" type="text" placeholder="Enter title..."
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.title" class="text-sm text-red-600">
                        {{ form.errors.title }}
                    </span>
                </div>

                <!-- Summary -->
                <div class="flex flex-col space-y-1">
                    <label for="summary" class="font-medium">
                        Summary <span class="text-red-500">*</span>
                    </label>
                    <QuillEditor v-model:content="form.summary" content-type="html" theme="snow" toolbar="full" placeholder="Enter summary..."
                        class="border rounded-md" />
                    <span v-if="form.errors.summary" class="text-sm text-red-600">
                        {{ form.errors.summary }}
                    </span>
                </div>

                <!-- Details -->
                <div class="flex flex-col space-y-1">
                    <label for="details" class="font-medium">
                        Details</label>
                    <QuillEditor v-model:content="form.details" content-type="html" theme="snow" toolbar="full" placeholder="Enter details..."
                        class="border rounded-md" />
                    <span v-if="form.errors.details" class="text-sm text-red-600">
                        {{ form.errors.details }}
                    </span>
                </div>

                <!-- 🆕 Video Link -->
                <div class="flex flex-col space-y-1">
                    <label for="link" class="font-medium">Video Link (YouTube / Google Drive)</label>
                    <input id="link" v-model="form.link" type="url"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"
                        placeholder="https://www.youtube.com/watch?v=..." />
                    <span v-if="form.errors.link" class="text-sm text-red-600">
                        {{ form.errors.link }}
                    </span>

                    <!-- Preview iframe if link is valid -->
                    <div v-if="form.link" class="mt-3">
                        <!-- YouTube -->
                        <iframe v-if="form.link.includes('youtube')"
                            :src="form.link.replace(/(?:https?:\/\/)?(?:www\.)?youtube\.com\/.*[?&]v=([a-zA-Z0-9_-]+).*/, 'https://www.youtube.com/embed/$1')"
                            class="w-full h-64 rounded-md border" frameborder="0" allowfullscreen></iframe>

                        <!-- Vimeo -->
                        <iframe v-else-if="form.link.includes('vimeo')"
                            :src="form.link.replace('vimeo.com', 'player.vimeo.com/video')"
                            class="w-full h-64 rounded-md border" frameborder="0" allowfullscreen></iframe>

                        <!-- Google Drive -->
                        <iframe v-else-if="form.link.includes('drive.google.com')"
                            :src="form.link.replace('/view', '/preview')" class="w-full h-64 rounded-md border"
                            frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>

                <!-- Images -->
                <div class="flex flex-col space-y-1">
                    <label for="images" class="font-medium">Upload Images</label>
                    <input id="images" type="file" accept="image/*" multiple @change="handleFileChange"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />

                    <!-- New previews -->
                    <div v-if="previews.length" class="mt-3 grid grid-cols-3 gap-2">
                        <div v-for="(src, i) in previews" :key="i" class="relative">
                            <img :src="src" alt="New preview" class="h-20 w-20 object-cover rounded-md border" />
                        </div>
                    </div>

                    <!-- Existing images -->
                    <div v-else-if="service.images?.length" class="mt-3 grid grid-cols-3 gap-2">
                        <div v-for="img in service.images" :key="img.id" class="relative">
                            <img :src="img.url" alt="Service image" class="h-20 w-20 object-cover rounded-md border" />

                            <button type="button"
                                class="absolute top-0 right-24 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded"
                                @click="toggleDeleteImage(img.id)">
                                {{ form.deleted_images.includes(img.id) ? 'Undo' : 'X' }}
                            </button>

                            <div v-if="form.deleted_images.includes(img.id)"
                                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-xs rounded-md">
                                To delete
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visible -->
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
