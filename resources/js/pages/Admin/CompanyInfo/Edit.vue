<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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
        }
    });
}
</script>

<template>
    <AppLayout>
        <div class="p-6 max-w-2xl">
            <h1 class="text-2xl font-bold mb-6">Edit Company Info</h1>

            <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
                <!-- Background -->
                <div class="flex flex-col space-y-1">
                    <label for="background" class="font-medium">Company Background</label>
                    <textarea id="text" v-model="form.background" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.background" class="text-sm text-red-600">
                        {{ form.errors.background }}
                    </span>
                </div>

                <!-- Vision -->
                <div class="flex flex-col space-y-1">
                    <label for="vision" class="font-medium">Vision</label>
                    <textarea id="vision" v-model="form.vision" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.vision" class="text-sm text-red-600">
                        {{ form.errors.vision }}
                    </span>
                </div>

                <!-- mission -->
                <div class="flex flex-col space-y-1">
                    <label for="mission" class="font-medium">Mission</label>
                    <textarea id="mission" v-model="form.mission" type="text"
                        class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                    <span v-if="form.errors.mission" class="text-sm text-red-600">
                        {{ form.errors.mission }}
                    </span>
                </div>

                <!-- Visible Checkbox -->
                <div class="flex items-center space-x-2">
                    <input id="is_visible" v-model="form.is_visible" type="checkbox" class="h-4 w-4 border rounded" />
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
