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
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import { index, show } from '@/routes/funcionario/rentas/patentes';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { AlertCircle, CheckCircle2, Clock, Eye, FileText, XCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Solicitudes de Patente', href: index().url },
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
        month: '2-digit',
        day: '2-digit',
    });
};

// Lógica de búsqueda simple
const searchCode = ref('');
const selectedState = ref('');

const performSearch = () => {
    const params: any = {};
    if (searchCode.value.trim()) {
        params.code = searchCode.value.trim();
    }
    if (selectedState.value) {
        params.state = selectedState.value;
    }

    router.get(index().url, params, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearSearch = () => {
    searchCode.value = '';
    selectedState.value = '';
    performSearch();
};

const filterByState = (state: string) => {
    selectedState.value = state;
    performSearch();
};

interface PaginationResponse<T> {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    current_page_url: string;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
    from: number;
    to: number;
    data: T[];
}

interface PatentRequest {
    id: number;
    code: string;
    rut: string;
    business_address: string;
    business_activity: string;
    contact_email: string;
    state: string;
    user: User;
    created_at: string;
    reviewed_at: string;
}

//Contribuyente
interface User {
    id: number;
    name: string;
    email: string;
}

defineProps<{
    requests: PaginationResponse<PatentRequest>;
    stats: {
        pending: number;
        approved: number;
        rejected: number;
        rejected_with_observations: number;
    };
    filters: {
        state?: string;
        rut?: string;
    };
}>();
</script>

<template>
    <Head title="Solicitudes de Patente" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-7xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Solicitudes de Patente</h1>
                <p class="mt-1 text-gray-500">
                    Revisa y gestiona las solicitudes de patentes municipales
                </p>
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
                                    {{ stats.pending }}
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
                                    {{ stats.approved }}
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
                                    {{ stats.rejected }}
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
                                    {{ stats.rejected_with_observations }}
                                </p>
                                <p class="text-xs text-gray-500">Rechazadas con Obs.</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Tabla de solicitudes -->
            <Card>
                <CardHeader>
                    <div class="flex flex-col gap-4">
                        <div
                            class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                        >
                            <CardTitle>Todas las Solicitudes ({{ requests.total }})</CardTitle>
                            <div class="flex max-w-sm gap-2">
                                <Input
                                    id="search-code"
                                    name="search-code"
                                    placeholder="Buscar por código..."
                                    v-model="searchCode"
                                    class="flex-1"
                                />
                                <Button @click="performSearch"> Buscar </Button>
                                <Button
                                    v-if="searchCode || selectedState"
                                    variant="outline"
                                    @click="clearSearch"
                                >
                                    Limpiar
                                </Button>
                            </div>
                        </div>
                        <!-- Filtros por estado -->
                        <div class="flex flex-wrap gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'ring-2 ring-primary': selectedState === '' }"
                                @click="filterByState('')"
                            >
                                Todos
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'ring-2 ring-primary': selectedState === 'pending' }"
                                @click="filterByState('pending')"
                                class="gap-2"
                            >
                                <Clock class="h-4 w-4" />
                                Pendientes
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'ring-2 ring-primary': selectedState === 'approved' }"
                                @click="filterByState('approved')"
                                class="gap-2"
                            >
                                <CheckCircle2 class="h-4 w-4" />
                                Aprobados
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'ring-2 ring-primary': selectedState === 'rejected' }"
                                @click="filterByState('rejected')"
                                class="gap-2"
                            >
                                <XCircle class="h-4 w-4" />
                                Rechazados
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{
                                    'ring-2 ring-primary':
                                        selectedState === 'rejected_with_observations',
                                }"
                                @click="filterByState('rejected_with_observations')"
                                class="gap-2"
                            >
                                <AlertCircle class="h-4 w-4" />
                                Rechazados con Obs.
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Código</TableHead>
                                <TableHead>RUT</TableHead>
                                <TableHead>Giro Comercial</TableHead>
                                <TableHead>Dirección</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead>Fecha</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="req in requests.data"
                                :key="req.id"
                                class="hover:bg-muted/50"
                            >
                                <TableCell class="font-mono font-medium">{{ req.code }}</TableCell>
                                <TableCell class="font-medium">{{ req.rut }}</TableCell>
                                <TableCell>
                                    <div class="max-w-[150px] truncate">
                                        {{ req.business_activity }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <div class="max-w-[150px] truncate">
                                        {{ req.business_address }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="getStatusConfig(req.state).variant"
                                        class="gap-1.5"
                                    >
                                        <component
                                            :is="getStatusConfig(req.state).icon"
                                            class="h-3 w-3"
                                        />
                                        {{ getStatusConfig(req.state).label }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex flex-col">
                                        <span>{{ formatDate(req.created_at) }}</span>
                                        <span
                                            v-if="req.reviewed_at"
                                            class="text-xs text-muted-foreground"
                                        >
                                            Rev: {{ formatDate(req.reviewed_at) }}
                                        </span>
                                    </div>
                                </TableCell>
                                <TableCell class="text-right">
                                    <Button variant="ghost" size="sm" as-child>
                                        <Link :href="show(req.code)">
                                            <Eye class="h-4 w-4" />
                                        </Link>
                                    </Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <div
                        v-if="requests.data.length === 0"
                        class="flex flex-col items-center justify-center py-12 text-center"
                    >
                        <FileText class="h-12 w-12 text-gray-300" />
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No hay solicitudes</h3>
                        <p class="mt-2 text-gray-500">No se encontraron solicitudes de patente</p>
                    </div>
                </CardContent>
            </Card>
            <!-- Pagination Controls -->
            <div class="mt-4 flex items-center justify-between px-2">
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
