<script setup lang="ts">
import PortalFooter from '@/components/portal/PortalFooter.vue';
import PortalHeader from '@/components/portal/PortalHeader.vue';
import { Button } from '@/components/ui/button';
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeft, Home } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: Number,
        default: 500,
    },
    message: String,
});

const title = computed(() => {
    const statusTitles: { [key: number]: string } = {
        503: '503: Servicio no disponible',
        500: '500: Error del servidor',
        404: '404: Página no encontrada',
        403: '403: Acceso prohibido',
    };
    return statusTitles[props.status as number] || 'Error';
});

const description = computed(() => {
    const statusDescriptions: { [key: number]: string } = {
        503: 'Lo sentimos, estamos realizando mantenimiento. Por favor, vuelve más tarde.',
        500: '¡Ups! Algo salió mal en nuestros servidores.',
        404: 'Lo sentimos, no pudimos encontrar la página que buscas.',
        403: 'Lo sentimos, no tienes permiso para acceder a esta página.',
    };
    return statusDescriptions[props.status as number] || 'Ha ocurrido un error.';
});

function goHome() {
    router.visit('/');
}

function goBack() {
    window.history.back();
}
</script>

<template>
    <div class="flex min-h-screen flex-col bg-gray-50">
        <Head :title="title" />

        <!-- Header del Portal -->
        <PortalHeader />

        <!-- Contenido principal -->
        <main class="flex flex-1 items-center justify-center px-4 py-12">
            <div class="w-full max-w-2xl text-center">
                <!-- Código de estado -->
                <div class="mb-4">
                    <span class="text-8xl font-bold text-gray-300">{{ status }}</span>
                </div>

                <!-- Título y mensaje -->
                <h1 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">
                    {{ title }}
                </h1>

                <p class="mx-auto mb-8 max-w-lg text-lg text-gray-600">
                    {{ description }}
                    <span v-if="message" class="mt-2 block text-sm">{{ message }}</span>
                </p>

                <!-- Botones de acción -->
                <div class="mb-12 flex flex-col justify-center gap-4 sm:flex-row">
                    <Button @click="goHome" size="lg" class="gap-2">
                        <Home class="h-5 w-5" />
                        Ir al inicio
                    </Button>

                    <Button variant="outline" @click="goBack" size="lg" class="gap-2">
                        <ArrowLeft class="h-5 w-5" />
                        Volver atrás
                    </Button>
                </div>
            </div>
        </main>

        <!-- Footer del Portal -->
        <PortalFooter />
    </div>
</template>
