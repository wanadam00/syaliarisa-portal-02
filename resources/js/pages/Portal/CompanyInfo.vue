<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, defineComponent, h, ref } from 'vue';
import AppLayout2 from '@/layouts/AppLayout2.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

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

const { companyInfo, positions, unassignedEmployees } = usePage().props as unknown as {
    companyInfo: CompanyInfo;
    positions: Position[];
    unassignedEmployees: Employee[];
};

const positionsList = computed(() => (positions || []) as Position[]);
const expandedNodes = ref<Set<number>>(new Set());

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
                                            ? 'bg-blue-500 group-hover:bg-blue-600'
                                            : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400 dark:group-hover:bg-gray-500'
                                            }`
                                    }, [
                                        h('i', { class: `bi bi-person text-lg ${props.level === 0 ? 'text-white' : 'text-gray-600 dark:text-gray-300'}` })
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
                                class: 'absolute left-1/2 transform -translate-x-1/2 w-px h-4 bg-gray-300 dark:bg-gray-600'
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

// Example branch data (you can fetch this from Laravel later)
const clients = [
    { id: 1, name: "Perodua Manufacturing Sdn. Bhd.", lat: 3.3706333, lng: 101.5409997 },
    { id: 2, name: "Selinsing Gold Mine", lat: 4.2581719, lng: 101.7037384 },
    { id: 3, name: "Branch - Johor Bahru", lat: 2.6844839, lng: 101.9075693 },
    { id: 4, name: "Kum Hoi Engineering Industries Sdn Bhd", lat: 3.0539604, lng: 101.5133176 },
    { id: 5, name: "Royal Selangor International Sdn. Bhd", lat: 3.1966613, lng: 101.642032 },
    { id: 6, name: "Petrofac (Malaysia-Pm304) Limited (West Desaru Mopu)", lat: 3.155923, lng: 101.6275111 },
    { id: 7, name: "SGS (M) Sdn. Bhd.", lat: 3.2277944, lng: 101.4291594 },
    { id: 8, name: "Lablink (M) Sdn. Bhd.", lat: 3.2277388, lng: 101.42913 },
    { id: 9, name: "Rohm-Wako Elelctronic (M) SB", lat: 6.1387528, lng: 102.2193729 },
    { id: 10, name: "San Soon Seng Food Industries Sdn. Bhd.", lat: 3.1973235, lng: 101.4799408 },
    { id: 11, name: "Notion Venture Sdn Bhd", lat: 3.2277912, lng: 101.4291611 },
    { id: 12, name: "Samling Housing Sdn. Bhd.", lat: 3.205349, lng: 101.456431 },
    { id: 13, name: "SMC Composite Sdn. Bhd.", lat: 3.4864473, lng: 101.8505535 },
    { id: 14, name: "Herbal Science Sdn. Bhd.", lat: 3.0898669, lng: 101.5271453 },
    { id: 15, name: "Shinko Electronics (M) SB", lat: 3.0285399, lng: 101.479152 },
];

onMounted(() => {
    const map = L.map('client-map').setView([3.139, 101.6869], 6); // default Malaysia view

    // OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add markers for each branch
    clients.forEach(client => {
        L.marker([client.lat, client.lng])
            .addTo(map)
        // .bindPopup(`<b>${client.name}</b>`);
    });
});

// Fix default icon paths
delete (L.Icon.Default.prototype as any)._getIconUrl;

L.Icon.Default.mergeOptions({
    iconRetinaUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).toString(),
    iconUrl: new URL('leaflet/dist/images/marker-icon.png', import.meta.url).toString(),
    shadowUrl: new URL('leaflet/dist/images/marker-shadow.png', import.meta.url).toString(),
});

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
                    <p class="mb-6 text-gray-700 text-justify dark:text-gray-300">{{ companyInfo.background }}</p>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                            data-aos="zoom-in">
                            <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Mission
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 text-justify">{{ companyInfo.mission }}</p>
                        </div>
                        <div class="bg-white dark:bg-background p-6 rounded-lg shadow-md dark:shadow-white border border-gray-100 dark:border-white"
                            data-aos="zoom-in">
                            <h3 class="text-xl font-medium mb-4 text-primary dark:text-white">Vision
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 text-justify">{{ companyInfo.vision }}</p>
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

                <!-- Client Locations -->
                <div class="container mx-auto px-4" data-aos="fade-in">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white text-center">
                        Client Locations
                    </h2>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <div id="client-map" class="leaflet-container h-96"></div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout2>
</template>

<style scoped>
/* Ensure leaflet renders correctly */
.leaflet-container {
    border-radius: 0.75rem;
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
