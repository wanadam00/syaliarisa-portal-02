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
            <div class="flex items-center">
                <img src="/images/crop-logo-2.png" alt="Syaliarisa Services" class="h-11 block dark:hidden" />
                <span
                    class="block dark:hidden mx-4 font-bold text-lg uppercase drop-shadow-md drop-shadow-gray-400 font-mono">Syaliarisa</span>
                <img src="/images/crop-logo-2.png" alt="Syaliarisa Services" class="h-11 hidden dark:block" />
            </div>
        </template>

        <!-- Show crop logo when collapsed -->
        <template v-else>
            <img src="/images/crop-logo-2.png" alt="Syaliarisa Services"
                class="flex justify-center items-center w-full" />
        </template>
    </div>
</template>
