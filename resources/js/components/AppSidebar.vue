<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { LayoutGrid, Home, Info, Users, FileText, Gavel, Wrench, Phone, Folder, BookOpen } from 'lucide-vue-next';

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
import { type NavItem } from '@/types';

// Main admin sections
const mainNavItems: NavItem[] = [
    {
        title: 'Home',
        href: route('admin.home-sections.index'),
        icon: Home, // Updated to Home icon
    },
    {
        title: 'Company Info',
        href: route('admin.company-info.index'),
        icon: Info, // Updated to Info icon
    },
    {
        title: 'Organization Chart',
        href: route('admin.employees.index'),
        icon: Users, // Kept Users icon
    },
    {
        title: 'Standards Applications',
        href: route('admin.standard-applications.index'),
        icon: FileText, // Updated to FileText icon
    },
    {
        title: 'Legislations',
        href: route('admin.legislations.index'),
        icon: Gavel, // Updated to Gavel icon
    },
    {
        title: 'Services',
        href: route('admin.services.index'),
        icon: Wrench, // Updated to Wrench icon
    },
    {
        title: 'Contact Us',
        href: route('admin.contact-info.index'),
        icon: Phone, // Kept Phone icon
    },
];

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
    <Sidebar collapsible="icon" variant="inset" class="h-screen border-r shadow-sm">
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
