<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { ref, watchEffect, computed } from 'vue';
import '@vueup/vue-quill/dist/vue-quill.snow.css'
import Swal from 'sweetalert2';
import { Plus } from 'lucide-vue-next';

interface CompetentPerson {
    id: number;
    name: string;
    dosh_numbers: string | null;
    competencies: string | null;
    is_active: boolean;
    employee_id?: string;
}

interface Employee { id: number; name: string; }

interface Props {
    competentPerson?: CompetentPerson | null;
    employees: Employee[];
}

const props = withDefaults(defineProps<Props>(), {
    competentPerson: null,
});

const isEditing = computed(() => !!props.competentPerson);
const pageTitle = computed(() => isEditing.value ? 'Edit Competent Person' : 'Add Competent Person');

// Initialize form with competent person data or empty values
const form = useForm({
    name: props.competentPerson?.name || '',
    dosh_numbers: props.competentPerson?.dosh_numbers || '',
    competencies: props.competentPerson?.competencies || '',
    employee_id: props.competentPerson?.employee_id || '',
    is_active: props.competentPerson?.is_active !== false,
});

// Parse competencies from string to array for UI
const competenciesArray = ref<string[]>(
    props.competentPerson?.competencies
        ? props.competentPerson.competencies.split(',').map(c => c.trim()).filter(c => c)
        : []
);

const doshArray = ref<string[]>(
    props.competentPerson?.dosh_numbers
        ? props.competentPerson.dosh_numbers.split(',').map(d => d.trim()).filter(d => d)
        : []
);

const newCompetency = ref('');
const newDosh = ref('');

function addCompetency() {
    if (newCompetency.value.trim()) {
        competenciesArray.value.push(newCompetency.value.trim());
        updateCompetenciesField();
        newCompetency.value = '';
    }
}

function removeCompetency(index: number) {
    competenciesArray.value.splice(index, 1);
    updateCompetenciesField();
}

function updateCompetenciesField() {
    form.competencies = competenciesArray.value.join(', ');
}

function addDosh() {
    if (newDosh.value.trim()) {
        doshArray.value.push(newDosh.value.trim());
        updateDoshField();
        newDosh.value = '';
    }
}

function removeDosh(index: number) {
    doshArray.value.splice(index, 1);
    updateDoshField();
}

function updateDoshField() {
    form.dosh_numbers = doshArray.value.join(', ');
}

function submitForm() {
    const submitAction = isEditing.value
        ? () => form.put(route('admin.competent-persons.update', props.competentPerson!.id), {
            onSuccess: () => {
                Swal.fire('Updated!', 'The competent person has been updated.', 'success');
                form.reset();
                router.visit(route('admin.competent-persons.index'));
            },
            onError: () => {
                Swal.fire('Error!', 'There was an issue updating the competent person.', 'error');
            }
        })
        : () => form.post(route('admin.competent-persons.store'), {
            onSuccess: () => {
                Swal.fire('Created!', 'The competent person has been created.', 'success');
                form.reset();
                router.visit(route('admin.competent-persons.index'));
            },
            onError: () => {
                Swal.fire('Error!', 'There was an issue creating the competent person.', 'error');
            }
        });

    submitAction();
}
</script>

<template>

    <Head :title="pageTitle" />
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <!-- <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <div class="flex sm:items-center justify-between gap-4">
                    <div class="truncate">
                        <Link :href="route('admin.competent-persons.index')"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center mb-2">
                        <i class="bi bi-arrow-left mr-2"></i> Back to List
                        </Link>
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                            {{ pageTitle }}</h1>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Create or edit competent person records
                            and certifications</p>
                    </div>
                </div>
            </div> -->

            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
                    <!-- Name -->
                    <!-- <div class="flex flex-col space-y-1">
                        <label for="name" class="font-medium">Name<span class="ml-1 text-red-500">*</span></label>
                        <input id="name" v-model="form.name" type="text"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </span>
                    </div> -->

                    <!-- Employee -->
                    <div class="flex flex-col space-y-2">
                        <label for="employee" class="font-medium">Employee<span
                                class="ml-1 text-red-500">*</span></label>
                        <select v-model.number="form.employee_id"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required>
                            <option value="" disabled>-- Select Employee --</option>
                            <option v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</option>
                        </select>
                        <p v-if="form.errors.employee_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.employee_id }}
                        </p>
                    </div>

                    <!-- DOSH Number -->
                    <div class="flex flex-col space-y-1">
                        <!-- <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="bi bi-file-text mr-2"></i> DOSH Numbers (one or more)
                        </label> -->
                        <label for="dosh" class="font-medium">DOSH Numbers<span
                                class="ml-1 text-red-500">*</span></label>
                        <div class="flex gap-2 mb-3">
                            <input v-model="newDosh" type="text" placeholder="Add a DOSH number (e.g., DOSH/2024/001)"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                @keyup.enter="addDosh" />
                            <button type="button" @click="addDosh"
                                class="group px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                <Plus class="size-5 transition-transform group-hover:rotate-90 duration-300" />
                            </button>
                        </div>

                        <div v-if="doshArray.length > 0" class="flex flex-wrap gap-2 mb-3">
                            <div v-for="(d, idx) in doshArray" :key="idx"
                                class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm">
                                {{ d }}
                                <button type="button" @click="removeDosh(idx)"
                                    class="hover:text-red-600 dark:hover:text-red-400 transition">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <p v-if="form.errors.dosh_numbers" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.dosh_numbers }}
                        </p>
                    </div>

                    <!-- Competencies -->
                    <div class="flex flex-col space-y-1">
                        <label for="competencies" class="font-medium">Competencies<span
                                class="ml-1 text-red-500">*</span></label>
                        <!-- Add Competency Input -->
                        <div class="flex gap-2 mb-3">
                            <input v-model="newCompetency" type="text"
                                placeholder="Add a competency (e.g., Safety Management)"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                @keyup.enter="addCompetency" />
                            <button type="button" @click="addCompetency"
                                class="group px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition">
                                <Plus class="size-5 transition-transform group-hover:rotate-90 duration-300" />
                            </button>
                        </div>

                        <!-- Competencies List -->
                        <div v-if="competenciesArray.length > 0" class="flex flex-wrap gap-2 mb-3">
                            <div v-for="(comp, idx) in competenciesArray" :key="idx"
                                class="inline-flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm">
                                {{ comp }}
                                <button type="button" @click="removeCompetency(idx)"
                                    class="hover:text-red-600 dark:hover:text-red-400 transition">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <!-- <p v-if="competenciesArray.length === 0"
                            class="text-sm text-gray-500 dark:text-gray-400 italic">
                            No competencies added yet. Add one using the input above.
                        </p> -->

                        <p v-if="form.errors.competencies" class="mt-1 text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.competencies }}
                        </p>
                    </div>

                    <!-- Visible Checkbox -->
                    <div class="flex items-center space-x-3">
                        <label for="is_active" class="font-medium text-gray-700 dark:text-gray-300">Visible</label>
                        <button type="button" @click="form.is_active = !form.is_active" :class="[
                            'relative inline-flex h-6 w-11 items-center rounded-full transition',
                            form.is_active ? 'bg-blue-600' : 'bg-gray-300 dark:bg-gray-600'
                        ]">
                            <span :class="[
                                'inline-block h-4 w-4 transform rounded-full bg-white transition',
                                form.is_active ? 'translate-x-6' : 'translate-x-1'
                            ]" />
                        </button>
                    </div>

                    <!-- Submit -->
                    <!-- <div>
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                            :disabled="form.processing">
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update</span>
                        </button>
                    </div> -->
                    <div class="inline-flex justify-end w-full">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold transition"
                            :disabled="form.processing">
                            <span v-if="form.processing">
                                {{ isEditing ? 'Updating...' : 'Creating...' }}
                            </span>
                            <span v-else>
                                {{ isEditing ? 'Update' : 'Save' }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
