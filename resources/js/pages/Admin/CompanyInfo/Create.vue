<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    background: '',
    vision: '',
    mission: '',
    organization_structure: [] as string[],
    is_visible: true
});

function submit() {
    form.post(route('admin.company-info.store'));
}

function handleStructureChange(e: Event) {
    const target = e.target as HTMLInputElement | null;
    if (target) {
        form.organization_structure = target.value
            .split(',')
            .map((i) => i.trim())
            .filter(Boolean);
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Company Info</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-2xl">

                <!-- Background -->
                <div>
                    <label class="block mb-1 font-medium">Background</label>
                    <textarea v-model="form.background" class="w-full border rounded-md p-2" rows="4" />
                </div>

                <!-- Vision -->
                <div>
                    <label class="block mb-1 font-medium">Vision</label>
                    <textarea v-model="form.vision" class="w-full border rounded-md p-2" rows="3" />
                </div>

                <!-- Mission -->
                <div>
                    <label class="block mb-1 font-medium">Mission</label>
                    <textarea v-model="form.mission" class="w-full border rounded-md p-2" rows="3" />
                </div>

                <!-- Organization Structure (JSON input as string array for now) -->
                <div>
                    <label class="block mb-1 font-medium">Organization Structure (comma separated)</label>
                    <input type="text" :value="form.organization_structure.join(', ')" @change="handleStructureChange"
                        class="w-full border rounded-md p-2" placeholder="e.g. CEO, Manager, Engineer" />

                </div>

                <!-- Is Visible -->
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.is_visible" id="is_visible" />
                    <label for="is_visible" class="font-medium">Visible on Website</label>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </AppLayout>
</template>
