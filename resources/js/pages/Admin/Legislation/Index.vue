<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { Plus } from 'lucide-vue-next';

interface Legislation {
    id: number;
    title: string;
    type: string;
    is_visible: boolean;
    showMenu?: boolean; // added for dropdown state
    display_mode?: string;
}

// function deleteLegislation(id: number) {
//     if (confirm('Are you sure you want to delete this legislation?')) {
//         router.delete(route('admin.legislations.destroy', id), {
//             onSuccess: () => {
//                 router.visit(route('admin.legislations.index'));
//             },
//         });
//     } else {
//         router.visit(route('admin.legislations.index'));
//     }
// }

function deleteLegislation(id: number) {
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
            router.delete(route('admin.legislations.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The legislation has been deleted.', 'success')
                    router.visit(route('admin.legislations.index'))
                },
            })
        }
    })
}

const { legislations } = usePage().props as unknown as { legislations: Legislation[] };
legislations.forEach(e => (e.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeLegislation: Legislation | null = null;

function toggleMenu(e: MouseEvent, legislation: Legislation) {
    if (activeLegislation && activeLegislation !== legislation) {
        activeLegislation.showMenu = false; // close previous
    }
    legislation.showMenu = !legislation.showMenu;
    activeLegislation = legislation.showMenu ? legislation : null;

    if (legislation.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeLegislation && activeLegislation.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeLegislation.id}`);
        const btnEl = document.getElementById(`btn-${activeLegislation.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeLegislation.showMenu = false;
            activeLegislation = null;
        }
    }
}

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});

</script>

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
                            Legislation Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create and manage legislations</p>
                    </div>
                    <Link :href="route('admin.legislations.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">New Legislation</span>
                    </Link>
                </div>
            </div>

            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-200/70 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">Display</th>
                            <th class="px-4 py-3">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(legislation, index) in legislations" :key="legislation.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2">{{ legislation.title }}</td>
                            <td class="px-4 py-2">{{ legislation.type }}</td>
                            <td class="px-4 py-2">{{ legislation.display_mode ?? 'group' }}</td>
                            <td class="px-4 py-2">
                                <span
                                    :class="legislation.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ legislation.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dot Button -->
                                <button :id="`btn-${legislation.id}`" @click="toggleMenu($event, legislation)"
                                    class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>

                                <!-- Dropdown rendered outside -->
                                <Teleport to="body">
                                    <div v-if="legislation.showMenu" :id="`dropdown-${legislation.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/legislations/${legislation.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteLegislation(legislation.id)"
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
