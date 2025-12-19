<script setup lang="ts">
import SearchModal from '@/components/SearchModal.vue';
import { getIconComponent } from '@/config/available-icon';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { department as departmentRoute } from '@/routes/portal';
import { Head, Link } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import { ref } from 'vue';

interface Department {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    color: string;
    procedures_count: number;
}
defineProps<{
    departments: Department[];
}>();

const isSearchModalOpen = ref(false);
</script>

<template>
    <Head title="Inicio" />

    <PortalLayout>
        <!-- Search Modal -->
        <SearchModal :is-open="isSearchModalOpen" @close="isSearchModalOpen = false" />

        <!-- Hero con Buscador -->
        <section
            class="bg-[url('/banner.svg')] bg-cover bg-[position:35%] bg-no-repeat py-16 text-white md:py-24"
        >
            <div class="container mx-auto px-6 text-center">
                <h1 class="mb-4 text-3xl font-bold md:text-4xl lg:text-5xl">
                    ¿Qué trámite necesitas realizar?
                </h1>
                <p class="mx-auto mb-8 max-w-2xl text-white/80">
                    Encuentra información sobre trámites y servicios de la Municipalidad de Arica
                </p>

                <!-- Buscador -->
                <div class="mx-auto max-w-2xl">
                    <div
                        class="flex cursor-pointer overflow-hidden rounded-full bg-white shadow-lg transition-shadow hover:shadow-xl"
                        @click="isSearchModalOpen = true"
                    >
                        <div class="flex flex-1 items-center gap-3 py-4 pl-4 text-gray-400">
                            <Search class="size-5" />
                            <span class="text-base"> Buscar trámites, servicios... </span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Unidades Municipales -->
        <section class="bg-gray-50 py-16">
            <div class="container mx-auto px-6">
                <h2 class="mb-8 text-center text-2xl font-bold text-gray-900">
                    Unidades Municipales
                </h2>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <Link
                        v-for="department in departments"
                        :key="department.slug"
                        :href="departmentRoute(department.slug).url"
                        class="group rounded-xl bg-white p-6 shadow-sm transition-shadow hover:shadow-md"
                    >
                        <div
                            :style="{ backgroundColor: department.color }"
                            class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl transition-transform group-hover:scale-110"
                        >
                            <component
                                :is="getIconComponent(department.icon)"
                                class="h-7 w-7 text-white"
                            />
                        </div>
                        <h3 class="mb-1 font-semibold text-gray-900">
                            {{ department.name }}
                        </h3>
                        <p class="text-gray-500">
                            {{ department.procedures_count }} trámites disponibles
                        </p>
                    </Link>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
|
