<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';

interface ContactInfo {
    id: number;
    address: string;
    phone: string;
    email: string;
    business_hours: string | null;
    is_visible: boolean;
}

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo };

const form = useForm({
    address: contactInfo.address ?? '',
    phone: contactInfo.phone ?? '',
    email: contactInfo.email ?? '',
    business_hours: contactInfo.business_hours ?? '',
    is_visible: Boolean(contactInfo.is_visible ?? true),
});

const quillEditor = ref<HTMLDivElement | null>(null);
let quill: Quill | null = null;

onMounted(() => {
    if (quillEditor.value) {
        quill = new Quill(quillEditor.value, {
            theme: 'snow',
            placeholder: 'Enter business hours...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }], // 🔑 add indent/outdent
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Set initial value
        quill.root.innerHTML = form.business_hours || '';

        // Sync with form
        quill.on('text-change', () => {
            form.business_hours = quill?.root.innerHTML || '';
        });
    }
});


function submit() {
    form.post(route('admin.contact-info.store'), {
        forceFormData: true,
        onSuccess: () => {
            // Optional success handling
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Add Contact Info</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Address -->
                <div class="flex flex-col space-y-1">
                    <label for="address" class="font-medium">Address</label>
                    <input id="address" v-model="form.address" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.address" class="text-sm text-red-600">
                        {{ form.errors.address }}
                    </span>
                </div>

                <!-- Phone -->
                <div class="flex flex-col space-y-1">
                    <label for="phone" class="font-medium">Phone</label>
                    <input id="phone" v-model="form.phone" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.phone" class="text-sm text-red-600">
                        {{ form.errors.phone }}
                    </span>
                </div>

                <!-- Email -->
                <div class="flex flex-col space-y-1">
                    <label for="email" class="font-medium">Email</label>
                    <input id="email" v-model="form.email" type="email"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.email" class="text-sm text-red-600">
                        {{ form.errors.email }}
                    </span>
                </div>

                <!-- Map Embed -->
                <!-- <div class="flex flex-col space-y-1">
                    <label for="map_embed" class="font-medium">Map Embed (HTML)</label>
                    <textarea id="map_embed" v-model="form.map_embed" rows="4"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500"></textarea>
                    <span v-if="form.errors.map_embed" class="text-sm text-red-600">
                        {{ form.errors.map_embed }}
                    </span>
                </div> -->

                <!-- Business Hours with Quill -->
                <div class="flex flex-col space-y-1">
                    <label for="business_hours" class="font-medium">Business Hours</label>
                    <div ref="quillEditor" class="h-40 border rounded-md"></div>
                    <span v-if="form.errors.business_hours" class="text-sm text-red-600">
                        {{ form.errors.business_hours }}
                    </span>
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
