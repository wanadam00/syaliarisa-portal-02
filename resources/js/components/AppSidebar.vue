<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Home, Info, Users, FileText, Gavel, Wrench, Phone, Folder, BookOpen, Network } from 'lucide-vue-next';

import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem
} from '@/components/ui/sidebar';

import NavMain from '@/components/NavMain.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavUser from '@/components/NavUser.vue';
import AppLogo from './AppLogo.vue';
import { mainNavItems } from '@/config/nav';
import { type NavItem } from '@/types';

// Footer items (external)
const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#vue',
        icon: BookOpen,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset"
        class="h-screen border-r shadow-sm bg-gradient-to-b from-gray-900 to-gray-800 group-data-[variant=floating]:border-gray-700">
        <!-- Header with Logo -->
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('admin.home-sections.index')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- Main Nav Items -->
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <!-- Footer Nav & User -->
        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <!-- Page Content -->
    <slot />
</template>
