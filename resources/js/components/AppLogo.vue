<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <a href="/" class="flex items-center">
        <!-- Light mode logo (shown by default) -->
        <img src="/images/primary-logo.png" alt="Syaliarisa Services" class="h-16 block dark:hidden" />

        <!-- Dark mode logo (hidden by default, shown in dark mode) -->
        <img src="/images/secondary-logo.png" alt="Syaliarisa Services" class="h-16 hidden dark:block" />
    </a>
    <!-- <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
        <AppLogoIcon class="size-5 fill-current text-white dark:text-black" />
    </div> -->
    <!-- <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold">Laravel Starter Kit</span>
    </div> -->
</template>
