<script setup lang="ts">
import PortalBanner from '@/components/portal/PortalBanner.vue';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { home } from '@/routes';
import * as routes from '@/routes/portal';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronRight, FileText } from 'lucide-vue-next';

interface Departament {
    id: number;
    name: string;
    slug: string;
}

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
    procedures: Procedure[];
}

interface Procedure {
    id: number;
    name: string;
    slug: string;
    description: string;
    modality: string;
}

const props = defineProps<{
    department: Departament;
    category: Category;
}>();

// Constantes para modalidades
const PROCEDURE_MODALITY = {
    ONLINE: 'online',
    PRESENCIAL: 'presencial',
    MIXTO: 'mixto',
} as const;

const getModalidadInfo = (modalidad: string) => {
    const info = {
        [PROCEDURE_MODALITY.ONLINE]: { text: 'En línea', class: 'bg-green-100 text-green-800' },
        [PROCEDURE_MODALITY.PRESENCIAL]: {
            text: 'Presencial',
            class: 'bg-orange-100 text-orange-800',
        },
        [PROCEDURE_MODALITY.MIXTO]: { text: 'Mixto', class: 'bg-blue-100 text-blue-800' },
    };
    return info[modalidad as keyof typeof info] || info[PROCEDURE_MODALITY.PRESENCIAL];
};

console.log(props);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: home().url },
    {
        title: props.department.name,
        href: routes.department(props.department.slug).url,
    },
    {
        title: props.category.name,
        href: routes.category({
            department: props.department.slug,
            category: props.category.slug,
        }).url,
    },
];
</script>

<template>
    <Head :title="`${category.name} - ${department.name}`" />

    <PortalLayout>
        <!-- Breadcrumb + Header -->
        <PortalBanner
            :breadcrumbs="breadcrumbs"
            :title="category.name"
            :description="category.description"
        />

        <!-- Lista de Trámites -->
        <section class="py-24">
            <div class="container mx-auto px-6">
                <h2 class="mb-6 text-xl font-bold text-gray-900">
                    Trámites disponibles ({{ category.procedures.length }})
                </h2>

                <div class="divide-y rounded-xl border border-gray-100 bg-white shadow-sm">
                    <Link
                        v-for="procedure in category.procedures"
                        :key="procedure.id"
                        :href="
                            routes.procedure({
                                category: category.slug,
                                procedure: procedure.slug,
                                department: department.slug,
                            }).url
                        "
                        class="group flex items-center gap-4 p-5 transition-colors hover:bg-gray-50"
                    >
                        <div
                            class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-[#06048c]/10"
                        >
                            <FileText class="h-5 w-5 text-[#06048c]" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3
                                class="font-semibold text-gray-900 transition-colors group-hover:text-[#06048c]"
                            >
                                {{ procedure.name }}
                            </h3>
                            <p class="truncate text-gray-500">
                                {{ procedure.description }}
                            </p>
                        </div>
                        <span
                            :class="`rounded-full px-3 py-1 text-xs font-medium ${getModalidadInfo(procedure.modality).class}`"
                        >
                            {{ getModalidadInfo(procedure.modality).text }}
                        </span>
                        <ChevronRight
                            class="h-5 w-5 flex-shrink-0 text-gray-400 transition-colors group-hover:text-[#06048c]"
                        />
                    </Link>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
