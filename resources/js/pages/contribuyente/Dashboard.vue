<script setup lang="ts">
import PortalFooter from '@/components/portal/PortalFooter.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';

const page = usePage();
const user = page.props.auth.user;

// Trámites Más Solicitados (props reales vendrán del backend)
const featuredTramites = [
    {
        name: 'Patente Comercial',
        unidad: 'Rentas',
        slug: 'patente-comercial',
        unidad_slug: 'rentas',
        categoria_slug: 'patentes',
    },
    {
        name: 'Permiso de Circulación',
        unidad: 'Tránsito',
        slug: 'permiso-circulacion',
        unidad_slug: 'transito',
        categoria_slug: 'permisos',
    },
    {
        name: 'Certificado de Residencia',
        unidad: 'Dirección Social',
        slug: 'certificado-residencia',
        unidad_slug: 'social',
        categoria_slug: 'certificados',
    },
];

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <!-- Banner con saludo personalizado -->
        <section class="mb-10 rounded-xl bg-blue-100 p-10">
            <h2 class="mb-2 text-3xl font-bold text-gray-900">Hola,</h2>
            <h2 :user="user" class="mb-2 text-3xl font-bold text-gray-900">
                {{ user.name }}
            </h2>
            <p class="mb-4 text-gray-700">Te damos la bienvenida a tu Ventanilla Única</p>
            <Link
                href="/informacion-personal"
                class="inline-flex items-center font-medium text-blue-700 hover:underline"
            >
                Ir a Información Personal
                <svg
                    viewBox="0 0 16 16"
                    fill="none"
                    class="ml-2 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M5 8h6M11 8l-3 3M11 8l-3-3"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </Link>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-6">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-900">Trámites más solicitados</h2>
                    <a
                        href="/contribuyente/portal"
                        class="font-medium text-[#06048c] hover:underline"
                    >
                        Ver todos →
                    </a>
                </div>

                <div class="divide-y rounded-xl bg-white shadow-sm">
                    <Link
                        v-for="tramite in featuredTramites"
                        :key="tramite.name"
                        :href="`/portal/${tramite.unidad_slug}/${tramite.categoria_slug}/${tramite.slug}`"
                        class="flex items-center justify-between p-4 transition-colors hover:bg-gray-50"
                    >
                        <div class="flex items-center gap-3">
                            <FileText class="h-5 w-5 text-[#06048c]" />
                            <span class="font-medium text-gray-900">{{ tramite.name }}</span>
                        </div>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-gray-500">
                            {{ tramite.unidad }}
                        </span>
                    </Link>
                </div>
            </div>
        </section>
        <PortalFooter />
    </AppLayout>
</template>
