<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import Swal from 'sweetalert2';

interface ContactInfo {
    id: number;
    address: string;
    phone: string;
    email: string;
    mobile_phone_1: string | null;
    mobile_phone_2: string | null;
    business_hours: string | null;
    is_visible: boolean;
}

const { contactInfo } = usePage().props as unknown as { contactInfo: ContactInfo };

const form = useForm({
    address: contactInfo.address ?? '',
    phone: contactInfo.phone ?? '',
    email: contactInfo.email ?? '',
    mobile_phone_1: contactInfo.mobile_phone_1 ?? '',
    mobile_phone_2: contactInfo.mobile_phone_2 ?? '',
    business_hours: contactInfo.business_hours ?? '',
    is_visible: Boolean(contactInfo.is_visible ?? true),
});

const quillEditor = ref<HTMLDivElement | null>(null);
let quill: Quill | null = null;

onMounted(() => {
    if (quillEditor.value) {
        quill = new Quill(quillEditor.value, {
            theme: 'snow',
            placeholder: 'Enter business hours (e.g., Monday - Friday: 8:00 AM - 6:00 PM)',
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['clean']
                ]
            }
        });

        // Set initial value or default template
        const initialContent = form.business_hours;
        quill.root.innerHTML = initialContent;

        // Sync with form
        quill.on('text-change', () => {
            form.business_hours = quill?.root.innerHTML || '';
        });

        // Add format business hours button to toolbar
        const toolbar = quill.getModule('toolbar');
        toolbar.addHandler('clean', function () {
            formatBusinessHours();
        });
    }
});

function formatBusinessHours() {
    if (!quill) return;

    const text = quill.getText();
    const lines = text.split('\n').filter(line => line.trim());

    const formattedLines = lines.map(line => {
        // Simple formatting - you can customize this logic
        if (line.includes(':')) {
            const parts = line.split(':');
            if (parts.length >= 2) {
                const day = parts[0].trim();
                const time = parts.slice(1).join(':').trim();
                return `<strong>${day}:</strong> ${time}`;
            }
        }
        return line;
    });

    quill.root.innerHTML = formattedLines.join('<br>');
    form.business_hours = quill.root.innerHTML;
}

function submit() {
    form.post(route('admin.contact-info.store'), {
        forceFormData: true,
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'New detail added to the contact us.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to add detail to the contact us.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <h1 class="text-2xl font-bold mb-6">Add Contact Info</h1> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">

                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Address -->
                    <div class="flex flex-col space-y-1">
                        <label for="address" class="font-medium">Address<span class="text-red-500">*</span></label>
                        <input id="address" v-model="form.address" type="text" placeholder="Enter address..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.address" class="text-sm text-red-600">
                            {{ form.errors.address }}
                        </span>
                    </div>

                    <!-- Phone -->
                    <div class="flex flex-col space-y-1">
                        <label for="phone" class="font-medium">Office Phone<span class="text-red-500">*</span></label>
                        <input id="phone" v-model="form.phone" type="text" placeholder="Enter office phone..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.phone" class="text-sm text-red-600">
                            {{ form.errors.phone }}
                        </span>
                    </div>

                    <!-- Mobile Phone 1 -->
                    <div class="flex flex-col space-y-1">
                        <label for="mobile_phone_1" class="font-medium">Mobile Phone 1</label>
                        <input id="mobile_phone_1" v-model="form.mobile_phone_1" type="text"
                            placeholder="Enter mobile phone 1..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.mobile_phone_1" class="text-sm text-red-600">
                            {{ form.errors.mobile_phone_1 }}
                        </span>
                    </div>

                    <!-- Mobile Phone 2 -->
                    <div class="flex flex-col space-y-1">
                        <label for="mobile_phone_2" class="font-medium">Mobile Phone 2</label>
                        <input id="mobile_phone_2" v-model="form.mobile_phone_2" type="text"
                            placeholder="Enter mobile phone 2..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <span v-if="form.errors.mobile_phone_2" class="text-sm text-red-600">
                            {{ form.errors.mobile_phone_2 }}
                        </span>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col space-y-1">
                        <label for="email" class="font-medium">Email<span class="text-red-500">*</span></label>
                        <input id="email" v-model="form.email" type="email" placeholder="Enter email..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
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
                        <label for="business_hours" class="font-medium">Business Hours<span
                                class="text-red-500">*</span></label>
                        <div class="mb-2">
                            <button type="button" @click="formatBusinessHours"
                                class="text-sm bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded border">
                                Format Business Hours
                            </button>
                            <span class="text-xs text-gray-500 ml-2">Click to auto-format days and times</span>
                        </div>
                        <div ref="quillEditor" class="h-40 border rounded-md"></div>
                        <div class="text-xs text-gray-500 mt-1">
                            Format example: <strong>Monday - Friday:</strong> 8:00 AM - 6:00 PM
                        </div>
                        <span v-if="form.errors.business_hours" class="text-sm text-red-600">
                            {{ form.errors.business_hours }}
                        </span>
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
                            <span v-if="form.processing">Saving...</span>
                            <span v-else>Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
