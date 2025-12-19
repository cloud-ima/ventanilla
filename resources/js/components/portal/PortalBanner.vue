<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import type { BreadcrumbItemType } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs: BreadcrumbItemType[];
        title: string;
        description?: string;
    }>(),
    {
        breadcrumbs: () => [],
        title: '',
        description: '',
    },
);
</script>
<template>
    <section
        class="flex min-h-[300px] flex-col bg-[url('/banner.svg')] bg-cover bg-[position:35%] bg-no-repeat px-8 pt-8 sm:bg-[position:40%] md:px-16 2xl:min-h-[400px]"
    >
        <!-- Contenido -->
        <div class="flex flex-1 flex-col justify-between">
            <!-- Breadcrumbs -->
            <div v-if="breadcrumbs && breadcrumbs.length > 0" class="mb-4">
                <Breadcrumbs
                    :breadcrumbs="breadcrumbs"
                    classList="text-white text-base font-semibold"
                    classItem="text-white text-base font-semibold"
                    classPage="text-white text-base font-semibold"
                    classLink="text-white text-base font-semibold"
                />
            </div>

            <div class="my-8">
                <!-- Título y descripción -->
                <h1 class="text-3xl font-bold text-white md:text-5xl">
                    {{ title }}
                </h1>
                <p v-if="description" class="mt-4 text-xl text-white/90">
                    {{ description }}
                </p>
            </div>
        </div>

        <!-- Espacio reservado para la decoración inferior del SVG -->
        <div class="h-26 md:h-20 2xl:h-22" aria-hidden="true" />
    </section>
</template>
