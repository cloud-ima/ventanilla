<script setup lang="ts">
import PortalFooter from '@/components/portal/PortalFooter.vue';
import PortalHeader from '@/components/portal/PortalHeader.vue';
import { Toaster } from '@/components/ui/sonner';
</script>

<template>
    <div class="flex min-h-screen flex-col font-[Montserrat]">
        <PortalHeader />

        <main class="flex-1">
            <slot />
        </main>

        <PortalFooter />
        <Toaster position="top-right" />
    </div>
</template>
