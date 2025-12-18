<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, defineComponent, h, ref } from 'vue';
import AppLayout2 from '@/layouts/AppLayout2.vue';
// Leaflet removed: replacing client map with Competent Persons UI

// --- INTERFACES ---
interface CompanyInfo {
    background: string;
    vision: string;
    mission: string;
    is_visible: boolean;
}
interface Employee {
    id: number;
    name: string;
    photo: string | null;
    department?: string | null;
}

interface CompetentPerson {
    id: number;
    name: string;
    dosh_numbers: string | null;
    competencies: string | null;
    bio: string | null;
    employee_id?: number | null;
    is_active: boolean;
    employee?: { id: number; name: string; photo: string | null };
}

interface Position {
    id: number;
    name: string;
    parent_id?: number | null;
    parent_ids?: number[] | null; // This array must be populated by the backend for M:N display
    rank?: number | null;
    is_visible?: boolean;
    employees: Employee[];
    children?: Position[];
}

const { companyInfo, positions, unassignedEmployees, competentPersons } = usePage().props as unknown as {
    companyInfo: CompanyInfo;
    positions: Position[];
    unassignedEmployees: Employee[];
    competentPersons: CompetentPerson[];
};

const positionsList = computed(() => (positions || []) as Position[]);
const expandedNodes = ref<Set<number>>(new Set());

const formatQuillContent = (html: string) => {
    if (!html) return "";
    return html
        .replace(/<ol>/g, '<ol style="list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/<ul>/g, '<ul style="list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem;">')
        .replace(/class="ql-align-center"/g, 'style="text-align: center;"')
        .replace(/class="ql-align-right"/g, 'style="text-align: right;"')
        .replace(/class="ql-align-justify"/g, 'style="text-align: justify;"')
        .replace(/<p><br><\/p>/g, '');
};

// New: Create a flat map of all positions for quick lookup by ID
const flatPositionsMap = computed(() => {
    const map = new Map<number, Position>();
    const traverse = (posArray: Position[]) => {
        posArray.forEach(pos => {
            map.set(pos.id, pos);
            if (pos.children) {
                traverse(pos.children);
            }
        });
    };
    traverse(positionsList.value);
    return map;
});

// Return true if this position or any descendant has employees
function hasEmployees(pos: Position): boolean {
    if (pos.employees && pos.employees.length > 0) return true;
    if (!pos.children || pos.children.length === 0) return false;
    return pos.children.some(child => hasEmployees(child));
}

function toggleNode(positionId: number): void {
    if (expandedNodes.value.has(positionId)) {
        expandedNodes.value.delete(positionId);
    } else {
        expandedNodes.value.add(positionId);
    }
}

// --- Horizontal Organizational Chart Component (Refactored for M:N parent display) ---
const HorizontalOrgChart: any = defineComponent({
    name: 'HorizontalOrgChart',
    props: {
        position: { type: Object as () => Position, required: true },
        level: { type: Number, default: 0 },
        positionsMap: { type: Object as () => Map<number, Position>, required: true }
    },
    setup(props) {
        const isExpanded = computed(() => expandedNodes.value.has(props.position.id));
        const hasChildren = computed(() =>
            props.position.children && props.position.children.filter((c: Position) => hasEmployees(c)).length > 0
        );

        // Get parent names from parent_ids
        const parentNames = computed(() => {
            const parentIds = props.position.parent_ids || [];
            // Filter out the primary parent_id if it's included in the list,
            // but for visualization purposes, we show all linked parents.
            return parentIds
                .map(id => props.positionsMap.get(id)?.name)
                .filter((name): name is string => !!name);
        });

        // Check if this position has multiple reporting lines (M:N)
        const hasMultipleParents = computed(() => parentNames.value && parentNames.value.length > 1);


        // Advanced transition control
        const containerClasses = computed(() => {
            const baseClasses = 'overflow-hidden';

            if (isExpanded.value && hasChildren.value) {
                // Opening animation - slower and more dramatic
                return `${baseClasses} max-h-[2000px] opacity-100 translate-y-0 scale-100 transition-all duration-500 ease-out`;
            } else {
                // Closing animation - faster and more subtle
                return `${baseClasses} max-h-0 opacity-0 -translate-y-2 scale-95 transition-all duration-400 ease-in`;
            }
        });

        const connectingLineClasses = computed(() => {
            return `w-px h-full bg-gray-300 dark:bg-gray-600 transition-all duration-700 ${isExpanded.value ? 'opacity-100 scale-y-100' : 'opacity-0 scale-y-0'
                }`;
        });

        return () => h('div', { class: 'flex flex-col items-center pb-4 px-4' }, [
            // Position Card
            h('div', {
                class: `relative mb-4 ${props.level === 0 ? 'bg-white' : 'bg-white dark:bg-gray-800'}
                rounded-lg shadow-lg border-2 border-gray-200 dark:border-gray-700 p-4 min-w-64 transition-all duration-300 ease-out
                hover:shadow-xl hover:scale-105 cursor-pointer group`,
                onClick: () => hasChildren.value && toggleNode(props.position.id)
            }, [
                // M:N Alert Icon
                hasMultipleParents.value ? h('i', {
                    class: 'bi bi-share-fill absolute top-2 right-2 text-red-500 text-sm animate-pulse',
                    title: 'Reports to multiple positions'
                }) : null,

                // Primary Employee Name (The Role Holder)
                h('h3', {
                    class: `font-bold text-center mb-1 capitalize transition-colors duration-300 ${props.level === 0 ? 'text-gray-900 group-hover:text-blue-700' : 'text-gray-900 dark:text-white group-hover:text-blue-600'
                        }`
                }, props.position.employees.length > 0 ? props.position.employees[0].name : props.position.name),

                // **REFINED M:N Reporting Line Display**
                // hasMultipleParents.value ? h('div', {
                //     class: 'text-xs text-center text-red-500 dark:text-red-400 font-semibold mb-3 border-t border-b border-dashed border-red-300 dark:border-red-700 pt-2 pb-2 mt-2 px-1 bg-red-50/50 dark:bg-red-900/10 rounded'
                // }, [
                //     h('p', { class: 'mb-1' }, 'Reports to Multiple Positions:'),
                //     h('p', { class: 'font-normal text-red-700 dark:text-red-300' }, parentNames.value!.join(' & '))
                // ]) : null,


                // Employees Grid
                h('div', { class: 'flex flex-wrap justify-center gap-4 mt-3' },
                    props.position.employees.map(emp =>
                        h('div', {
                            class: 'flex flex-col items-center transition-all duration-300 ease-out hover:scale-110',
                            key: emp.id
                        }, [
                            // Employee Avatar
                            h('div', {
                                class: 'w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-md mb-2 transition-all duration-300 group-hover:border-blue-200 dark:group-hover:border-blue-800'
                            }, [
                                emp.photo
                                    ? h('img', {
                                        src: emp.photo,
                                        class: 'w-full h-full object-cover transition-transform duration-300 group-hover:scale-110',
                                        alt: emp.name
                                    })
                                    : h('div', {
                                        class: `w-full h-full flex items-center justify-center transition-colors duration-300 ${props.level === 0
                                            ? 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400 dark:group-hover:bg-gray-500'
                                            : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400 dark:group-hover:bg-gray-500'
                                            }`
                                    }, [
                                        h('i', { class: `bi bi-person text-lg ${props.level === 0 ? 'text-gray-600 dark:text-gray-300' : 'text-gray-600 dark:text-gray-300'}` })
                                    ])
                            ]),
                            // Position Name - Updated for 2-line display
                            h('p', {
                                class: `text-xs capitalize font-medium text-center max-w-32 transition-colors duration-300 leading-tight break-words ${props.level === 0
                                    ? 'text-gray-600 group-hover:text-blue-600'
                                    : 'text-gray-700 dark:text-gray-300 group-hover:text-blue-500'
                                    }`
                            }, props.position.name)
                        ])
                    )
                ),

                // Expand/Collapse Indicator
                hasChildren.value ? h('div', {
                    class: `absolute -bottom-7 left-1/2 transform -translate-x-1/2 transition-all duration-500 ease-in-out ${isExpanded.value
                        ? 'rotate-180 scale-110 text-blue-700'
                        : 'group-hover:scale-110 group-hover:text-blue-500'
                        }`
                }, [
                    h('i', {
                        class: `bi bi-chevron-down ${props.level === 0 ? 'text-blue-600' : 'text-blue-600'} text-lg`
                    })
                ]) : null
            ]),

            // Children Positions with advanced transitions (Primary Hierarchy Display)
            h('div', {
                class: containerClasses.value
            }, [
                isExpanded.value && hasChildren.value ? h('div', {
                    class: 'flex gap-6 mt-4 relative'
                }, [
                    // Connecting line (vertical line from parent to horizontal line)
                    h('div', {
                        class: 'absolute top-0 left-1/2 transform -translate-x-1/2 w-px h-4 bg-gray-300 dark:bg-gray-600'
                    }),
                    // Horizontal line connecting all children
                    h('div', {
                        class: 'absolute top-4 left-0 right-0 h-px bg-gray-300 dark:bg-gray-600'
                    }),

                    // Child nodes (Recursively call the component)
                    ...props.position.children!.filter((c: Position) => hasEmployees(c)).map((child: Position) =>
                        h('div', { class: 'pt-8' }, [ // Wrapper for vertical connector line
                            // Vertical line down to the child card
                            h('div', {
                                // class: 'absolute left-1/2 transform -translate-x-1/2 w-px h-4 bg-gray-300 dark:bg-gray-600'
                            }),
                            h(HorizontalOrgChart, {
                                position: child,
                                level: props.level + 1,
                                positionsMap: props.positionsMap,
                                key: child.id
                            })
                        ])
                    )
                ]) : null
            ])
        ]);
    }
});


// --- Option 2: Compact Grid View Component ---
const CompactTeamGrid: any = defineComponent({
    name: 'CompactTeamGrid',
    props: {
        positions: { type: Array as () => Position[], required: true },
        positionsMap: { type: Object as () => Map<number, Position>, required: true }
    },
    setup(props) {
        const getParentNames = (position: Position) => {
            if (!position.parent_ids || position.parent_ids.length === 0) return null;
            return position.parent_ids
                .map(id => props.positionsMap.get(id)?.name)
                .filter((name): name is string => !!name);
        };

        const hasMultipleParents = (position: Position) => {
            const names = getParentNames(position);
            return names && names.length > 1;
        }

        return () => h('div', { class: 'space-y-6' },
            props.positions.map(position =>
                h('div', {
                    class: 'rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden',
                    key: position.id
                }, [
                    // Department Header
                    h('div', {
                        class: 'bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-600'
                    }, [
                        h('h3', {
                            class: 'text-xl font-bold capitalize text-gray-800 dark:text-white'
                        }, position.name),
                        // **REFINED M:N Reporting Line Display in Grid Header**
                        hasMultipleParents(position) ? h('p', {
                            class: 'text-sm text-red-500 dark:text-red-400 font-medium mt-1'
                        }, `Reports to Multiple Positions: ${getParentNames(position)!.join(' & ')}`) : null,
                    ]),

                    // Employees Grid
                    h('div', { class: 'p-6' }, [
                        h('div', { class: 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4' },
                            position.employees.map(emp =>
                                h('div', {
                                    class: 'flex flex-col items-center text-center group hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition-colors',
                                    key: emp.id
                                }, [
                                    h('div', {
                                        class: 'w-20 h-20 rounded-full overflow-hidden border-4 border-white shadow-lg group-hover:border-blue-200 dark:group-hover:border-blue-800 transition-colors mb-3'
                                    }, [
                                        emp.photo
                                            ? h('img', {
                                                src: emp.photo,
                                                class: 'w-full h-full object-cover',
                                                alt: emp.name
                                            })
                                            : h('div', {
                                                class: 'w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-600'
                                            }, [
                                                h('i', { class: 'bi bi-person text-2xl text-gray-500 dark:text-gray-400' })
                                            ])
                                    ]),
                                    h('p', {
                                        class: 'font-medium text-gray-900 capitalize dark:text-white text-sm mb-1'
                                    }, emp.name),
                                    emp.department ? h('p', {
                                        class: 'text-xs text-gray-500 capitalize dark:text-gray-400'
                                    }, emp.department) : null
                                ])
                            )
                        )
                    ]),

                    // Recursive children
                    position.children && position.children.filter((c: Position) => hasEmployees(c)).length > 0
                        ? h('div', { class: 'border-t border-gray-200 dark:border-gray-600' },
                            position.children.filter((c: Position) => hasEmployees(c)).map((child: Position) =>
                                h(CompactTeamGrid, { positions: [child], positionsMap: props.positionsMap, key: child.id })
                            )
                        )
                        : null
                ])
            )
        );
    }
});


const visibleTopPositions = computed(() => positionsList.value.filter(p => hasEmployees(p)));
const activeView = ref<'hierarchical' | 'grid'>('hierarchical');

// Compute a flat list of all employees (from positions tree + unassigned)
const allEmployees = computed((): Employee[] => {
    const list: Employee[] = [];

    const collect = (pos: Position) => {
        if (pos.employees && pos.employees.length > 0) {
            pos.employees.forEach(e => list.push(e));
        }
        if (pos.children && pos.children.length > 0) {
            pos.children.forEach(child => collect(child));
        }
    };

    positionsList.value.forEach(root => collect(root));
    if (unassignedEmployees && unassignedEmployees.length > 0) {
        unassignedEmployees.forEach(e => list.push(e));
    }

    // Deduplicate by id just in case
    const dedup = new Map<number, Employee>();
    list.forEach(e => dedup.set(e.id, e));
    return Array.from(dedup.values());
});

const selectedCompetent = ref<number | null>(null);

function findPositionNameForEmployee(empId: number): string | null {
    for (const pos of flatPositionsMap.value.values()) {
        if (pos.employees && pos.employees.some(e => e.id === empId)) return pos.name;
    }
    return null;
}

function setCompetent(emp: Employee) {
    // Local-only selection for now. Backend hookup can be added later.
    selectedCompetent.value = emp.id;
    console.log('Selected competent person:', emp);
}

</script>

<template>

    <Head title="Company Info" />
    <AppLayout2>
        <!-- Hero Section -->
        <section
            class="bg-gradient-to-r from-[#133762] to-[#2262AE] dark:bg-background dark:border-b py-12 text-white pt-32"
            data-aos="fade-down">
            <div class="container mx-auto px-4 text-center">
                <h1
                    class="text-4xl md:text-5xl font-bold mb-4 dark:text-white dark:drop-shadow-[0_0_8px_rgba(255,255,255,0.6)]">
                    Company Information
                </h1>
                <p class="text-lg opacity-90">
                    Discover our vision, mission, and organizational values.
                </p>
            </div>
        </section>

        <!-- Content Section -->
        <section class="py-16">
            <div class="container mx-auto px-4">
                <!-- Company Background -->
                <div class="mb-12" data-aos="fade-up">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">About Us</h2>
                    <p class="mb-6 text-gray-700 dark:text-gray-300"
                        v-html="formatQuillContent(companyInfo.background)"></p>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                            data-aos="zoom-in">
                            <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Vision
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300" v-html="formatQuillContent(companyInfo.vision)">
                            </p>
                        </div>
                        <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                            data-aos="zoom-in">
                            <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Mission
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300"
                                v-html="formatQuillContent(companyInfo.mission)"></p>
                        </div>
                    </div>
                </div>

                <!-- Organization Structure -->
                <div class="mb-16" data-aos="fade-up">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 md:mb-0">
                            Team Structure
                        </h2>

                        <!-- View Toggle -->
                        <div class="flex space-x-2 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
                            <button @click="activeView = 'hierarchical'" :class="[
                                'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                                activeView === 'hierarchical'
                                    ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                            ]">
                                <i class="bi bi-diagram-3 mr-2"></i>Hierarchical
                            </button>
                            <button @click="activeView = 'grid'" :class="[
                                'px-4 py-2 rounded-md text-sm font-medium transition-colors',
                                activeView === 'grid'
                                    ? 'bg-white dark:bg-gray-700 text-blue-600 dark:text-blue-400 shadow-sm'
                                    : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'
                            ]">
                                <i class="bi bi-grid-3x3-gap mr-2"></i>Grid View
                            </button>
                        </div>
                    </div>

                    <!-- Hierarchical View -->
                    <div v-if="activeView === 'hierarchical'" class="overflow-x-auto py-8">
                        <div class="min-w-max flex justify-center">
                            <div class="space-y-8">
                                <HorizontalOrgChart v-for="pos in visibleTopPositions" :key="pos.id" :position="pos"
                                    :positions-map="flatPositionsMap" />
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div v-if="activeView === 'grid'" class="space-y-8 py-8">
                        <CompactTeamGrid :positions="visibleTopPositions" :positions-map="flatPositionsMap" />
                    </div>

                    <!-- View Instructions -->
                    <div class="text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <i class="bi bi-info-circle mr-2"></i>
                            {{ activeView === 'hierarchical'
                                ? 'Click on department cards to expand/collapse teams'
                                : 'Browse team members in an organized grid layout' }}
                        </p>
                    </div>
                </div>

                <!-- Competent Persons -->
                <div class="container mx-auto px-4" data-aos="fade-in">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">
                        Competent Persons
                    </h2>

                    <div v-if="competentPersons && competentPersons.length > 0" class="overflow-x-auto scrollbar-hide">
                        <div class="flex gap-6 pb-4 px-1">
                            <div v-for="person in competentPersons" :key="person.id" class="flex-none w-72">
                                <!-- Card Container -->
                                <div
                                    class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700 h-full">

                                    <!-- Background Accent -->
                                    <div
                                        class="absolute top-0 right-0 w-32 h-32 bg-blue-100 dark:bg-blue-900/30 rounded-full -mr-16 -mt-16 transition-transform duration-300 group-hover:scale-110">
                                    </div>

                                    <!-- Content -->
                                    <div class="relative p-6 flex flex-col h-full">

                                        <!-- Photo Section -->
                                        <div class="mb-4 flex justify-center">
                                            <div
                                                class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-500 shadow-lg">
                                                <img v-if="person.employee?.photo" :src="person.employee.photo"
                                                    :alt="person.employee.name"
                                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" />
                                                <div v-else
                                                    class="w-full h-full flex items-center justify-center bg-gray-100">
                                                    <i class="bi bi-person text-4xl text-gray-500"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Name -->
                                        <div
                                            class="text-md font-bold text-gray-900 dark:text-white text-center mb-5 line-clamp-2">
                                            {{ person.employee ? person.employee.name : person.name }}
                                        </div>

                                        <!-- No. of Certificates Section -->
                                        <div class="mb-4">
                                            <p
                                                class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">
                                                <i class="bi bi-file-text mr-1 text-blue-600 dark:text-blue-400"></i>
                                                No. of Certificates
                                            </p>
                                            <div v-if="person.dosh_numbers"
                                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-2 text-center">
                                                <p
                                                    class="font-mono text-sm text-blue-700 dark:text-blue-300 line-clamp-2">
                                                    {{ typeof person.dosh_numbers === 'string' ? person.dosh_numbers :
                                                        JSON.stringify(person.dosh_numbers) }}
                                                </p>
                                            </div>
                                            <div v-else
                                                class="text-gray-500 dark:text-gray-400 text-sm italic text-center py-2">
                                                Not specified
                                            </div>
                                        </div>

                                        <!-- Competencies Section -->
                                        <div class="flex-1">
                                            <p
                                                class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">
                                                <i class="bi bi-star mr-1 text-blue-600 dark:text-blue-400"></i>
                                                Competencies
                                            </p>
                                            <div v-if="person.competencies" class="flex flex-wrap gap-2">
                                                <span
                                                    v-for="(comp, idx) in person.competencies.split(',').map(c => c.trim()).filter(c => c)"
                                                    :key="idx"
                                                    class="inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs px-3 py-1 rounded-full transition-all duration-200 cursor-default whitespace-nowrap">
                                                    <i class="bi bi-check-lg mr-1"></i> {{ comp }}
                                                </span>
                                            </div>
                                            <div v-else class="text-gray-500 dark:text-gray-400 text-sm italic">
                                                No competencies listed
                                            </div>
                                        </div>
                                        <!-- Bio Section -->
                                        <div v-if="person.bio && formatQuillContent(person.bio).trim()" class="mt-4">
                                            <div
                                                class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider mb-2">
                                                <i class="bi bi-info-circle mr-1 text-blue-600 dark:text-blue-400"></i>
                                                Bio
                                            </div>
                                            <div class="text-sm text-gray-700 dark:text-gray-300 line-clamp-4"
                                                v-html="formatQuillContent(person.bio)">
                                            </div>
                                            <!-- <div v-else class="text-gray-500 dark:text-gray-400 text-sm italic">
                                                No bio available
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else
                        class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl shadow-lg overflow-hidden border border-blue-200 dark:border-blue-800 p-8 text-center">
                        <i class="bi bi-info-circle text-3xl text-blue-600 dark:text-blue-400 mb-3 block"></i>
                        <p class="text-gray-600 dark:text-gray-300 font-medium">
                            No competent persons have been added yet.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>

<style scoped>
/* Competent Persons card tweaks */
.flex.space-x-4.overflow-x-auto>div {
    flex: 0 0 auto;
}

/* Fix for the org chart structure layout */
/* This style block ensures the lines connecting children visually align when rendered by the VNode setup. */
.flex>div.pt-8:first-child {
    /* For the first child in the row, ensure it's positioned correctly relative to the horizontal line */
    margin-left: 0;
}

.flex>div.pt-8:last-child {
    /* For the last child, ensure it's positioned correctly */
    margin-right: 0;
}

/* Remove default style to allow better control over VNode rendering */
.HorizontalOrgChart {
    flex-grow: 1;
}

/* Smooth transitions for organizational chart */
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
</style>
