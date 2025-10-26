<template>
    <AppLayout>
        <div class="p-6">
            <!-- Header -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Positions Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage and organize position structure
                        </p>
                    </div>
                    <Link :href="route('admin.positions.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">New Position</span>
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead
                        class="bg-gray-200/70 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Reports to</th>
                            <th class="px-4 py-3">Rank</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(pos, index) in positions" :key="pos.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 w-64 truncate">{{ pos.name }}</td>
                            <td class="px-4 py-2 truncate">{{ pos.parent ? pos.parent.name : '—' }}</td>
                            <td class="px-4 py-2 truncate">{{ pos.rank }}</td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dots -->
                                <button :id="`btn-${pos.id}`" @click="toggleMenu($event, pos)"
                                    class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <Teleport to="body">
                                    <div v-if="pos.showMenu" :id="`dropdown-${pos.id}`"
                                        class="absolute w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/positions/${pos.id}/edit`"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deletePosition(pos.id)"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-gray-700">
                                            Delete
                                        </button>
                                    </div>
                                </Teleport>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { Plus } from 'lucide-vue-next';

interface Position {
    id: number
    name: string
    parent?: { id: number, name: string } | null
    rank: number
    showMenu?: boolean; // added for dropdown state
}

const { positions } = usePage().props as unknown as { positions: Position[] }
positions.forEach(p => (p.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activePosition: Position | null = null;

function toggleMenu(e: MouseEvent, position: Position) {
    if (activePosition && activePosition !== position) {
        activePosition.showMenu = false; // close previous
    }
    position.showMenu = !position.showMenu;
    activePosition = position.showMenu ? position : null;

    if (position.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activePosition && activePosition.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activePosition.id}`);
        const btnEl = document.getElementById(`btn-${activePosition.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activePosition.showMenu = false;
            activePosition = null;
        }
    }
}

function deletePosition(id: number) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.positions.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The position has been deleted.', 'success')
                    router.visit(route('admin.positions.index'))
                },
            })
        }
    })
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>
