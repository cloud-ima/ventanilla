<script setup lang="ts">
import PortalBanner from '@/components/portal/PortalBanner.vue';
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';
import { Button } from '@/components/ui/button';
import PortalLayout from '@/layouts/PortalLayout.vue';
import { home, login } from '@/routes';
import { index } from '@/routes/contribuyente/patentes';
import * as route from '@/routes/portal';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { CheckCircle2, Clock, DollarSign, Globe, MapPin, Scale } from 'lucide-vue-next';

interface Requirement {
    id: number;
    name: string;
    description: string | null;
    how_to_obtain: string | null;
    is_active: boolean;
    pivot: RequirementPivot;
}

interface RequirementPivot {
    is_mandatory: boolean;
    order: number;
    procedure_id: number;
    requirement_id: number;
}

interface Procedure {
    id: number;
    name: string;
    slug: string;
    description: string;
    modality: string;
    cost: string | null;
    duration: string | null;
    user_requirements: string[] | null;
    instructions: string[] | null;
    legal_framework: string | null;
    requirements: Requirement[];
}

interface Category {
    id: number;
    name: string;
    slug: string;
}

interface Department {
    id: number;
    name: string;
    slug: string;
}

const props = defineProps<{
    department: Department;
    category: Category;
    procedure: Procedure;
}>();

// Constantes para modalidades
const PROCEDURE_MODALITY = {
    ONLINE: 'online',
    PRESENCIAL: 'presencial',
    MIXTO: 'mixto',
} as const;

const getModalidadInfo = (modalidad: string) => {
    const info = {
        [PROCEDURE_MODALITY.ONLINE]: {
            text: 'En línea',
            icon: Globe,
            class: 'text-green-600',
        },
        [PROCEDURE_MODALITY.PRESENCIAL]: {
            text: 'Presencial',
            icon: MapPin,
            class: 'text-orange-600',
        },
        [PROCEDURE_MODALITY.MIXTO]: {
            text: 'Mixto',
            icon: Globe,
            class: 'text-blue-600',
        },
    };
    return info[modalidad as keyof typeof info] || info[PROCEDURE_MODALITY.PRESENCIAL];
};

const page = usePage();
const user = page.props.auth.user;

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Inicio', href: home().url },
    {
        title: props.department.name,
        href: route.department(props.department.slug).url,
    },
    {
        title: props.category.name,
        href: route.category({
            category: props.category.slug,
            department: props.department.slug,
        }).url,
    },
    {
        title: props.procedure.name,
        href: route.procedure({
            procedure: props.procedure.slug,
            category: props.category.slug,
            department: props.department.slug,
        }).url,
    },
];
</script>

<template>
    <Head :title="`${procedure.name} - ${category.name} - ${department.name}`" />

    <PortalLayout>
        <PortalBanner
            :breadcrumbs="breadcrumbs"
            :title="procedure.name"
            :description="procedure.description"
        />

        <!-- Contenido -->
        <section class="py-24">
            <div class="container mx-auto px-6">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Columna principal -->
                    <div class="lg:col-span-2">
                        <Accordion
                            type="single"
                            collapsible
                            default-value="requisitos"
                            class="space-y-4"
                        >
                            <!-- Documentos Requeridos -->
                            <AccordionItem
                                v-if="procedure.requirements && procedure.requirements.length > 0"
                                value="requisitos"
                                class="rounded-xl border border-gray-100 bg-white px-6 shadow-sm"
                            >
                                <AccordionTrigger
                                    class="py-5 text-lg font-bold text-gray-900 hover:no-underline"
                                >
                                    <div class="flex items-center gap-2">
                                        <span class="h-6 w-1 rounded-full bg-[#06048c]"></span>
                                        Documentos Requeridos
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent class="pb-6">
                                    <ul class="space-y-3">
                                        <li
                                            v-for="requirement in procedure.requirements"
                                            :key="requirement.id"
                                            class="flex items-start gap-3"
                                        >
                                            <CheckCircle2
                                                class="mt-0.5 h-5 w-5 flex-shrink-0"
                                                :class="
                                                    requirement.pivot.is_mandatory
                                                        ? 'text-[#06048c]'
                                                        : 'text-green-500'
                                                "
                                            />
                                            <div>
                                                <p class="text-gray-900">
                                                    {{ requirement.name }}
                                                    <span
                                                        v-if="requirement.pivot.is_mandatory"
                                                        class="text-red-500"
                                                        >*</span
                                                    >
                                                </p>
                                                <p
                                                    v-if="requirement.description"
                                                    class="mt-1 text-gray-500"
                                                >
                                                    {{ requirement.description }}
                                                </p>
                                                <p
                                                    v-if="requirement.how_to_obtain"
                                                    class="mt-1 text-blue-600"
                                                >
                                                    Cómo obtenerlo:
                                                    {{ requirement.how_to_obtain }}
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                    <p class="mt-4 text-gray-500">
                                        <span class="text-red-500">*</span>
                                        Requisitos obligatorios
                                    </p>
                                </AccordionContent>
                            </AccordionItem>

                            <!-- Requisitos del Usuario -->
                            <AccordionItem
                                v-if="
                                    procedure.user_requirements &&
                                    procedure.user_requirements.length > 0
                                "
                                value="usuario"
                                class="rounded-xl border border-gray-100 bg-white px-6 shadow-sm"
                            >
                                <AccordionTrigger
                                    class="py-5 text-lg font-bold text-gray-900 hover:no-underline"
                                >
                                    <div class="flex items-center gap-2">
                                        <span class="h-6 w-1 rounded-full bg-[#06048c]"></span>
                                        Requisitos del Usuario
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent class="pb-6">
                                    <ul class="list-inside list-disc space-y-2 text-gray-700">
                                        <li
                                            v-for="(
                                                requirement, index
                                            ) in procedure.user_requirements"
                                            :key="index"
                                        >
                                            {{ requirement }}
                                        </li>
                                    </ul>
                                </AccordionContent>
                            </AccordionItem>

                            <!-- Instrucciones Paso a Paso -->
                            <AccordionItem
                                v-if="procedure.instructions && procedure.instructions.length > 0"
                                value="instrucciones"
                                class="rounded-xl border border-gray-100 bg-white px-6 shadow-sm"
                            >
                                <AccordionTrigger
                                    class="py-5 text-lg font-bold text-gray-900 hover:no-underline"
                                >
                                    <div class="flex items-center gap-2">
                                        <span class="h-6 w-1 rounded-full bg-[#06048c]"></span>
                                        Instrucciones Paso a Paso
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent class="pb-6">
                                    <ol class="list-inside list-decimal space-y-2 text-gray-700">
                                        <li
                                            v-for="(instruction, index) in procedure.instructions"
                                            :key="index"
                                        >
                                            {{ instruction }}
                                        </li>
                                    </ol>
                                </AccordionContent>
                            </AccordionItem>

                            <!-- Marco Legal -->
                            <AccordionItem
                                v-if="procedure.legal_framework"
                                value="marco_legal"
                                class="rounded-xl border border-gray-100 bg-white px-6 shadow-sm"
                            >
                                <AccordionTrigger
                                    class="py-5 text-lg font-bold text-gray-900 hover:no-underline"
                                >
                                    <div class="flex items-center gap-2">
                                        <span class="h-6 w-1 rounded-full bg-[#06048c]"></span>
                                        Marco Legal
                                    </div>
                                </AccordionTrigger>
                                <AccordionContent class="pb-6">
                                    <div class="flex items-start gap-3 text-gray-700">
                                        <Scale class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-500" />
                                        <p class="whitespace-pre-line">
                                            {{ procedure.legal_framework }}
                                        </p>
                                    </div>
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>
                    </div>

                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Card de acción -->
                        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
                            <h3 class="mb-4 font-bold text-gray-900">Iniciar trámite</h3>

                            <div class="mb-6 space-y-4">
                                <!-- Modalidad -->
                                <div class="flex items-center gap-3">
                                    <component
                                        :is="getModalidadInfo(procedure.modality).icon"
                                        class="h-5 w-5"
                                        :class="getModalidadInfo(procedure.modality).class"
                                    />
                                    <div>
                                        <p class="text-xs text-gray-500">Modalidad</p>
                                        <p class="font-medium text-gray-900">
                                            {{ getModalidadInfo(procedure.modality).text }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Costo -->
                                <div v-if="procedure.cost" class="flex items-center gap-3">
                                    <DollarSign class="h-5 w-5 text-green-600" />
                                    <div>
                                        <p class="text-xs text-gray-500">Costo</p>
                                        <p class="font-medium text-gray-900">
                                            {{ procedure.cost }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Duración -->
                                <div v-if="procedure.duration" class="flex items-center gap-3">
                                    <Clock class="h-5 w-5 text-blue-600" />
                                    <div>
                                        <p class="text-xs text-gray-500">Tiempo estimado</p>
                                        <p class="font-medium text-gray-900">
                                            {{ procedure.duration }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <template
                                v-if="
                                    procedure.modality === 'online' ||
                                    procedure.modality === 'mixto'
                                "
                            >
                                <!-- Usuario es admin o funcionario -->
                                <div
                                    v-if="
                                        user &&
                                        (user.role === 'admin' || user.role === 'funcionario')
                                    "
                                    class="rounded-lg border border-yellow-200 bg-yellow-50 p-4"
                                >
                                    <p class="font-medium text-yellow-800">
                                        Advertencia: Cuenta de
                                        <strong>{{ user.role.toUpperCase() }}</strong>
                                    </p>
                                    <p class="mt-1 text-yellow-700">
                                        Para solicitar trámites necesitas una cuenta de
                                        contribuyente. Esta funcionalidad solo está disponible para
                                        contribuyentes. Las opciones solo lo redirigirán a tu panel
                                        de administración.
                                    </p>
                                </div>

                                <!-- Usuario es contribuyente -->
                                <Button
                                    v-else-if="user && user.role === 'contribuyente'"
                                    as-child
                                    class="w-full bg-[#06048c] hover:bg-[#050370]"
                                >
                                    <Link :href="index()">Solicitar trámite</Link>
                                </Button>

                                <!-- Usuario no autenticado -->
                                <Button
                                    v-else
                                    as-child
                                    class="w-full bg-[#06048c] hover:bg-[#050370]"
                                >
                                    <Link :href="login()">Iniciar trámite</Link>
                                </Button>
                            </template>
                            <template v-else>
                                <p class="py-2 text-center text-gray-500">
                                    Este trámite debe realizarse de forma presencial
                                </p>
                            </template>
                        </div>

                        <!-- Unidad responsable -->
                        <div class="rounded-xl bg-gray-50 p-6">
                            <h3 class="mb-3 font-bold text-gray-900">Unidad responsable</h3>
                            <p class="text-gray-700">{{ department.name }}</p>
                            <Link
                                :href="route.department(department.slug)"
                                class="mt-2 inline-block text-[#06048c] hover:underline"
                            >
                                Ver todos los trámites de
                                {{ department.name }} →
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </PortalLayout>
</template>
