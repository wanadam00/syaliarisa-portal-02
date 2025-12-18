<script setup lang="ts">
import type { HTMLAttributes } from 'vue'
import { cn } from '@/lib/utils'

const props = defineProps<{
  class?: HTMLAttributes['class']
}>()
</script>

<template>
  <li
    data-slot="sidebar-menu-item"
    data-sidebar="menu-item"
    :class="cn('group/menu-item relative transition-transform hover:translate-x-2 duration-300 ease-in-out', props.class)"
  >
    <slot />
  </li>
</template>
