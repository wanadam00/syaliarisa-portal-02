<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

const { aboutUsContent } = usePage().props as any;
const form = useForm({
    title: aboutUsContent.title,
    content: aboutUsContent.content
});
function submit() {
    form.put(route('admin.about-us-contents.update', aboutUsContent.id));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Edit About Us Content</h1>
            <form @submit.prevent="submit" class="space-y-4 max-w-lg">
                <div>
                    <label class="block mb-1 font-medium">Title</label>
                    <input v-model="form.title" required class="w-full border rounded-md p-2" />
                </div>
                <div>
                    <label class="block mb-1 font-medium">Content</label>
                    <textarea v-model="form.content" required class="w-full border rounded-md p-2" rows="5" />
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </AppLayout>
</template>
