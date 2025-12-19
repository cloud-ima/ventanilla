<script setup lang="ts">
import PortalBanner from '@/components/portal/PortalBanner.vue';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { home } from '@/routes';
import * as routes from '@/routes/portal';
import { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ChevronRight, FileText } from 'lucide-vue-next';

interface Categories {
    id: number;
    name: string;
    slug: string;
    description: string;
    is_active: boolean;
    procedures_count: number;
}

interface Department {
    id: number;
    name: string;
    slug: string;
    description: string;
    icon: string;
    color: string;
}

const props = defineProps<{
    department: Department;
    categories: Categories[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: home().url },
    {
        title: props.department.name,
        href: routes.department(props.department.slug).url,
    },
];
</script>

<template>
    <Head :title="department.name" />

    <PortalLayout>
        <!-- Breadcrumb + Header -->
        <PortalBanner
            :breadcrumbs="breadcrumbs"
            :title="department.name"
            :description="department.description"
        />
        <!-- Categorías -->
        <section class="py-24">
            <div class="container mx-auto px-6">
                <h2 class="mb-6 text-xl font-bold text-gray-900">Categorías de trámites</h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <template v-for="category in categories" :key="category.id">
                        <Link
                            v-if="category.is_active"
                            :href="
                                routes.category({
                                    department: department.slug,
                                    category: category.slug,
                                }).url
                            "
                            class="group rounded-xl border border-gray-100 bg-white p-6 shadow-sm transition-all hover:shadow-md"
                        >
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-[#06048c] transition-transform group-hover:scale-110"
                                >
                                    <FileText class="h-6 w-6 text-white" />
                                </div>
                                <div class="flex-1">
                                    <h3 class="mb-1 font-semibold text-gray-900">
                                        {{ category.name }}
                                    </h3>
                                    <p class="mb-2 text-gray-500">
                                        {{ category.description }}
                                    </p>
                                    <span class="text-xs font-medium text-primary">
                                        {{ category.procedures_count }}
                                        Trámites disponibles
                                    </span>
                                </div>
                                <ChevronRight class="h-5 w-5 text-accent" />
                            </div>
                        </Link>
                    </template>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
