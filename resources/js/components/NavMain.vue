<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, SidebarMenuSub, SidebarMenuSubButton, SidebarMenuSubItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { ChevronRight } from 'lucide-vue-next';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ref } from 'vue';

defineProps<{
    items: NavItem[];
}>();

const page = usePage();
const openItems = ref<Record<string, boolean>>({});
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Platform</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
                <SidebarMenuButton v-if="!item.children" as-child :is-active="item.href === page.url"
                    :tooltip="item.title">
                    <Link :href="item.href">
                    <component :is="item.icon" />
                    <span>{{ item.title }}</span>
                    </Link>
                </SidebarMenuButton>
                <Collapsible v-else v-model:open="openItems[item.title]" class="group/collapsible">
                    <CollapsibleTrigger as-child>
                        <SidebarMenuButton :tooltip="item.title">
                            <component :is="item.icon" />
                            <span>{{ item.title }}</span>
                            <ChevronRight
                                class="ml-auto transition-transform duration-300 ease-in-out group-data-[state=open]/collapsible:rotate-90" />
                        </SidebarMenuButton>
                    </CollapsibleTrigger>
                    <CollapsibleContent class="overflow-hidden transition-all duration-300 ease-in-out" :class="{
                        'data-[state=closed]:max-h-0 data-[state=open]:max-h-96': true,
                        'data-[state=closed]:opacity-0 data-[state=open]:opacity-100': true,
                        'data-[state=closed]:translate-y-[-8px] data-[state=open]:translate-y-0': true
                    }">
                        <SidebarMenuSub class="py-1">
                            <SidebarMenuSubItem v-for="child in item.children" :key="child.title">
                                <SidebarMenuSubButton as-child :is-active="child.href === page.url">
                                    <Link :href="child.href" class="text-white hover:text-gray-900 transition-transform hover:translate-x-2 duration-300 ease-in-out">
                                    <span >{{ child.title }}</span>
                                    </Link>
                                </SidebarMenuSubButton>
                            </SidebarMenuSubItem>
                        </SidebarMenuSub>
                    </CollapsibleContent>
                </Collapsible>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
