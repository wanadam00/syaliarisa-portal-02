<template>
    <AppLayout>
        <div class="p-6">
            <div class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="flex flex-col space-y-1">
                        <label class="font-medium">Name</label>
                        <input v-model="form.name" type="text" class="w-full px-3 py-2 border rounded" required />
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label class="font-medium">Reports to (parent)</label>
                        <select v-model="form.parent_id" class="w-full px-3 py-2 border rounded">
                            <option value="">-- None --</option>
                            <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label class="font-medium">Rank (smaller = higher)</label>
                        <input v-model.number="form.rank" type="number" class="w-full px-3 py-2 border rounded" />
                    </div>

                    <div class="flex items-center space-x-3">
                        <label class="font-medium">Visible</label>
                        <input type="checkbox" v-model="form.is_visible" />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

const { positions } = usePage().props as unknown as { positions: { id:number, name:string }[] }

const form = useForm({ name: '', parent_id: null, rank: 0, is_visible: true })

function submit() {
    form.post(route('admin.positions.store'))
}
</script>