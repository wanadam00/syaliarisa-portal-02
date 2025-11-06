<template>
    <AppLayout>
        <div class="p-6 w-full mx-auto">
            <div
                class="overflow-y-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 mb-6 shadow-xl border border-gray-200/50 dark:border-gray-700/50">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="flex flex-col space-y-1">
                        <label for="name" class="font-medium">Name<span class="ml-1 text-red-500">*</span></label>
                        <input id="name" v-model="form.name" type="text" placeholder="Enter position name..."
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" required />
                        <span v-if="form.errors.name" class="text-sm text-red-600">
                            {{ form.errors.name }}
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1 relative" v-auto-close-dropdown>
                        <label for="parent_ids" class="font-medium">Reports to (select one or more)</label>

                        <div class="flex flex-wrap gap-2 p-2 border rounded-md min-h-[40px] w-full cursor-pointer"
                            @click="toggleDropdown">

                            <template v-if="selectedParents.length">
                                <span v-for="parent in selectedParents" :key="parent.id"
                                    class="bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                                    {{ parent.name }}
                                    <button type="button" @click.stop="removeParent(parent.id)"
                                        class="ml-1.5 text-blue-600 dark:text-blue-300 hover:text-blue-900 dark:hover:text-blue-50 transition-colors">
                                        &times;
                                    </button>
                                </span>
                            </template>
                            <span v-else class="text-gray-400">Select reporting positions...</span>
                        </div>

                        <div v-if="dropdownOpen"
                            class="absolute top-full mt-1 w-full bg-white dark:bg-gray-700 shadow-xl rounded-md border border-gray-200 dark:border-gray-600 max-h-60 overflow-y-auto z-10">

                            <input type="text" v-model="searchQuery" placeholder="Search positions..."
                                class="w-full p-2 border-b dark:border-gray-600 sticky top-0 bg-white dark:bg-gray-700 focus:ring-0" />

                            <div v-for="p in filteredPositions" :key="p.id" @click.stop="toggleParent(p.id)" :class="[
                                'px-4 py-2 cursor-pointer transition-colors',
                                'hover:bg-blue-50 dark:hover:bg-blue-900/50',
                                form.parent_ids.includes(p.id) ? 'bg-blue-100 dark:bg-blue-800 font-semibold' : 'text-gray-900 dark:text-gray-100'
                            ]">
                                {{ p.name }}
                            </div>
                            <div v-if="filteredPositions.length === 0" class="px-4 py-2 text-gray-500">
                                No positions found.
                            </div>
                        </div>

                        <small class="text-xs text-gray-400">The first selected will be saved as the primary
                            parent.</small>
                        <span v-if="form.errors.parent_ids" class="text-sm text-red-600">
                            {{ form.errors.parent_ids }}
                        </span>
                    </div>

                    <div class="flex flex-col space-y-1">
                        <label for="rank" class="font-medium">Rank (smaller = higher)</label>
                        <input id="rank" v-model.number="form.rank" type="number"
                            placeholder="Enter rank (e.g., 1 for highest)"
                            class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-500" />
                        <span v-if="form.errors.rank" class="text-sm text-red-600">
                            {{ form.errors.rank }}
                        </span>
                    </div>

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
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'

// --- Types ---
interface Position {
    id: number;
    name: string;
}

// --- Props & Form Setup ---
const { positions } = usePage().props as unknown as { positions: Position[] }

const form = useForm({
    name: '',
    parent_ids: [] as number[], // Array of IDs for multi-select
    rank: 0,
    is_visible: true
})

// --- Multi-Select State ---
const dropdownOpen = ref(false)
const searchQuery = ref('')

// Computed array of selected Position objects (for displaying tags)
const selectedParents = computed(() => {
    return form.parent_ids
        .map(id => positions.find(p => p.id === id))
        .filter((p): p is Position => p !== undefined);
});

// Filter the list of options based on the search query
const filteredPositions = computed(() => {
    if (!searchQuery.value) {
        return positions;
    }
    const query = searchQuery.value.toLowerCase();
    return positions.filter(p => p.name.toLowerCase().includes(query));
});

// --- Multi-Select Actions ---

// Open/close the dropdown
const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
    if (dropdownOpen.value) {
        searchQuery.value = ''; // Reset search on open
    }
};

// Add or remove a parent ID from the form.parent_ids array
const toggleParent = (id: number) => {
    const index = form.parent_ids.indexOf(id);
    if (index === -1) {
        // Add the ID
        form.parent_ids.push(id);
    } else {
        // Remove the ID
        form.parent_ids.splice(index, 1);
    }

    // Sort the IDs to maintain the "first selected is primary" logic
    form.parent_ids.sort((a, b) => {
        const aIndex = form.parent_ids.indexOf(a);
        const bIndex = form.parent_ids.indexOf(b);
        return aIndex - bIndex;
    });

    // Keep the dropdown open for multiple selections
};

// Remove a parent by clicking the 'x' on the tag
const removeParent = (id: number) => {
    const index = form.parent_ids.indexOf(id);
    if (index !== -1) {
        form.parent_ids.splice(index, 1);
    }
};


// --- Click Outside Directive (for dropdown closure) ---

// Directive to close dropdown when clicking outside its container
const vAutoCloseDropdown = {
    mounted: (el: HTMLElement) => {
        el.__clickOutsideHandler__ = (event: MouseEvent) => {
            if (!el.contains(event.target as Node)) {
                dropdownOpen.value = false;
            }
        };
        document.addEventListener('click', el.__clickOutsideHandler__);
    },
    unmounted: (el: HTMLElement) => {
        document.removeEventListener('click', el.__clickOutsideHandler__);
    }
};

// --- Form Submission ---
function submit() {
    form.post(route('admin.positions.store'))
}
</script>

<style scoped>
/* Optional: Hide the default scrollbar on the custom dropdown for a cleaner look */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 3px;
}
</style>
