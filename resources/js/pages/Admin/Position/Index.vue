<template>
    <AppLayout>
        <div class="p-6">
            <div class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Positions</h1>
                    <Link :href="route('admin.positions.create')" class="px-4 py-2 bg-blue-600 text-white rounded">New Position</Link>
                </div>

                <div class="space-y-3">
                    <div v-for="pos in positions" :key="pos.id" class="p-3 border rounded flex items-center justify-between">
                        <div>
                            <div class="font-semibold">{{ pos.name }}</div>
                            <div class="text-sm text-gray-500">Reports to: {{ pos.parent ? pos.parent.name : '—' }} | Rank: {{ pos.rank }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <Link :href="`/admin/positions/${pos.id}/edit`" class="text-blue-600">Edit</Link>
                            <form :action="`/admin/positions/${pos.id}`" method="post">
                                <input type="hidden" name="_method" value="delete" />
                                <button type="submit" class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { usePage, Link } from '@inertiajs/vue3'

interface Position {
    id: number
    name: string
    parent?: { id: number, name: string } | null
    rank: number
}

const { positions } = usePage().props as unknown as { positions: Position[] }
</script>
