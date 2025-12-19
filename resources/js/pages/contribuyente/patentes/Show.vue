<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/contribuyente/patentes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import {
    AlertCircle,
    AlertTriangle,
    ArrowLeft,
    Calendar,
    CheckCircle2,
    Clock,
    Download,
    ExternalLink,
    FileDown,
    FileText,
    Mail,
    MapPin,
    User,
    XCircle,
} from 'lucide-vue-next';

interface PatentRequest {
    id: number;
    code: string;
    rut: string;
    business_address: string;
    business_activity: string;
    state: 'pending' | 'approved' | 'rejected' | 'rejected_with_observations';
    reviewed_by: number | null;
    reviewed_at: string | null;
    history: PatentRequestHistory[];
    forms: PatentRequestForm[];
    requirements: PatentRequestRequirement[];
    created_at: string;
    updated_at: string;
    user: User;
    observations: string | null;
    reviewer: User | null;
}

interface PatentRequestHistory {
    id: number;
    patent_request_id: number;
    action: 'created' | 'approved' | 'rejected' | 'rejected_with_observations';
    observations: string | null;
    created_at: string;
    user: User;
}

interface PatentForm {
    id: number;
    name: string;
    description: string;
    template_file: string | null;
    created_by: number;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    phone_number: string | null;
    address: string | null;
}

interface PatentRequestForm {
    id: number;
    patent_request_id: number;
    patent_form_id: number;
    patent_form: PatentForm;
    file: string | null;
    file_name: string | null;
    attached_by: number;
    created_at: string;
    updated_at: string;
}

interface PatentRequirement {
    id: number;
    name: string;
    description: string;
    where_to_obtain: string;
    obtain_address?: string;
    info_url?: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface PatentRequestRequirement {
    id: number;
    patent_request_id: number;
    patent_requirement_id: number;
    observations: string | null;
    patent_requirement: PatentRequirement;
    attached_by: number;
    created_at: string;
}

const props = defineProps<{
    patentRequest: PatentRequest;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Mis Patentes',
        href: '/contribuyente/patentes',
    },
    {
        title: `Solicitud ${props.patentRequest.code}`,
        href: '',
    },
];

const getStatusBadge = (
    status: 'pending' | 'approved' | 'rejected' | 'rejected_with_observations' | 'created',
) => {
    const configs = {
        pending: {
            variant: 'secondary' as const,
            label: 'Pendiente',
            icon: Clock,
            color: 'text-amber-600',
            bgColor: 'bg-amber-50',
            borderColor: 'border-amber-400',
        },
        approved: {
            variant: 'default' as const,
            label: 'Aprobada',
            icon: CheckCircle2,
            color: 'text-emerald-600',
            bgColor: 'bg-emerald-50',
            borderColor: 'border-emerald-400',
        },
        rejected: {
            variant: 'destructive' as const,
            label: 'Rechazada',
            icon: XCircle,
            color: 'text-destructive',
            bgColor: 'bg-destructive/10',
            borderColor: 'border-destructive',
        },
        rejected_with_observations: {
            variant: 'destructive' as const,
            label: 'Rechazada con Observaciones',
            icon: AlertTriangle,
            color: 'text-orange-600',
            bgColor: 'bg-orange-50',
            borderColor: 'border-orange-400',
        },
        created: {
            variant: 'secondary' as const,
            label: 'Creada',
            icon: Clock,
            color: 'text-amber-600',
            bgColor: 'bg-amber-50',
            borderColor: 'border-amber-400',
        },
    };
    return configs[status] || configs.pending;
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-CL', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const downloadForm = (form: any) => {
    // Crear URL para descargar el archivo del formulario
    const url = `/storage/${form.patent_form.template_file}`;

    // Crear un enlace temporal para descargar
    const link = document.createElement('a');
    link.href = url;
    link.download = form.patent_form.name || 'formulario.pdf';
    link.target = '_blank'; // Abrir en nueva pestaña para evitar descargas de HTML
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head :title="`Solicitud de Patente - #${props.patentRequest.code}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-7xl space-y-6 p-6 md:p-8">
            <!-- Header con estado -->
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="index().url">
                            <ArrowLeft class="h-5 w-5" />
                        </Link>
                    </Button>
                    <h1 class="text-3xl font-bold text-gray-900">Solicitud de Patente</h1>
                </div>
                <div>
                    <Badge
                        :variant="
                            getStatusBadge(
                                props.patentRequest.state as
                                    | 'pending'
                                    | 'approved'
                                    | 'rejected'
                                    | 'rejected_with_observations',
                            ).variant
                        "
                        :class="[
                            'gap-2 border-2 px-4 py-2 text-base',
                            getStatusBadge(
                                props.patentRequest.state as
                                    | 'pending'
                                    | 'approved'
                                    | 'rejected'
                                    | 'rejected_with_observations',
                            ).bgColor,
                            getStatusBadge(
                                props.patentRequest.state as
                                    | 'pending'
                                    | 'approved'
                                    | 'rejected'
                                    | 'rejected_with_observations',
                            ).color,
                            getStatusBadge(
                                props.patentRequest.state as
                                    | 'pending'
                                    | 'approved'
                                    | 'rejected'
                                    | 'rejected_with_observations',
                            ).borderColor,
                        ]"
                    >
                        <component
                            :is="
                                getStatusBadge(
                                    props.patentRequest.state as
                                        | 'pending'
                                        | 'approved'
                                        | 'rejected'
                                        | 'rejected_with_observations',
                                ).icon
                            "
                            class="h-5 w-5"
                        />
                        {{
                            getStatusBadge(
                                props.patentRequest.state as
                                    | 'pending'
                                    | 'approved'
                                    | 'rejected'
                                    | 'rejected_with_observations',
                            ).label
                        }}
                    </Badge>
                </div>
            </div>

            <!-- Alerta de observaciones -->
            <div
                v-if="props.patentRequest.state === 'rejected_with_observations'"
                class="rounded-lg border border-red-200 bg-red-50 p-4"
            >
                <div class="flex gap-3">
                    <AlertCircle class="h-5 w-5 flex-shrink-0 text-red-600" />
                    <div>
                        <h3 class="font-semibold text-red-900">Tu solicitud tiene observaciones</h3>
                        <p class="mt-1 text-red-700">
                            Revisa las observaciones en la sección de evaluación.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Alerta de trámite formal -->
            <div v-if="false" class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex gap-3">
                    <CheckCircle2 class="h-5 w-5 flex-shrink-0 text-blue-600" />
                    <div>
                        <h3 class="font-semibold text-blue-900">
                            Tu solicitud fue aprobada en la pre-evaluación
                        </h3>
                        <p class="mt-1 text-blue-700">
                            Debes descargar los formularios, completarlos y traer los documentos
                            físicamente a la municipalidad.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Datos del Contribuyente -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <User class="h-5 w-5" />
                        Mis Datos
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <dl class="grid grid-cols-2 gap-4">
                        <div>
                            <dt class="font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 font-medium">
                                {{ props.patentRequest.user.name }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 font-medium">
                                {{ props.patentRequest.user.email }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 font-medium">
                                {{ props.patentRequest.user.phone_number || 'Sin información' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 font-medium">
                                {{ props.patentRequest.user.address || 'Sin información' }}
                            </dd>
                        </div>
                    </dl>
                </CardContent>
            </Card>

            <!-- Datos de la solicitud -->
            <Card>
                <CardHeader>
                    <CardTitle>Datos de la Solicitud</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-6 md:grid-cols-2">
                        <dl class="space-y-4">
                            <div>
                                <dt class="font-medium text-gray-500">Código de Seguimiento</dt>
                                <dd class="mt-1 font-mono">
                                    {{ props.patentRequest.code }}
                                </dd>
                            </div>
                            <Separator />
                            <div>
                                <dt class="font-medium text-gray-500">RUT</dt>
                                <dd class="mt-1 font-medium">
                                    {{ props.patentRequest.rut }}
                                </dd>
                            </div>
                            <Separator />
                            <div>
                                <dt class="font-medium text-gray-500">Dirección Comercial</dt>
                                <dd class="mt-1 font-medium">
                                    {{ props.patentRequest.business_address }}
                                </dd>
                            </div>
                        </dl>
                        <dl class="space-y-4">
                            <div>
                                <dt class="font-medium text-gray-500">Giro Comercial</dt>
                                <dd class="mt-1 font-medium">
                                    {{ props.patentRequest.business_activity }}
                                </dd>
                            </div>
                            <Separator />
                            <div>
                                <dt class="font-medium text-gray-500">Fecha de Envío</dt>
                                <dd class="mt-1 font-medium">
                                    {{ formatDate(props.patentRequest.created_at) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </CardContent>
            </Card>

            <!-- Evaluación -->
            <Card v-if="props.patentRequest.reviewer">
                <CardHeader>
                    <CardTitle>Resultado de Evaluación</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="mb-4 space-y-2">
                        <p class="flex items-center gap-2 text-gray-500">
                            <User class="h-4 w-4" />
                            Evaluado por:
                            <span class="font-medium text-gray-900">{{
                                props.patentRequest.reviewer.name
                            }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-gray-500">
                            <Mail class="h-4 w-4" />
                            Correo del evaluador:
                            <span class="font-medium text-gray-900">{{
                                props.patentRequest.reviewer.email
                            }}</span>
                        </p>
                        <p class="flex items-center gap-2 text-gray-500">
                            <Calendar class="h-4 w-4" />
                            Fecha de Revisión:
                            <span class="font-medium text-gray-900">{{
                                formatDate(props.patentRequest.reviewed_at || '')
                            }}</span>
                        </p>
                        <p class="text-gray-500">
                            Resultado:
                            <Badge
                                :variant="
                                    props.patentRequest.state === 'approved'
                                        ? 'default'
                                        : props.patentRequest.state === 'rejected'
                                          ? 'destructive'
                                          : 'destructive'
                                "
                                class="ml-2"
                            >
                                {{
                                    props.patentRequest.state === 'approved'
                                        ? 'APROBADO'
                                        : props.patentRequest.state === 'rejected'
                                          ? 'RECHAZADO'
                                          : 'RECHAZADO CON OBSERVACIONES'
                                }}
                            </Badge>
                        </p>
                    </div>

                    <!-- Observaciones -->
                    <div v-if="props.patentRequest.observations" class="mt-4">
                        <h4 class="mb-2 font-medium text-gray-900">Observaciones:</h4>
                        <p class="rounded-lg bg-gray-50 p-3 text-gray-700">
                            {{ props.patentRequest.observations }}
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Documentación requerida -->
            <Card v-if="props.patentRequest.state === 'approved'">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2"> Documentación Requerida </CardTitle>
                </CardHeader>
                <CardContent>
                    <!-- Hay documentación requerida -->
                    <div
                        v-if="
                            props.patentRequest.forms?.length > 0 ||
                            props.patentRequest.requirements?.length > 0
                        "
                    >
                        <div class="mb-6 rounded-lg border-l-4 border-l-blue-500 bg-blue-50 p-4">
                            <span class="text-lg font-semibold text-blue-900">
                                Para completar tu solicitud con el código
                                <span class="font-bold text-blue-700"
                                    >#{{ props.patentRequest.code }}</span
                                >, debes presentar los siguientes documentos en la Oficina de Rentas
                                Municipales
                            </span>
                            <div class="mt-2 flex items-center gap-2">
                                <MapPin class="h-5 w-5 text-blue-700" />
                                <span class="text-base font-bold text-blue-800 underline">
                                    Bernardo O'Higgins # 749
                                </span>
                            </div>
                        </div>
                        <div class="space-y-6">
                            <!-- Formularios -->
                            <div v-if="props.patentRequest.forms?.length > 0">
                                <h4
                                    class="mb-3 flex items-center gap-2 font-semibold text-gray-900"
                                >
                                    <FileDown class="h-4 w-4" />
                                    Formularios Tipo
                                </h4>
                                <p class="mb-4 text-sm text-gray-600">
                                    Debe descargar, completar y firmar estos documentos de manera
                                    responsable antes de presentarlos en la municipalidad.
                                </p>
                                <div class="space-y-3">
                                    <div
                                        v-for="form in props.patentRequest.forms"
                                        :key="form.id"
                                        class="flex items-center justify-between rounded-lg border p-3 hover:bg-gray-50"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100"
                                            >
                                                <FileText class="h-4 w-4 text-gray-600" />
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-900">
                                                    {{
                                                        form.patent_form.name ||
                                                        'Formulario sin nombre'
                                                    }}
                                                </h5>
                                                <p class="text-sm text-gray-500">
                                                    {{
                                                        form.patent_form.description ||
                                                        'Sin descripción'
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            @click="downloadForm(form)"
                                        >
                                            <Download class="mr-2 h-4 w-4" />
                                            Descargar
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <!-- Requerimientos -->
                            <div v-if="props.patentRequest.requirements?.length > 0">
                                <h4
                                    class="mb-3 flex items-center gap-2 font-semibold text-gray-900"
                                >
                                    <ExternalLink class="h-4 w-4" />
                                    Requerimientos
                                </h4>
                                <div class="space-y-3">
                                    <div
                                        v-for="req in props.patentRequest.requirements"
                                        :key="req.id"
                                        class="rounded-lg border p-3"
                                    >
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100"
                                            >
                                                <AlertCircle class="h-4 w-4 text-blue-600" />
                                            </div>
                                            <div class="flex-1">
                                                <h5 class="mb-1 font-medium text-gray-900">
                                                    {{
                                                        req.patent_requirement?.name ||
                                                        'Requerimiento sin nombre'
                                                    }}
                                                </h5>
                                                <p class="mb-2 text-sm text-gray-600">
                                                    {{
                                                        req.patent_requirement?.description ||
                                                        'Sin descripción'
                                                    }}
                                                </p>
                                                <div
                                                    class="flex items-center gap-2 text-sm text-blue-600"
                                                >
                                                    <MapPin class="h-3 w-3" />
                                                    <span
                                                        >Obtener en:
                                                        {{
                                                            req.patent_requirement
                                                                ?.where_to_obtain ||
                                                            'No especificado'
                                                        }}</span
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- No hay documentación requerida -->
                    <div v-else class="py-12 text-center">
                        <div
                            class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100"
                        >
                            <CheckCircle2 class="h-10 w-10 text-gray-600" />
                        </div>
                        <h3 class="mb-4 text-2xl font-bold text-gray-900">
                            ¡No se requiere documentación adicional!
                        </h3>
                        <div class="mx-auto mb-8 max-w-2xl">
                            <p class="text-lg leading-relaxed text-gray-700">
                                Tu solicitud ha sido aprobada sin requerir documentos adicionales.
                            </p>
                            <p class="mt-2 text-lg leading-relaxed text-gray-700">
                                Para continuar con el trámite con el código
                                <span class="font-bold text-blue-600"
                                    >#{{ props.patentRequest.code }}</span
                                >, dirígete presencialmente a la dirección de Rentas Municipales:
                            </p>
                            <div
                                class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-50 px-4 py-3"
                            >
                                <MapPin class="h-5 w-5 text-blue-700" />
                                <span class="font-bold text-blue-800 underline">
                                    Bernardo O'Higgins # 749
                                </span>
                            </div>
                        </div>
                        <Button size="lg" as-child>
                            <Link :href="index().url">
                                <ArrowLeft class="mr-2 h-5 w-5" />
                                Volver a mis solicitudes
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Historial de cambios -->
            <Card v-if="props.patentRequest.history.length > 0">
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <Calendar class="h-5 w-5" />
                        Historial de la Solicitud
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div
                            v-for="item in props.patentRequest.history"
                            :key="item.id"
                            class="relative flex gap-4"
                        >
                            <!-- Línea de tiempo -->
                            <div class="absolute top-8 bottom-0 left-4 w-px bg-gray-200" />

                            <!-- Icono del evento -->
                            <div
                                class="relative z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 border-white bg-white shadow-sm"
                            >
                                <component
                                    :is="getStatusBadge(item.action).icon"
                                    class="h-4 w-4"
                                    :class="getStatusBadge(item.action).color"
                                />
                            </div>

                            <!-- Contenido del evento -->
                            <div class="flex-1 pb-8">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <Badge
                                                :variant="getStatusBadge(item.action).variant"
                                                class="text-xs"
                                            >
                                                {{ getStatusBadge(item.action).label }}
                                            </Badge>
                                            <span class="text-sm text-gray-500">
                                                Por {{ item.user.name }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            {{ formatDate(item.created_at) }}
                                        </p>
                                        <p
                                            v-if="item.observations"
                                            class="mt-2 rounded-lg bg-gray-50 p-3 text-sm text-gray-600"
                                        >
                                            {{ item.observations }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
