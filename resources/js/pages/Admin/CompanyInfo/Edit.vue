<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

interface CompanyInfo {
    id: number;
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
}

const { companyInfo } = usePage().props as unknown as { companyInfo: CompanyInfo };

const form = useForm({
    background: companyInfo.background ?? '',
    vision: companyInfo.vision ?? '',
    mission: companyInfo.mission ?? '',
    is_visible: Boolean(companyInfo.is_visible ?? true),
});

function submit() {
    form.post(route('admin.company-info.update', companyInfo.id), {
        forceFormData: true, // 🔑 ensures file uploads
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Company information updated.',
                confirmButtonColor: '#3085d6',
            });
            form.reset();
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                title: 'Failed!',
                text: 'Unable to update company information.',
                confirmButtonColor: '#d33',
            });
        },
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <h1 class="text-2xl font-bold mb-6">Edit Company Info</h1> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">

                <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                    <!-- Background -->
                    <div class="flex flex-col space-y-1">
                        <label for="background" class="font-medium">Company Background<span
                                class="text-red-500">*</span></label>
                        <textarea id="text" v-model="form.background" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.background" class="text-sm text-red-600">
                            {{ form.errors.background }}
                        </span>
                    </div>

                    <!-- Vision -->
                    <div class="flex flex-col space-y-1">
                        <label for="vision" class="font-medium">Vision<span class="text-red-500">*</span></label>
                        <textarea id="vision" v-model="form.vision" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.vision" class="text-sm text-red-600">
                            {{ form.errors.vision }}
                        </span>
                    </div>

                    <!-- mission -->
                    <div class="flex flex-col space-y-1">
                        <label for="mission" class="font-medium">Mission<span class="text-red-500">*</span></label>
                        <textarea id="mission" v-model="form.mission" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.mission" class="text-sm text-red-600">
                            {{ form.errors.mission }}
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
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
