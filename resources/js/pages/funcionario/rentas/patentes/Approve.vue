<script setup lang="ts">
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/components/ui/accordion';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { approve, approveWithDocuments, index } from '@/routes/funcionario/rentas/patentes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    CheckCircle,
    CheckCircle2,
    Clock,
    FileText,
    Upload,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import { toast } from 'vue-sonner';

interface PatentRequest {
    id: number;
    code: string;
    rut: string;
    business_address: string;
    business_activity: string;
    contact_email: string;
    state: string;
    created_at: string;
    user: {
        name: string;
        email: string;
        phone: string | null;
    };
}

interface PatentForm {
    id: number;
    name: string;
    description: string | null;
    template_file: string;
}

interface PatentRequirement {
    id: number;
    code: string;
    name: string;
    description: string | null;
    category: string;
    where_to_obtain: string;
    obtain_address: string | null;
    obtain_phone: string | null;
    info_url: string | null;
    validity_days: number | null;
}

const props = defineProps<{
    patentRequest: PatentRequest;
    forms: PatentForm[];
    requirements: PatentRequirement[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Solicitudes', href: index().url },
    { title: 'Aprobar solicitud', href: '' },
];

const selectedForms = ref<number[]>([]);
const selectedRequirements = ref<number[]>([]);
const showDocumentSelection = ref(false);

// Agrupar requerimientos por categoría
const requirementsByCategory = computed(() => {
    const grouped: Record<string, PatentRequirement[]> = {};

    props.requirements.forEach((req) => {
        if (!grouped[req.category]) {
            grouped[req.category] = [];
        }
        grouped[req.category].push(req);
    });

    return grouped;
});

// Nombres de categorías en español
const categoryNames: Record<string, string> = {
    municipal: 'Municipal',
    sanitario: 'Sanitario',
    legal: 'Legal',
    profesional: 'Profesional',
    financiero: 'Financiero',
    transporte: 'Transporte',
    educacion: 'Educación',
    seguridad: 'Seguridad',
    otros: 'Otros',
};

const form = useForm({
    forms: [] as number[],
    requirements: [] as number[],
});

// Función para obtener la configuración del estado
const getStatusBadge = (state: string) => {
    const config: Record<
        string,
        { variant: 'default' | 'destructive' | 'outline' | 'secondary'; label: string; icon: any }
    > = {
        pending: {
            variant: 'secondary',
            label: 'Pendiente',
            icon: Clock,
        },
        approved: {
            variant: 'default',
            label: 'Aprobado',
            icon: CheckCircle2,
        },
        rejected: {
            variant: 'destructive',
            label: 'Rechazado',
            icon: XCircle,
        },
        rejected_with_observations: {
            variant: 'destructive',
            label: 'Con observaciones',
            icon: AlertCircle,
        },
    };
    return config[state] || config.pending;
};

function toggleFormSelection(formId: number) {
    const index = selectedForms.value.indexOf(formId);
    if (index > -1) {
        selectedForms.value.splice(index, 1);
    } else {
        selectedForms.value.push(formId);
    }
}

function toggleRequirementSelection(requirementId: number) {
    const index = selectedRequirements.value.indexOf(requirementId);
    if (index > -1) {
        selectedRequirements.value.splice(index, 1);
    } else {
        selectedRequirements.value.push(requirementId);
    }
}

function approveWithoutDocuments() {
    router.post(
        approve(props.patentRequest.code).url,
        {},
        {
            onError: () => {
                toast.error('Error al aprobar la solicitud.');
            },
        },
    );
}

function approveWithSelectedDocuments() {
    form.forms = selectedForms.value;
    form.requirements = selectedRequirements.value;

    router.post(approveWithDocuments(props.patentRequest.code).url, form.data(), {
        onError: () => {
            toast.error('Error al aprobar la solicitud.');
        },
    });
}

function formatDate(date: string) {
    return new Date(date).toLocaleDateString('es-CL', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
}
</script>

<template>
    <Head title="Aprobar Solicitud" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-5xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link :href="index()">
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Aprobar Solicitud</h1>
                    <p class="text-sm text-gray-500">Código: {{ patentRequest.code }}</p>
                </div>
            </div>

            <!-- Datos del Solicitante -->
            <Card class="shadow-sm transition-shadow hover:shadow-md">
                <CardHeader class="pb-4">
                    <CardTitle class="flex items-center gap-2 text-lg">
                        <User class="h-5 w-5 text-gray-600" />
                        Datos del Solicitante
                    </CardTitle>
                </CardHeader>
                <CardContent class="pt-0">
                    <dl class="grid grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">Nombre</dt>
                            <dd class="font-semibold text-gray-900">
                                {{ props.patentRequest.user.name }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">Email</dt>
                            <dd class="font-semibold text-gray-900">
                                {{ props.patentRequest.user.email }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">RUT</dt>
                            <dd class="font-mono text-lg font-semibold">
                                {{ props.patentRequest.rut }}
                            </dd>
                        </div>
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">Teléfono</dt>
                            <dd class="font-semibold text-gray-900">
                                {{ props.patentRequest.user.phone || 'No registrado' }}
                            </dd>
                        </div>
                    </dl>
                </CardContent>
            </Card>

            <!-- Datos de la Patente -->
            <Card
                class="border-l-4 border-l-blue-500 bg-blue-50/30 shadow-sm transition-shadow hover:shadow-md"
            >
                <CardHeader class="pb-4">
                    <CardTitle class="flex items-center gap-2 text-lg text-blue-700">
                        <FileText class="h-5 w-5" />
                        Datos de la Patente
                    </CardTitle>
                </CardHeader>
                <CardContent class="pt-0">
                    <dl class="space-y-4">
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">Dirección Comercial</dt>
                            <dd class="font-semibold text-gray-900">
                                {{ props.patentRequest.business_address }}
                            </dd>
                        </div>
                        <Separator />
                        <div class="space-y-1">
                            <dt class="text-lg font-medium text-gray-500">Giro Comercial</dt>
                            <dd class="font-semibold text-gray-900">
                                {{ props.patentRequest.business_activity }}
                            </dd>
                        </div>
                        <Separator />
                        <div class="flex items-center justify-between">
                            <div class="space-y-1">
                                <dt class="text-lg font-medium text-gray-500">
                                    Fecha de Solicitud
                                </dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ formatDate(props.patentRequest.created_at) }}
                                </dd>
                            </div>
                            <Badge
                                :variant="getStatusBadge(props.patentRequest.state).variant"
                                class="gap-1.5"
                            >
                                <component
                                    :is="getStatusBadge(props.patentRequest.state).icon"
                                    class="h-3 w-3"
                                />
                                {{ getStatusBadge(props.patentRequest.state).label }}
                            </Badge>
                        </div>
                    </dl>
                </CardContent>
            </Card>

            <!-- Selección de Documentos -->
            <Card v-if="!showDocumentSelection">
                <CardHeader>
                    <CardTitle>¿Desea requerir documentos para esta solicitud?</CardTitle>
                    <CardDescription>
                        Puede aprobar la solicitud directamente o seleccionar formularios y
                        documentos que el solicitante debe presentar.
                    </CardDescription>
                </CardHeader>
                <CardContent class="space-y-4">
                    <Button
                        @click="showDocumentSelection = true"
                        class="w-full bg-blue-600 hover:bg-blue-700"
                    >
                        <Upload class="mr-2 h-4 w-4" />
                        Seleccionar Documentos Requeridos
                    </Button>
                    <Button
                        variant="outline"
                        @click="approveWithoutDocuments"
                        class="w-full border-green-600 text-green-600 hover:border-green-700 hover:bg-green-50 hover:text-green-700"
                    >
                        <CheckCircle class="mr-2 h-4 w-4" />
                        Aprobar sin Documentos
                    </Button>
                </CardContent>
            </Card>

            <!-- Formularios Disponibles -->
            <Card v-if="showDocumentSelection && forms.length > 0">
                <Accordion type="single" collapsible class="w-full">
                    <AccordionItem value="forms">
                        <AccordionTrigger class="px-6 hover:no-underline">
                            <div class="text-left">
                                <h3 class="text-lg font-semibold">Formularios Requeridos</h3>
                                <p class="text-sm text-gray-500">
                                    Seleccione los formularios que el solicitante debe descargar y
                                    completar.
                                </p>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent>
                            <CardContent class="pt-0">
                                <div class="space-y-3">
                                    <div
                                        v-for="form in forms"
                                        :key="form.id"
                                        class="flex items-start space-x-3 rounded-lg border p-3 hover:bg-gray-50"
                                    >
                                        <Checkbox
                                            :id="`form-${form.id}`"
                                            :model-value="selectedForms.includes(form.id)"
                                            @update:model-value="toggleFormSelection(form.id)"
                                        />
                                        <div class="flex-1">
                                            <Label
                                                :for="`form-${form.id}`"
                                                class="text-sm font-medium"
                                            >
                                                {{ form.name }}
                                            </Label>
                                            <p
                                                v-if="form.description"
                                                class="mt-1 text-xs text-gray-500"
                                            >
                                                {{ form.description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </Card>

            <!-- Requerimientos de Documentos por Categoría -->
            <Card v-if="showDocumentSelection && requirements.length > 0">
                <Accordion type="single" collapsible class="w-full">
                    <AccordionItem value="requirements">
                        <AccordionTrigger class="px-6 hover:no-underline">
                            <div class="text-left">
                                <h3 class="text-lg font-semibold">Requerimientos de Documentos</h3>
                                <p class="text-sm text-gray-500">
                                    Seleccione los documentos que el solicitante debe presentar.
                                </p>
                            </div>
                        </AccordionTrigger>
                        <AccordionContent>
                            <CardContent class="pt-0">
                                <div class="space-y-4">
                                    <Accordion type="multiple" collapsible class="w-full">
                                        <AccordionItem
                                            v-for="(
                                                requirements, category
                                            ) in requirementsByCategory"
                                            :key="category"
                                            :value="category"
                                        >
                                            <AccordionTrigger class="px-4 hover:no-underline">
                                                <h4 class="text-sm font-semibold text-gray-700">
                                                    {{ categoryNames[category] || category }}
                                                </h4>
                                            </AccordionTrigger>
                                            <AccordionContent>
                                                <div class="space-y-2">
                                                    <div
                                                        v-for="req in requirements"
                                                        :key="req.id"
                                                        class="flex items-start space-x-3 rounded-lg border p-3 hover:bg-gray-50"
                                                    >
                                                        <Checkbox
                                                            :id="`req-${req.id}`"
                                                            :model-value="
                                                                selectedRequirements.includes(
                                                                    req.id,
                                                                )
                                                            "
                                                            @update:model-value="
                                                                toggleRequirementSelection(req.id)
                                                            "
                                                        />
                                                        <div class="flex-1">
                                                            <Label
                                                                :for="`req-${req.id}`"
                                                                class="text-sm font-medium"
                                                            >
                                                                {{ req.name }}
                                                            </Label>
                                                            <p
                                                                v-if="req.description"
                                                                class="mt-1 text-xs text-gray-500"
                                                            >
                                                                {{ req.description }}
                                                            </p>
                                                            <p class="mt-1 text-xs text-gray-400">
                                                                <strong>Dónde obtener:</strong>
                                                                {{ req.where_to_obtain }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </AccordionContent>
                                        </AccordionItem>
                                    </Accordion>
                                </div>
                            </CardContent>
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </Card>

            <!-- Botones de Acción -->
            <div v-if="showDocumentSelection" class="flex justify-end gap-4">
                <Button
                    variant="outline"
                    @click="showDocumentSelection = false"
                    class="border-gray-400 hover:bg-gray-50"
                >
                    Volver
                </Button>
                <Button
                    @click="approveWithSelectedDocuments"
                    :disabled="form.processing"
                    class="bg-blue-600 hover:bg-blue-700"
                >
                    <CheckCircle class="mr-2 h-4 w-4" />
                    {{ form.processing ? 'Procesando...' : 'Aprobar y Requerir Documentos' }}
                </Button>
            </div>
            <!-- Pagination Controls -->
        </div>
    </AppLayout>
</template>
