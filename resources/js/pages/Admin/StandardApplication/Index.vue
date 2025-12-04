<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { ChevronRight, ChevronLeft, Plus } from 'lucide-vue-next';

interface StandardApplication {
    id: number;
    title: string;
    description: string;
    logo: string | null;
    link: string | null;
    is_visible: boolean;
    showMenu?: boolean; // for dropdown state
}

// function deleteStandard(id: number) {
//     if (confirm('Are you sure you want to delete this standard application?')) {
//         router.delete(route('admin.standard-applications.destroy', id), {
//             onSuccess: () => {
//                 router.visit(route('admin.standard-applications.index'));
//             },
//         });
//     }
// }

const formatQuillContent = (html: string) => {
    if (!html) return "";
    return html
        .replace(/<ol>/g, '<ol style="list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<ul>/g, '<ul style="list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<p><br><\/p>/g, '');
};

function deleteStandard(id: number) {
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
            router.delete(route('admin.standard-applications.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The standard application has been deleted.', 'success')
                    router.visit(route('admin.standard-applications.index'))
                },
            })
        }
    })
}

const { standardApplications } = usePage().props as unknown as {
    standardApplications: {
        data: StandardApplication[];
        links: { url: string | null; label: string; active: boolean }[];
        from: number;
        to: number;
        total: number;
    };
};

// dropdown position state
const dropdownPosition = reactive({ top: 0, left: 0 });
standardApplications.data.forEach(app => (app.showMenu = false));
function toggleMenu(event: MouseEvent, item: StandardApplication) {
    item.showMenu = !item.showMenu;

    const btn = event.currentTarget as HTMLElement;
    const rect = btn.getBoundingClientRect();

    dropdownPosition.top = rect.bottom + window.scrollY;
    dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
}

function closeAllMenus() {
    standardApplications.data.forEach(app => (app.showMenu = false));
}

onMounted(() => {
    document.addEventListener('click', closeAllMenus);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', closeAllMenus);
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
                            Standard Applications Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create and manage standard applications
                        </p>
                    </div>
                    <Link :href="route('admin.standard-applications.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">New Application</span>
                    </Link>
                </div>
            </div>

            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-200/70 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3 w-16">No.</th>
                            <th class="px-4 py-3 w-48">Title</th>
                            <th class="px-4 py-3 w-64">Description</th>
                            <th class="px-4 py-3 w-24">Logo</th>
                            <th class="px-4 py-3 w-48">Link</th>
                            <th class="px-4 py-3 w-20">Visible</th>
                            <th class="px-4 py-3 w-24 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(standard, index) in standardApplications.data" :key="standard.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-2 w-16">{{ index + 1 }}</td>

                            <!-- Title with proper truncation -->
                            <td class="px-4 py-2 w-48 max-w-[12rem]">
                                <div class="truncate" :title="standard.title">
                                    {{ standard.title }}
                                </div>
                            </td>

                            <!-- Description with better text handling -->
                            <td class="px-4 py-2 w-64 max-w-[16rem]">
                                <div class="line-clamp-1 text-ellipsis overflow-hidden" :title="standard.description" v-html="formatQuillContent(standard.description)">

                                </div>
                            </td>

                            <td class="px-4 py-2 w-24">
                                <img v-if="standard.logo" :src="standard.logo" :alt="standard.title"
                                    class="h-10 w-10 rounded object-cover" />
                                <span v-else class="italic text-gray-400 text-sm">No logo</span>
                            </td>

                            <!-- Link with better truncation -->
                            <td class="px-4 py-2 w-48 max-w-[12rem]">
                                <a v-if="standard.link" :href="standard.link" target="_blank"
                                    class="text-blue-600 hover:underline line-clamp-1 text-ellipsis overflow-hidden"
                                    :title="standard.link">
                                    {{ standard.link }}
                                </a>
                                <span v-else class="italic text-gray-400 text-sm">No link</span>
                            </td>

                            <td class="px-4 py-2 w-20">
                                <span
                                    :class="standard.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ standard.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>

                            <td class="px-4 py-2 w-24 text-center relative">
                                <!-- Your existing dropdown button code -->
                                <button :id="`btn-${standard.id}`" @click.stop="toggleMenu($event, standard)"
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
                                    <div v-if="standard.showMenu" :id="`dropdown-${standard.id}`"
                                        class="absolute w-32 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/standard-applications/${standard.id}/edit`"
                                            class="block px-4 py-2 text-left text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteStandard(standard.id)"
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
            <!-- Pagination -->
            <div class="py-6 flex justify-center space-x-2">
                <template v-for="(link, i) in standardApplications.links" :key="i">
                    <button v-if="link.url" @click="router.visit(link.url)" :class="[
                        'px-3 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center',
                        link.active
                            ? 'bg-blue-600 text-white shadow-lg'
                            : 'bg-white/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-300 hover:bg-gray-100/70 dark:hover:bg-gray-700/70 backdrop-blur'
                    ]">
                        <ChevronLeft v-if="i === 0" />
                        <ChevronRight v-else-if="i === standardApplications.links.length - 1" />
                        <span v-else v-html="link.label"></span>
                    </button>
                    <span v-else class="px-3 py-2 rounded-lg text-sm text-gray-400 flex items-center justify-center">
                        <ChevronLeft v-if="i === 0" />
                        <ChevronRight v-else-if="i === standardApplications.links.length - 1" />
                        <span v-else v-html="link.label"></span>
                    </span>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
