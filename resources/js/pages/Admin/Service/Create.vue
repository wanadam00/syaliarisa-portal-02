<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
    images: ServiceImage[];
    is_visible: boolean;
}

const { service } = usePage().props as unknown as { service: Service };

const form = useForm({
    type: service.type ?? '',
    title: service.title ?? '',
    summary: service.summary ?? '',
    details: service.details ?? '',
    images: [] as File[], // allow multiple uploads
    deleted_images: [] as number[], // 🆕 track deleted image IDs
    is_visible: Boolean(service.is_visible ?? true),
});

// 🖼️ preview state for multiple new files
const previews = ref<string[]>([]);

function handleFileChange(e: Event) {
    const target = e.target as HTMLInputElement;
    const files = Array.from(target.files || []);
    form.images = files;

    // Preview for multiple
    previews.value = files.map(file => URL.createObjectURL(file));
}

// 🆕 mark existing image for deletion
function toggleDeleteImage(id: number) {
    if (form.deleted_images.includes(id)) {
        form.deleted_images = form.deleted_images.filter(imgId => imgId !== id);
    } else {
        form.deleted_images.push(id);
    }
}

function submit() {
    form.post(route('admin.services.store'), {
        forceFormData: true,
        onSuccess: () => {
            previews.value = [];
            form.deleted_images = []; // reset deletions
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Add Service</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Position -->
                <div class="flex flex-col space-y-1">
                    <label for="type" class="font-medium">Type of services</label>
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
                    <label for="title" class="font-medium">Title</label>
                    <input id="title" v-model="form.title" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.title" class="text-sm text-red-600">
                        {{ form.errors.title }}
                    </span>
                </div>

                <!-- Summary -->
                <div class="flex flex-col space-y-1">
                    <label for="summary" class="font-medium">Summary</label>
                    <textarea id="summary" v-model="form.summary" type="text" rows="5"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.summary" class="text-sm text-red-600">
                        {{ form.errors.summary }}
                    </span>
                </div>

                <!-- Details -->
                <div class="flex flex-col space-y-1">
                    <label for="details" class="font-medium">Details</label>
                    <textarea id="details" v-model="form.details" type="text" rows="5"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.details" class="text-sm text-red-600">
                        {{ form.errors.details }}
                    </span>
                </div>

                <!-- Image Upload -->
                <div class="flex flex-col space-y-1">
                    <label for="images" class="font-medium">Upload Images</label>
                    <input id="images" type="file" accept="image/*" multiple @change="handleFileChange"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />

                    <!-- Show new previews -->
                    <div v-if="previews.length" class="mt-3 grid grid-cols-3 gap-2">
                        <div v-for="(src, i) in previews" :key="i" class="relative">
                            <img :src="src" alt="New preview" class="h-20 w-20 object-cover rounded-md border" />
                        </div>
                    </div>

                    <!-- Show existing service images -->
                    <div v-else-if="service.images?.length" class="mt-3 grid grid-cols-3 gap-2">
                        <div v-for="img in service.images" :key="img.id" class="relative">
                            <img :src="img.url" alt="Service image" class="h-20 w-20 object-cover rounded-md border" />

                            <!-- Delete button -->
                            <button type="button"
                                class="absolute top-1 right-24 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded"
                                @click="toggleDeleteImage(img.id)">
                                {{ form.deleted_images.includes(img.id) ? 'Undo' : 'X' }}
                            </button>

                            <!-- Mark deleted visually -->
                            <div v-if="form.deleted_images.includes(img.id)"
                                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center text-white text-xs rounded-md">
                                To delete
                            </div>
                        </div>
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
                        <span v-if="form.processing">Submiting...</span>
                        <span v-else>Submit</span>
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
