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

// Find matching sidebar parent
const parentItem = mainNavItems.find(item =>
    currentUrl.startsWith(new URL(item.href, window.location.origin).pathname)
)

// Build breadcrumbs
const breadcrumbs: BreadcrumbItemType[] = []

if (parentItem) {
    breadcrumbs.push({ title: parentItem.title, href: parentItem.href })

    // Add child based on path
    const segments = currentUrl.split('/').filter(Boolean)
    if (segments.length > 2) {
        const last = segments[segments.length - 1]

        if (last === 'create') {
            breadcrumbs.push({ title: 'Create' })
        } else if (last === 'edit') {
            breadcrumbs.push({ title: 'Edit' })
        } else if (!isNaN(Number(last))) {
            breadcrumbs.push({ title: `#${last}` }) // numeric ID
        }
    }
}
</script>

<template>
    <AppShell variant="sidebar">
        <AppSidebar />
        <AppContent variant="sidebar" class="overflow-x-hidden">
            <!-- Breadcrumb with bigger font -->
            <div class="font-semibold mb-4">
                <AppSidebarHeader :breadcrumbs="breadcrumbs" />
            </div>
            <slot />
        </AppContent>
    </AppShell>
</template>
