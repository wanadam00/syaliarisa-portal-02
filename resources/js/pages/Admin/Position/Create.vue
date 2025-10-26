<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name -->
                    <div class="flex flex-col space-y-1">
                        <label for="name" class="font-medium">Name<span class="ml-1 text-red-500">*</span></label>
                        <input id="name" v-model="form.name" type="text" placeholder="Enter position name..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <!-- Reports to (parent) -->
                    <div class="flex flex-col space-y-1">
                        <label for="parent_id" class="font-medium">Reports to (parent)</label>
                        <select id="parent_id" v-model="form.parent_id"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500">
                            <option value="">-- None --</option>
                            <option v-for="p in positions" :key="p.id" :value="p.id">{{ p.name }}</option>
                        </select>
                        <span v-if="form.errors.parent_id" class="text-sm text-red-600">
                            {{ form.errors.parent_id }}
                        </span>
                    </div>

                    <!-- Rank -->
                    <div class="flex flex-col space-y-1">
                        <label for="rank" class="font-medium">Rank (smaller = higher)</label>
                        <input id="rank" v-model.number="form.rank" type="number"
                            placeholder="Enter rank (e.g., 1 for highest)"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <span v-if="form.errors.rank" class="text-sm text-red-600">
                            {{ form.errors.rank }}
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

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, usePage } from '@inertiajs/vue3'

const { positions } = usePage().props as unknown as { positions: { id: number, name: string }[] }

const form = useForm({ name: '', parent_id: null, rank: 0, is_visible: true })

function submit() {
    form.post(route('admin.positions.store'))
}
</script>
