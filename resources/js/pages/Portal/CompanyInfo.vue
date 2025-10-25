<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { computed, onMounted, defineComponent, h, ref } from 'vue';
import AppLayout2 from '@/layouts/AppLayout2.vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

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

// Horizontal Organizational Chart Component
const HorizontalOrgChart: any = defineComponent({
    name: 'HorizontalOrgChart',
    props: {
        position: { type: Object as () => Position, required: true },
        level: { type: Number, default: 0 }
    },
    setup(props) {
        const isExpanded = computed(() => expandedNodes.value.has(props.position.id));
        const hasChildren = computed(() =>
            props.position.children && props.position.children.filter((c: Position) => hasEmployees(c)).length > 0
        );

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

        return () => h('div', { class: 'flex flex-col items-center' }, [
            // Position Card
            h('div', {
                class: `relative mb-4 ${props.level === 0 ? 'bg-white' : 'bg-white dark:bg-gray-800'} 
            rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4 min-w-64 transition-all duration-300 ease-out
            hover:shadow-xl hover:scale-105 cursor-pointer group`,
                onClick: () => hasChildren.value && toggleNode(props.position.id)
            }, [
                // Employee Name (Top - was position name)
                h('h3', {
                    class: `font-bold text-center mb-3 capitalize transition-colors duration-300 ${props.level === 0 ? 'text-gray-900 group-hover:text-blue-700' : 'text-gray-900 dark:text-white group-hover:text-blue-600'
                        }`
                }, props.position.employees.length > 0 ? props.position.employees[0].name : props.position.name),

                // Employees Grid
                h('div', { class: 'flex flex-wrap justify-center gap-3' },
                    props.position.employees.map(emp =>
                        h('div', {
                            class: 'flex flex-col items-center transition-all duration-300 ease-out hover:scale-110',
                            key: emp.id
                        }, [
                            // Employee Avatar
                            h('div', {
                                class: 'w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-md mb-1 transition-all duration-300 group-hover:border-blue-200 dark:group-hover:border-blue-800'
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
                            // Position Name (Bottom - was employee name)
                            h('p', {
                                class: `text-xs capitalize font-medium text-center max-w-20 truncate transition-colors duration-300 ${props.level === 0
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

            // Children Positions with advanced transitions
            h('div', {
                class: containerClasses.value
            }, [
                isExpanded.value && hasChildren.value ? h('div', {
                    class: 'flex gap-6 mt-4 relative'
                }, [
                    // Connecting lines with enhanced transition
                    h('div', {
                        class: 'absolute top-0 left-0 right-0 h-4 flex justify-center transition-all duration-700 ease-out'
                    }, [
                        h('div', {
                            class: connectingLineClasses.value,
                            style: { transformOrigin: 'top center' }
                        })
                    ]),

                    // Child nodes
                    ...props.position.children!.filter((c: Position) => hasEmployees(c)).map((child: Position) =>
                        h(HorizontalOrgChart, {
                            position: child,
                            level: props.level + 1,
                            key: child.id
                        })
                    )
                ]) : null
            ])
        ]);
    }
});

// Option 2: Compact Grid View Component
const CompactTeamGrid: any = defineComponent({
    name: 'CompactTeamGrid',
    props: {
        positions: { type: Array as () => Position[], required: true }
    },
    setup(props) {
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
                        }, position.name)
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
                                h(CompactTeamGrid, { positions: [child], key: child.id })
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
    { id: 1, name: "HQ - Kuala Lumpur", lat: 3.139, lng: 101.6869 },
    { id: 2, name: "Branch - Penang", lat: 5.4141, lng: 100.3288 },
    { id: 3, name: "Branch - Johor Bahru", lat: 1.4927, lng: 103.7414 },
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
            class="bg-gradient-to-r from-[#2262ae] to-[#48b2e5] dark:bg-background dark:border-b py-12 text-white pt-32"
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
                                <HorizontalOrgChart v-for="pos in visibleTopPositions" :key="pos.id" :position="pos" />
                            </div>
                        </div>
                    </div>

                    <!-- Grid View -->
                    <div v-if="activeView === 'grid'" class="space-y-8 py-8">
                        <CompactTeamGrid :positions="visibleTopPositions" />
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
                        Our Locations
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
.leaflet-container {
    border-radius: 0.75rem;
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