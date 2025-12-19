<script setup lang="ts">
import { Toaster } from '@/components/ui/sonner';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { toast } from 'vue-sonner';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});

const page = usePage();

// Detectar navegación hacia atrás
onMounted(() => {
    window.addEventListener('popstate', () => {
        sessionStorage.setItem('isBackNavigation', 'true');
    });

    // Mostrar flash messages si no es navegación hacia atrás
    if (sessionStorage.getItem('isBackNavigation') !== 'true') {
        showFlashMessages();
    }

    // Limpiar el flag después de usarlo
    sessionStorage.removeItem('isBackNavigation');
});

function showFlashMessages() {
    const flash = page.props.flash;
    if (flash?.success) {
        toast.success(flash.success);
    }
    if (flash?.error) {
        toast.error(flash.error, { richColors: true });
    }
    if (flash?.info) {
        toast.info(flash.info);
    }
    if (flash?.warning) {
        toast.warning(flash.warning);
    }
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <slot />
        <Toaster position="top-right" />
    </AppLayout>
</template>
