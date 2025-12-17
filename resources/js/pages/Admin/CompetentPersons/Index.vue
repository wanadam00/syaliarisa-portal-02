<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { Plus, ChevronRight, ChevronLeft } from 'lucide-vue-next';

interface CompetentPerson {
    id: number;
    name: string;
    dosh_numbers: string | null;
    competencies: string | null;
    is_active: boolean;
    employee?: { id: number; name: string };
    showMenu?: boolean;
}

// interface Props {
//     competentPersons: CompetentPerson[];
// }

// const props = withDefaults(defineProps<Props>(), {});
const { competentPersons } = usePage().props as unknown as {
    competentPersons: {
        data: CompetentPerson[];
        links: { url: string | null; label: string; active: boolean }[];
        from: number;
        to: number;
        total: number;
    };
};
competentPersons.data.forEach(e => (e.showMenu = false));

// competentPersons.forEach(person => {
//     person.showMenu = false; // removed in favor of map
// });

const dropdownPosition = reactive({ top: 0, left: 0 });
let activePersonId: number | null = null;
const showMenuMap: Record<number, boolean> = reactive({});

function toggleMenu(e: MouseEvent, person: CompetentPerson) {
    // initialize map entry
    if (!Object.prototype.hasOwnProperty.call(showMenuMap, person.id)) showMenuMap[person.id] = false;

    // close any other
    if (activePersonId && activePersonId !== person.id) {
        showMenuMap[activePersonId] = false;
    }

    showMenuMap[person.id] = !showMenuMap[person.id];
    activePersonId = showMenuMap[person.id] ? person.id : null;

    if (showMenuMap[person.id]) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 160 + window.scrollX; // align dropdown
    }
}

function handleClickOutside(event: MouseEvent) {
    if (activePersonId) {
        const menuEl = document.getElementById(`dropdown-${activePersonId}`);
        const btnEl = document.getElementById(`btn-${activePersonId}`);
        if (menuEl && !menuEl.contains(event.target as Node) && btnEl && !btnEl.contains(event.target as Node)) {
            showMenuMap[activePersonId] = false;
            activePersonId = null;
        }
    }
}

function deleteCompetentPerson(id: number) {
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
            router.delete(route('admin.competent-persons.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The competent person has been deleted.', 'success');
                    router.visit(route('admin.competent-persons.index'));
                },
            });
        }
    });
}

function parseDoshNumbers(dosh: string | null): string[] {
    if (!dosh) return [];
    try {
        return JSON.parse(dosh);
    } catch {
        return dosh.split(',').map(d => d.trim()).filter(d => d);
    }
}

function parseCompetencies(comp: string | null): string[] {
    if (!comp) return [];
    try {
        return JSON.parse(comp);
    } catch {
        return comp.split(',').map(c => c.trim()).filter(c => c);
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

    <Head title="Competent Persons" />
    <AppLayout>
        <div class="p-6">
            <!-- <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Competent Persons</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage competent persons for the
                            company</p>
                    </div>
                    <Link :href="route('admin.competent-persons.create')"
                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg transition">
                        <i class="bi bi-plus-circle mr-2"></i> Add Competent Person
                    </Link>
                </div>
            </div> -->
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            Competent Persons
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage competent persons for the
                            company</p>
                    </div>
                    <Link :href="route('admin.competent-persons.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                        <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                        <span class="hidden sm:inline">New Competent Person</span>
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-200/70 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Name
                            </th>
                            <th class="px-4 py-3">DOSH
                                Number</th>
                            <th class="px-4 py-3">
                                Competencies</th>
                            <th class="px-4 py-3">Status
                            </th>
                            <th class="px-4 py-3 text-center">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(person, index) in competentPersons.data" :key="person.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 w-64 truncate">
                                {{ person.employee ? person.employee.name : person.name }}
                            </td>
                            <td class="px-4 py-2 truncate">
                                <!-- <div v-if="person.dosh_numbers"
                                    class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded block max-w-xs truncate">
                                    {{ person.dosh_numbers ? (typeof person.dosh_numbers === 'string' ?
                                        person.dosh_numbers : JSON.stringify(person.dosh_numbers)) : '—' }}
                                </div>
                                <span v-else class="text-gray-400">—</span> -->
                                <div v-if="person.dosh_numbers" class="flex flex-wrap gap-1">
                                    <div v-for="(dosh, idx) in parseDoshNumbers(person.dosh_numbers)" :key="idx"
                                        class="inline-block bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs px-2 py-1 rounded">
                                        {{ dosh }}
                                    </div>
                                </div>
                                <span v-else class="text-gray-400">—</span>
                            </td>
                            <td class="px-4 py-2 truncate">
                                <div v-if="person.competencies" class="flex flex-wrap gap-1">
                                    <div v-for="(comp, idx) in parseCompetencies(person.competencies)" :key="idx"
                                        class="inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">
                                        {{ comp }}
                                    </div>
                                </div>
                                <span v-else class="text-gray-400">None</span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span
                                    :class="person.is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ person.is_active ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center relative">
                                <button :id="`btn-${person.id}`" @click="toggleMenu($event, person)"
                                    class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="1" />
                                        <circle cx="12" cy="5" r="1" />
                                        <circle cx="12" cy="19" r="1" />
                                    </svg>
                                </button>

                                <Teleport to="body">
                                    <div v-if="showMenuMap[person.id]" :id="`dropdown-${person.id}`"
                                        class="absolute w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="route('admin.competent-persons.edit', person.id)"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                        <button @click="deleteCompetentPerson(person.id)"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-gray-700">Delete</button>
                                    </div>
                                </Teleport>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="py-6 flex justify-center space-x-2">
                <template v-for="(link, i) in competentPersons.links" :key="i">
                    <button v-if="link.url" @click="router.visit(link.url)" :class="[
                        'px-3 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center',
                        link.active
                            ? 'bg-blue-600 text-white shadow-lg'
                            : 'bg-white/70 dark:bg-gray-800/70 text-gray-600 dark:text-gray-300 hover:bg-gray-100/70 dark:hover:bg-gray-700/70 backdrop-blur'
                    ]">
                        <ChevronLeft v-if="i === 0" />
                        <ChevronRight v-else-if="i === competentPersons.links.length - 1" />
                        <span v-else v-html="link.label"></span>
                    </button>
                    <span v-else class="px-3 py-2 rounded-lg text-sm text-gray-400 flex items-center justify-center">
                        <ChevronLeft v-if="i === 0" />
                        <ChevronRight v-else-if="i === competentPersons.links.length - 1" />
                        <span v-else v-html="link.label"></span>
                    </span>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
