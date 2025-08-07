<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';

interface HomeSection {
    id: number;
    order: number;
    section_type: string;
    title: string;
    content: string | null;
    hero_image: string | null;
    is_visible: boolean;
}

const { section } = usePage().props as unknown as { section: HomeSection };
const form = useForm({
    title: section.title,
    content: section.content,
    hero_image: section.hero_image,
    is_visible: section.is_visible,
    section_type: section.section_type,
    order: section.order,
});
function submit() {
    form.put(route('admin.home-sections.update', section.id));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Edit Home Section</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label>Type</label>
                    <input v-model="form.section_type" required />
                </div>
                <div>
                    <label>Order</label>
                    <input type="number" v-model="form.order" required />
                </div>
                <div>
                    <label>Title</label>
                    <input v-model="form.title" required />
                </div>
                <div>
                    <label>Content</label>
                    <textarea v-model="form.content" />
                </div>
                <div>
                    <label>Hero Image (URL)</label>
                    <input v-model="form.hero_image" />
                </div>
                <div>
                    <label><input type="checkbox" v-model="form.is_visible" /> Visible</label>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </AppLayout>
</template>
