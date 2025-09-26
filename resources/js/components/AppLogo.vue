<script setup lang="ts">
import { computed } from "vue"
import { useSidebar } from "@/components/ui/sidebar" // 👈 hook provided by shadcn-vue sidebar

// detect sidebar state
const sidebar = useSidebar()
const isCollapsed = computed(() => sidebar.state.value === "collapsed")
</script>

<template>
    <div>
        <!-- Show full logo when not collapsed -->
        <template v-if="!isCollapsed">
            <img src="/images/primary-logo.png" alt="Syaliarisa Services" class="h-15 block dark:hidden" />
            <img src="/images/secondary-logo.png" alt="Syaliarisa Services" class="h-15 hidden dark:block" />
        </template>

        <!-- Show crop logo when collapsed -->
        <template v-else>
            <img src="/images/crop-logo.png" alt="Syaliarisa Services"
                class="flex justify-center items-center w-full" />
        </template>
    </div>
</template>
