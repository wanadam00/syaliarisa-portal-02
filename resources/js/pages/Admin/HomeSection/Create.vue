<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
// No textarea component found, fallback to native
const form = useForm({
    title: '',
    content: '',
    hero_image: '',
    is_visible: true,
    section_type: '',
    order: 0,
});
function submit() {
    form.post(route('admin.home-sections.store'));
}
</script>

<template>
    <AppLayout>
        <div class="p-6 space-y-6">
            <h1 class="text-2xl font-bold">Add Home Section</h1>
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <Label>Type</Label>
                    <Input v-model="form.section_type" required />
                </div>
                <div>
                    <Label>Order</Label>
                    <Input type="number" v-model="form.order" required />
                </div>
                <div>
                    <Label>Title</Label>
                    <Input v-model="form.title" required />
                </div>
                <div>
                    <Label>Content</Label>
                    <textarea v-model="form.content" class="w-full rounded-md border px-3 py-2" />
                </div>
                <div>
                    <Label>Hero Image (URL)</Label>
                    <Input v-model="form.hero_image" />
                </div>
                <div>
                    <Label>
                        <Checkbox v-model="form.is_visible" /> Visible
                    </Label>
                </div>
                <Button type="submit" class="btn btn-primary">Save</Button>
            </form>
        </div>
    </AppLayout>
</template>
