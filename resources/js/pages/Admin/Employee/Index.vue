<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage, router, Link } from '@inertiajs/vue3';
import { reactive, onMounted, onBeforeUnmount } from 'vue';
import Swal from 'sweetalert2';
import { Plus } from 'lucide-vue-next';

interface Employee {
    id: number;
    name: string;
    department: string;
    position: string;
    photo: string | null;
    is_visible: boolean;
    showMenu?: boolean; // added for dropdown state
}

// function deleteEmployee(id: number) {
//     if (confirm('Are you sure you want to delete this employee?')) {
//         router.delete(route('admin.employees.destroy', id), {
//             onSuccess: () => {
//                 router.visit(route('admin.employees.index'));
//             },
//         });
//     }
// }

function deleteEmployee(id: number) {
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
            router.delete(route('admin.employees.destroy', id), {
                onSuccess: () => {
                    Swal.fire('Deleted!', 'The employee has been deleted.', 'success')
                    router.visit(route('admin.employees.index'))
                },
            })
        }
    })
}

const { employees } = usePage().props as unknown as { employees: Employee[] };
employees.forEach(e => (e.showMenu = false));

const dropdownPosition = reactive({ top: 0, left: 0 });
let activeEmployee: Employee | null = null;

function toggleMenu(e: MouseEvent, employee: Employee) {
    if (activeEmployee && activeEmployee !== employee) {
        activeEmployee.showMenu = false; // close previous
    }
    employee.showMenu = !employee.showMenu;
    activeEmployee = employee.showMenu ? employee : null;

    if (employee.showMenu) {
        const rect = (e.currentTarget as HTMLElement).getBoundingClientRect();
        dropdownPosition.top = rect.bottom + window.scrollY;
        dropdownPosition.left = rect.right - 128 + window.scrollX; // 128px = dropdown width (w-32)
    }
}

// close when clicking outside
function handleClickOutside(event: MouseEvent) {
    if (activeEmployee && activeEmployee.showMenu) {
        const menuEl = document.getElementById(`dropdown-${activeEmployee.id}`);
        const btnEl = document.getElementById(`btn-${activeEmployee.id}`);

        if (
            menuEl &&
            !menuEl.contains(event.target as Node) &&
            btnEl &&
            !btnEl.contains(event.target as Node)
        ) {
            activeEmployee.showMenu = false;
            activeEmployee = null;
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
                            Organization Chart Management
                        </h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage and organize employee structure
                        </p>
                    </div>
                    <Link :href="route('admin.employees.create')"
                        class="group inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-105">
                    <Plus class="size-5 mr-0 sm:mr-2 transition-transform group-hover:rotate-90 duration-300" />
                    <span class="hidden sm:inline">Add Employee</span>
                    </Link>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-x-auto rounded-2xl border border-gray-200/50 dark:border-gray-700/50 bg-white/70 dark:bg-gray-900/60 backdrop-blur-xl shadow-lg">
                <table class="min-w-full text-sm text-left">
                    <thead
                        class="bg-gradient-to-r from-gray-100/70 to-gray-200/50 dark:from-gray-800/70 dark:to-gray-700/50 text-gray-700 dark:text-gray-200 uppercase tracking-wide text-xs">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Department</th>
                            <th class="px-4 py-3">Position</th>
                            <th class="px-4 py-3 text-center">Photo</th>
                            <th class="px-4 py-3 text-center">Visible</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 dark:text-gray-100 divide-y divide-gray-200/30 dark:divide-gray-700/30">
                        <tr v-for="(employee, index) in employees" :key="employee.id"
                            class="hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-2">{{ index + 1 }}</td>
                            <td class="px-4 py-2 w-64 truncate">{{ employee.name }}</td>
                            <td class="px-4 py-2 truncate">{{ employee.department }}</td>
                            <td class="px-4 py-2 truncate">{{ employee.position }}</td>
                            <td class="px-4 py-2 text-center">
                                <img v-if="employee.photo" :src="employee.photo" alt="Photo"
                                    class="h-10 w-10 rounded-full object-cover mx-auto" />
                                <span v-else class="italic text-gray-400">No photo</span>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <span
                                    :class="employee.is_visible ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ employee.is_visible ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-center relative">
                                <!-- Vertical Dots -->
                                <button :id="`btn-${employee.id}`" @click="toggleMenu($event, employee)"
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
                                    <div v-if="employee.showMenu" :id="`dropdown-${employee.id}`"
                                        class="absolute w-40 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg z-50"
                                        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }">
                                        <a :href="`/admin/employees/${employee.id}/edit`"
                                            class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Edit
                                        </a>
                                        <button @click="deleteEmployee(employee.id)"
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
