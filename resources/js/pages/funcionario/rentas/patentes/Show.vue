<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import { index } from '@/routes/funcionario/rentas/patentes';
import { form } from '@/routes/funcionario/rentas/patentes/approve';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    CheckCircle2,
    Clock,
    FileText,
    User,
    XCircle,
} from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps<{
    patentRequest: {
        id: number;
        rut: string;
        business_address: string;
        business_activity: string;
        state: string;
        code: string;
        observations: string | null;
        created_at: string;
        reviewed_at: string;
        user: {
            id: number;
            name: string;
            email: string;
            phone_number?: string;
            address?: string;
        };
        reviewer?: {
            id: number;
            name: string;
            email: string;
        };
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Solicitudes de Patente',
        href: index().url,
    },
    {
        title: `Solicitud #${props.patentRequest.code}`,
        href: '',
    },
];

const getStatusConfig = (status: string) => {
    const configs: Record<
        string,
        {
            variant: 'default' | 'destructive' | 'secondary';
            label: string;
            icon: any;
            color: string;
        }
    > = {
        pending: {
            variant: 'secondary',
            label: 'Pendiente',
            icon: Clock,
            color: 'text-gray-600',
        },
        approved: {
            variant: 'default',
            label: 'Aprobado',
            icon: CheckCircle2,
            color: 'text-green-600',
        },
        rejected: {
            variant: 'destructive',
            label: 'Rechazado',
            icon: XCircle,
            color: 'text-red-600',
        },
        rejected_with_observations: {
            variant: 'destructive',
            label: 'Rechazado con Obs.',
            icon: XCircle,
            color: 'text-orange-600',
        },
    };
    return configs[status];
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-CL', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Estado para el formulario de rechazo con observaciones
const showRejectForm = ref(false);
const observations = ref('');
const isSubmitting = ref(false);

const submitRejection = () => {
    if (!observations.value.trim()) {
        return;
    }

    isSubmitting.value = true;

    router.post(
        `/funcionario/rentas/patentes/${props.patentRequest.code}/reject-with-observations`,
        {
            observations: observations.value.trim(),
        },
        {
            onSuccess: () => {
                showRejectForm.value = false;
                observations.value = '';
                // El toast se mostrará automáticamente desde AppLayout con el flash message del servidor
            },
            onError: (errors) => {
                console.error('Error al rechazar con observaciones:', errors);
            },
            onFinish: () => {
                isSubmitting.value = false;
            },
        },
    );
};

const cancelRejection = () => {
    showRejectForm.value = false;
    observations.value = '';
};

// Método para rechazo simple
const simpleReject = () => {
    router.post(
        `/funcionario/rentas/patentes/${props.patentRequest.code}/reject`,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {
                // El toast se mostrará automáticamente desde AppLayout con el flash message del servidor
            },
            onError: (errors) => {
                console.error('Error al rechazar:', errors);
            },
        },
    );
};
</script>

<template>
    <Head :title="`Solicitud - #${patentRequest.code}`" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-7xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="index().url">
                            <ArrowLeft class="h-5 w-5" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Solicitud de Patente #{{ patentRequest.code }}
                        </h1>
                        <p class="mt-1 text-gray-500">
                            Creada el {{ formatDate(patentRequest.created_at) }}
                        </p>
                    </div>
                </div>
                <Badge
                    :variant="getStatusConfig(patentRequest.state).variant"
                    class="gap-1.5 px-4 py-2 text-base"
                >
                    <component :is="getStatusConfig(patentRequest.state).icon" class="h-4 w-4" />
                    {{ getStatusConfig(patentRequest.state).label }}
                </Badge>
            </div>

            <div class="space-y-6">
                <!-- Grid principal para datos -->
                <div class="grid gap-8 lg:grid-cols-2">
                    <!-- Datos del Contribuyente -->
                    <Card class="shadow-sm transition-shadow hover:shadow-md">
                        <CardHeader class="pb-4">
                            <CardTitle class="flex items-center gap-2 text-lg">
                                <User class="h-5 w-5 text-gray-600" />
                                Datos del Contribuyente
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <dl class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">Nombre</dt>
                                    <dd class="font-semibold text-gray-900">
                                        {{ patentRequest.user.name }}
                                    </dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">Correo</dt>
                                    <dd class="font-semibold text-gray-900">
                                        {{ patentRequest.user.email }}
                                    </dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">Teléfono</dt>
                                    <dd class="font-semibold text-gray-900">
                                        {{ patentRequest.user.phone_number || 'Sin información' }}
                                    </dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">Dirección</dt>
                                    <dd class="font-semibold text-gray-900">
                                        {{ patentRequest.user.address || 'Sin información' }}
                                    </dd>
                                </div>
                            </dl>
                        </CardContent>
                    </Card>

                    <!-- Datos de la Solicitud -->
                    <Card
                        class="border-l-4 border-l-blue-500 bg-blue-50/30 shadow-sm transition-shadow hover:shadow-md"
                    >
                        <CardHeader class="pb-4">
                            <CardTitle class="flex items-center gap-2 text-lg text-blue-700">
                                <FileText class="h-5 w-5" />
                                Datos de la Solicitud
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="pt-0">
                            <dl class="grid grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">RUT</dt>
                                    <dd class="font-mono text-lg font-semibold">
                                        {{ patentRequest.rut }}
                                    </dd>
                                </div>
                                <div class="space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">
                                        Giro Comercial
                                    </dt>
                                    <dd class="font-semibold">
                                        {{ patentRequest.business_activity }}
                                    </dd>
                                </div>
                                <div class="col-span-2 space-y-1">
                                    <dt class="text-lg font-medium text-gray-500">
                                        Dirección del Negocio
                                    </dt>
                                    <dd class="font-semibold">
                                        {{ patentRequest.business_address }}
                                    </dd>
                                </div>
                            </dl>
                        </CardContent>
                    </Card>
                </div>

                <!-- Información de Revisión (ancho completo) -->
                <Card
                    v-if="patentRequest.reviewer"
                    class="shadow-sm transition-shadow hover:shadow-md"
                >
                    <CardHeader class="pb-4">
                        <CardTitle class="flex items-center gap-2 text-lg">
                            <CheckCircle2 class="h-5 w-5 text-green-600" />
                            Información de Revisión
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="pt-0">
                        <dl class="grid grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <dt class="text-lg font-medium text-gray-500">Funcionario</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ patentRequest.reviewer.name }}
                                </dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-lg font-medium text-gray-500">Correo</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ patentRequest.reviewer.email }}
                                </dd>
                            </div>
                            <div class="space-y-1">
                                <dt class="text-lg font-medium text-gray-500">Fecha de Revisión</dt>
                                <dd class="font-semibold text-gray-900">
                                    {{ formatDate(patentRequest.reviewed_at) }}
                                </dd>
                            </div>
                        </dl>
                    </CardContent>
                </Card>

                <!-- Observaciones (ancho completo) -->
                <Card
                    v-if="patentRequest.observations"
                    class="border-l-4 border-l-red-500 shadow-sm transition-shadow hover:shadow-md"
                >
                    <CardHeader class="pb-4">
                        <CardTitle class="flex items-center gap-2 text-lg text-red-600">
                            <XCircle class="h-5 w-5" />
                            Observaciones
                        </CardTitle>
                    </CardHeader>
                    <CardContent class="pt-0">
                        <div class="rounded-lg border border-red-200 bg-red-50 p-4">
                            <p class="whitespace-pre-wrap text-gray-700">
                                {{ patentRequest.observations }}
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Acciones -->
                <Card v-if="patentRequest.state === 'pending'">
                    <CardHeader>
                        <CardTitle>Acciones de Evaluación</CardTitle>
                        <p class="text-sm text-muted-foreground">
                            Selecciona una acción para esta solicitud de patente
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-4 md:grid-cols-3">
                            <!-- Aprobar -->
                            <Button
                                as-child
                                class="group h-auto flex-col gap-3 border border-emerald-300 bg-emerald-100 p-4 text-emerald-700 shadow-md hover:bg-emerald-200"
                            >
                                <Link
                                    :href="form(patentRequest.code)"
                                    class="flex flex-col items-center gap-2"
                                >
                                    <div
                                        class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-200 transition-colors group-hover:bg-emerald-300"
                                    >
                                        <CheckCircle2 class="h-8 w-8 text-emerald-700" />
                                    </div>
                                    <div class="text-center">
                                        <p class="font-semibold">Aprobar</p>
                                        <p class="text-xs opacity-80">Aprobar solicitud</p>
                                    </div>
                                </Link>
                            </Button>

                            <!-- Rechazar -->
                            <Button
                                @click="simpleReject"
                                class="group h-auto flex-col gap-3 border border-red-300 bg-red-50 p-4 text-red-700 shadow-md hover:bg-red-100"
                            >
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-red-100 transition-colors group-hover:bg-red-200"
                                >
                                    <XCircle class="h-8 w-8 text-red-700" />
                                </div>
                                <div class="text-center">
                                    <p class="font-semibold">Rechazar</p>
                                    <p class="text-xs opacity-80">Rechazar solicitud</p>
                                </div>
                            </Button>

                            <!-- Rechazar con observaciones -->
                            <Button
                                @click="showRejectForm = true"
                                class="group h-auto flex-col gap-3 border border-orange-300 bg-orange-50 p-4 text-orange-700 shadow-md hover:bg-orange-100"
                            >
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-orange-100 transition-colors group-hover:bg-orange-200"
                                >
                                    <AlertCircle class="h-8 w-8 text-orange-700" />
                                </div>
                                <div class="text-center">
                                    <p class="font-semibold">Rechazar con Observaciones</p>
                                    <p class="text-xs opacity-80">Explicar por qué no se aprueba</p>
                                </div>
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Formulario de Rechazo con Observaciones -->
                <Card v-if="showRejectForm" class="border-l-4 border-l-orange-500 bg-orange-50/30">
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2 text-orange-700">
                            <AlertCircle class="h-5 w-5" />
                            Rechazar con Observaciones
                        </CardTitle>
                        <p class="text-sm text-orange-600">
                            Proporcione una explicación clara sobre por qué la solicitud no puede
                            ser aprobada en este momento
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <Textarea
                                v-model="observations"
                                placeholder="Ej:&#10;&#10;• La solicitud no cumple con los requisitos de documentación exigidos por la ordenanza municipal.&#10;• El giro comercial solicitado no corresponde al clasificado en el registro de actividades económicas.&#10;• La dirección comercial no cuenta con los permisos de habitabilidad correspondientes.&#10;&#10;Por este motivo, la solicitud no puede ser aprobada hasta regularizar la situación mencionada."
                                class="min-h-[150px]"
                            />
                            <p class="text-sm text-gray-500">
                                {{ observations.length }} caracteres
                            </p>
                            <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                                <Button
                                    variant="outline"
                                    @click="cancelRejection"
                                    :disabled="isSubmitting"
                                >
                                    Cancelar
                                </Button>
                                <Button
                                    variant="destructive"
                                    @click="submitRejection"
                                    :disabled="!observations.trim() || isSubmitting"
                                    class="gap-2"
                                >
                                    <XCircle class="h-4 w-4" />
                                    {{
                                        isSubmitting
                                            ? 'Enviando Observaciones...'
                                            : 'Rechazar con Observaciones'
                                    }}
                                </Button>
                            </div>
                            <p class="text-sm text-red-600">
                                Esta acción notificará al solicitante con las razones por las que su
                                solicitud no fue aprobada.
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
