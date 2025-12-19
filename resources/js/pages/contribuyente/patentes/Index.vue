<script setup lang="ts">
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, show } from '@/routes/contribuyente/patentes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowRight,
    CheckCircle2,
    Clock,
    FileText,
    PlayCircle,
    Plus,
    XCircle,
} from 'lucide-vue-next';
import { ref } from 'vue';

interface PatentRequest {
    id: number;
    code: string;
    rut: string;
    business_address: string;
    business_activity: string;
    state: 'pending' | 'approved' | 'rejected' | 'rejected_with_observations';
    created_at: string;
}

interface PaginationResponse<T> {
    data: T[];
    per_page: number;
    total: number;
    current_page: number;
    last_page: number;
}

defineProps<{
    requests: PaginationResponse<PatentRequest>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Solicitudes de Patente',
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
            bgColor: string;
            borderColor: string;
        }
    > = {
        pending: {
            variant: 'secondary',
            label: 'Pendiente',
            icon: Clock,
            color: 'text-amber-600',
            bgColor: 'bg-amber-50',
            borderColor: 'border-amber-400',
        },
        approved: {
            variant: 'default',
            label: 'Aprobado',
            icon: CheckCircle2,
            color: 'text-emerald-600',
            bgColor: 'bg-emerald-50',
            borderColor: 'border-emerald-400',
        },
        rejected: {
            variant: 'destructive',
            label: 'Rechazado',
            icon: XCircle,
            color: 'text-destructive',
            bgColor: 'bg-destructive/10',
            borderColor: 'border-destructive',
        },
        rejected_with_observations: {
            variant: 'destructive',
            label: 'Con observaciones',
            icon: AlertCircle,
            color: 'text-orange-600',
            bgColor: 'bg-orange-50',
            borderColor: 'border-orange-400',
        },
    };
    return configs[status];
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-CL', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    });
};

// Lógica de búsqueda simple
const searchCode = ref('');

const performSearch = () => {
    const params: any = {};
    if (searchCode.value.trim()) {
        params.code = searchCode.value.trim();
    }

    router.get(index().url, params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearSearch = () => {
    searchCode.value = '';
    performSearch();
};
</script>

<template>
    <Head title="Solicitudes de Patente" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-7xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Solicitudes de Patente</h1>
                    <p class="mt-1 text-gray-500">Gestiona tus trámites de patentes municipales</p>
                </div>
                <Button as-child>
                    <Link :href="create().url">
                        <Plus class="mr-2 h-4 w-4" />
                        Nueva Solicitud
                    </Link>
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card>
                    <CardContent>
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-gray-100 p-2">
                                <Clock class="h-5 w-5 text-gray-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ requests.data.filter((r) => r.state === 'pending').length }}
                                </p>
                                <p class="text-xs text-gray-500">Pendientes</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardContent>
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-green-100 p-2">
                                <CheckCircle2 class="h-5 w-5 text-green-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ requests.data.filter((r) => r.state === 'approved').length }}
                                </p>
                                <p class="text-xs text-gray-500">Aprobadas</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardContent>
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-red-100 p-2">
                                <XCircle class="h-5 w-5 text-red-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ requests.data.filter((r) => r.state === 'rejected').length }}
                                </p>
                                <p class="text-xs text-gray-500">Rechazadas</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardContent>
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg bg-orange-100 p-2">
                                <AlertCircle class="h-5 w-5 text-orange-600" />
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{
                                        requests.data.filter(
                                            (r) => r.state === 'rejected_with_observations',
                                        ).length
                                    }}
                                </p>
                                <p class="text-xs text-gray-500">Con observaciones</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Lista de solicitudes -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <CardTitle class="text-lg">Todas las Solicitudes</CardTitle>
                        <div class="flex max-w-sm gap-2">
                            <Input
                                id="search-code"
                                name="search-code"
                                placeholder="Buscar por código..."
                                v-model="searchCode"
                                class="flex-1"
                            />
                            <Button type="button" @click="performSearch"> Buscar </Button>
                            <Button
                                v-if="searchCode"
                                variant="outline"
                                type="button"
                                @click="clearSearch"
                            >
                                Limpiar
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div v-if="requests.data.length > 0" class="flex flex-col">
                        <template v-for="(req, index) in requests.data" :key="req.id">
                            <div
                                class="flex flex-col gap-2 border-l-4 px-4 py-3 transition-colors hover:bg-muted md:flex-row md:items-center md:gap-5 md:px-6 md:py-5"
                                :class="{
                                    'border-amber-400': req.state === 'pending',
                                    'border-emerald-500': req.state === 'approved',
                                    'border-destructive': req.state === 'rejected',
                                    'border-orange-400': req.state === 'rejected_with_observations',
                                }"
                            >
                                <!-- Mobile: Card compacta -->
                                <div class="-mx-4 -my-3 flex flex-col gap-1 bg-card p-4 md:hidden">
                                    <!-- Fila superior: RUT, estado y botón -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex min-w-0 flex-1 items-center gap-2">
                                            <!-- RUT y fecha -->
                                            <div class="min-w-0">
                                                <h3 class="truncate text-xs font-semibold">
                                                    RUT: {{ req.rut }}
                                                </h3>
                                                <p class="font-mono text-xs text-muted-foreground">
                                                    {{ req.code }}
                                                </p>
                                                <p class="text-xs text-muted-foreground">
                                                    {{ formatDate(req.created_at) }}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- Badge y botón -->
                                        <div class="flex items-center gap-1">
                                            <Badge
                                                :variant="getStatusConfig(req.state).variant"
                                                :class="[
                                                    'flex-shrink-0 gap-0.5 text-xs',
                                                    getStatusConfig(req.state).bgColor,
                                                    getStatusConfig(req.state).color,
                                                    getStatusConfig(req.state).borderColor,
                                                ]"
                                            >
                                                <component
                                                    :is="getStatusConfig(req.state).icon"
                                                    class="h-2.5 w-2.5"
                                                />
                                                {{ getStatusConfig(req.state).label }}
                                            </Badge>
                                            <Button
                                                v-if="req.state === 'approved'"
                                                variant="outline"
                                                as-child
                                                size="sm"
                                                class="h-6 flex-shrink-0 gap-0.5 px-1.5 py-1 text-xs"
                                            >
                                                <Link :href="show(req.code).url">
                                                    <PlayCircle class="h-3 w-3" />
                                                </Link>
                                            </Button>
                                            <Button
                                                v-else
                                                variant="outline"
                                                as-child
                                                size="sm"
                                                class="h-6 flex-shrink-0 gap-0.5 px-1.5 py-1 text-xs"
                                            >
                                                <Link :href="show(req.code).url">
                                                    <ArrowRight class="h-3 w-3" />
                                                </Link>
                                            </Button>
                                        </div>
                                    </div>
                                    <!-- Fila inferior: Actividad y dirección -->
                                    <div>
                                        <p class="truncate text-xs font-medium text-foreground">
                                            {{ req.business_activity }}
                                        </p>
                                        <p class="mt-1 truncate text-xs text-muted-foreground">
                                            {{ req.business_address }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Desktop: Layout original -->
                                <!-- Icono y título -->
                                <div class="hidden flex-shrink-0 items-center gap-3 md:flex">
                                    <div class="flex flex-col gap-1">
                                        <h3 class="truncate font-semibold">RUT: {{ req.rut }}</h3>
                                        <p class="text-xs text-muted-foreground">
                                            {{ formatDate(req.created_at) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Desktop: Código destacado -->
                                <div
                                    class="hidden flex-shrink-0 items-center justify-center md:flex"
                                >
                                    <div
                                        class="rounded-lg border border-blue-200 bg-gradient-to-r from-blue-50 to-indigo-50 px-4 py-2"
                                    >
                                        <p class="font-mono text-sm font-bold text-blue-700">
                                            {{ req.code }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Desktop: Descripción -->
                                <div class="hidden min-w-0 flex-1 md:block">
                                    <p class="truncate font-semibold text-foreground">
                                        {{ req.business_activity }}
                                    </p>
                                    <p class="mt-1 truncate text-xs text-muted-foreground">
                                        {{ req.business_address }}
                                    </p>
                                </div>

                                <!-- Desktop: Badge -->
                                <div class="hidden flex-shrink-0 items-center md:flex">
                                    <Badge
                                        :variant="getStatusConfig(req.state).variant"
                                        :class="[
                                            'gap-1.5',
                                            getStatusConfig(req.state).bgColor,
                                            getStatusConfig(req.state).color,
                                            getStatusConfig(req.state).borderColor,
                                        ]"
                                    >
                                        <component
                                            :is="getStatusConfig(req.state).icon"
                                            class="h-3 w-3"
                                        />
                                        {{ getStatusConfig(req.state).label }}
                                    </Badge>
                                </div>

                                <!-- Desktop: Botón -->
                                <div class="hidden flex-shrink-0 items-center md:flex">
                                    <Button
                                        v-if="req.state === 'approved'"
                                        variant="outline"
                                        as-child
                                        size="sm"
                                        class="w-32 justify-center gap-2 sm:w-40"
                                    >
                                        <Link :href="show(req.code).url">
                                            <PlayCircle class="h-4 w-4" />
                                            <span>Seguir trámite</span>
                                        </Link>
                                    </Button>
                                    <Button
                                        v-else
                                        variant="outline"
                                        as-child
                                        size="sm"
                                        class="w-32 justify-center gap-2 sm:w-40"
                                    >
                                        <Link :href="show(req.code).url">
                                            <span>Ver detalles</span>
                                            <ArrowRight class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                </div>
                            </div>
                            <Separator v-if="index < requests.data.length - 1" />
                        </template>
                    </div>

                    <div v-else class="flex flex-col items-center justify-center py-12 text-center">
                        <FileText class="h-12 w-12 text-gray-300" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">
                            No tienes solicitudes de patente
                        </h3>
                        <p class="mt-2 text-gray-500">
                            Inicia tu primer trámite de patente municipal
                        </p>
                        <Button variant="default" as-child class="mt-4">
                            <Link :href="create().url" class="gap-2">
                                <Plus class="h-4 w-4" />
                                Nueva Solicitud
                            </Link>
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Paginación -->
            <div class="p-4">
                <Pagination
                    v-slot="{ page }"
                    :items-per-page="requests.per_page"
                    :total="requests.total"
                    :default-page="requests.current_page"
                    @update:page="
                        (newPage) =>
                            router.get(
                                index().url,
                                { page: newPage },
                                { preserveState: true, preserveScroll: true },
                            )
                    "
                >
                    <PaginationContent v-slot="{ items }">
                        <PaginationPrevious />
                        <template v-for="(item, index) in items" :key="index">
                            <PaginationItem
                                v-if="item.type === 'page'"
                                :value="item.value"
                                :is-active="item.value === page"
                            >
                                {{ item.value }}
                            </PaginationItem>
                        </template>
                        <PaginationNext />
                    </PaginationContent>
                </Pagination>
            </div>
        </div>
    </AppLayout>
</template>
