<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-sm">
            <div class="flex flex-col">
                <div class="flex flex-col items-center" data-aos="fade-down">
                    <Link :href="route('portal.home')" class="flex flex-col items-center gap-2 font-medium">
                    <a href="/" class="flex flex-col items-center">
                        <!-- Light mode logo (shown by default) -->
                        <img src="/images/crop-logo-2.png" alt="Syaliarisa Services" class="h-22 block dark:hidden" />
                        <span
                            class="block dark:hidden my-2 text-[#2262AE] font-extrabold text-2xl uppercase drop-shadow-lg drop-shadow-gray-400 font-mono">Syaliarisa sdn bhd</span>

                        <!-- Dark mode logo (hidden by default, shown in dark mode) -->
                        <img src="/images/secondary-logo.png" alt="Syaliarisa Services"
                            class="h-22 hidden dark:block" />
                    </a>
                    <!-- <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                            <AppLogoIcon class="size-9 fill-current text-[var(--foreground)] dark:text-white" />
                        </div> -->
                    <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
