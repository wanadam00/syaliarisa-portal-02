<script setup lang="ts">
import AppContent from '@/components/AppContent.vue'
import AppShell from '@/components/AppShell.vue'
import AppSidebar from '@/components/AppSidebar.vue'
import AppSidebarHeader from '@/components/AppSidebarHeader.vue'
import { usePage } from '@inertiajs/vue3'
import { mainNavItems } from '@/config/nav'
import type { BreadcrumbItemType } from '@/types'

// Get current URL path
const currentUrl = usePage().url // e.g. "/admin/services/12/edit"

// Find matching sidebar parent or child
let parentItem = mainNavItems.find(item =>
    item.children ? item.children.some(child => child.href && currentUrl.startsWith(new URL(child.href, window.location.origin).pathname)) : item.href && currentUrl.startsWith(new URL(item.href, window.location.origin).pathname)
)

let childItem = null
if (parentItem?.children) {
    childItem = parentItem.children.find(child => child.href && currentUrl.startsWith(new URL(child.href, window.location.origin).pathname))
}

// Build breadcrumbs
const breadcrumbs: BreadcrumbItemType[] = []

if (parentItem) {
    breadcrumbs.push({ title: parentItem.title, href: parentItem.href || '#' })

    if (childItem) {
        breadcrumbs.push({ title: childItem.title, href: childItem.href || '#' })
    }

    // Add child based on path
    const segments = currentUrl.split('/').filter(Boolean)
    if (segments.length > 2) {
        const last = segments[segments.length - 1]

        if (last === 'create') {
            breadcrumbs.push({ title: 'Create', href: '#' })
        } else if (last === 'edit') {
            breadcrumbs.push({ title: 'Edit', href: '#' })
        } else if (!isNaN(Number(last))) {
            breadcrumbs.push({ title: `#${last}`, href: '#' }) // numeric ID
        }
    }
}
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden bg-gray-50">
            <!-- Breadcrumb with bigger font -->
            <div class="font-semibold mb-4">
                <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            </div>
            <slot />
        </AppContent>
    </AppShell>
</template>
