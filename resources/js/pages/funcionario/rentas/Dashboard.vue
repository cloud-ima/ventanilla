<script setup lang="ts">
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ChartSingleTooltip } from '@/components/ui/chart';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Donut, GroupedBar } from '@unovis/ts';
import { VisAxis, VisDonut, VisGroupedBar, VisSingleContainer, VisXYContainer } from '@unovis/vue';
import {
    CheckCircle,
    ClipboardCheck,
    Clock,
    FileText,
    Inbox,
    TrendingUp,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

// Breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

// Datos desde el controlador via props
const props = defineProps<{
    stats: {
        total_requests: number;
        pending_requests: number;
        approved_requests: number;
        rejected_requests: number;
        total_forms: number;
        total_requirements: number;
    };
    requestsByState: Array<{
        state: string;
        label: string;
        count: number;
        percentage: number;
    }>;
    requestsByMonth: Array<{
        month: string;
        month_label: string;
        total: number;
        pending: number;
        approved: number;
        rejected: number;
    }>;
    topRequirements: Array<{
        name: string;
        category: string;
        count: number;
    }>;
    topForms: Array<{
        name: string;
        description: string;
        count: number;
    }>;
    requirementsByCategory: Array<{
        category: string;
        count: number;
    }>;
    avgResponseTime: number;
    reviewsThisMonth: number;
}>();

// Datos reactivos para las gráficas
const stats = ref(props.stats);
const requestsByState = ref(props.requestsByState);
const requestsByMonth = ref(props.requestsByMonth);
const topRequirements = ref(props.topRequirements);
const topForms = ref(props.topForms);
const avgResponseTime = ref(props.avgResponseTime);
const reviewsThisMonth = ref(props.reviewsThisMonth);

// Total de solicitudes
const totalRequests = computed(() => stats.value.total_requests);

// Datos para gráfica de estados
const donutChartData = computed(() =>
    requestsByState.value.map((item) => ({
        name: item.label,
        value: item.count,
        color:
            item.state === 'pending'
                ? 'var(--chart-1)'
                : item.state === 'approved'
                  ? 'var(--chart-2)'
                  : 'var(--chart-3)',
    })),
);

// Datos para gráfica de barras mensual
const monthlyChartData = computed(() => {
    return requestsByMonth.value.map((item) => ({
        month: item.month,
        count: item.total,
    }));
});
</script>

<template>
    <Head title="Dashboard - Rentas" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Dashboard de Rentas</h1>
                <p class="text-muted-foreground">
                    Estadísticas y métricas de solicitudes de patentes
                </p>
            </div>

            <!-- KPIs Principales -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Solicitudes</CardTitle>
                        <Inbox class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ totalRequests }}</div>
                        <p class="text-xs text-muted-foreground">Todas las solicitudes</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Pendientes</CardTitle>
                        <Clock class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold" style="color: var(--chart-1)">
                            {{ stats.pending_requests }}
                        </div>
                        <p class="text-xs text-muted-foreground">Esperando revisión</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Aprobadas</CardTitle>
                        <CheckCircle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold" style="color: var(--chart-2)">
                            {{ stats.approved_requests }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{
                                totalRequests > 0
                                    ? Math.round((stats.approved_requests / totalRequests) * 100)
                                    : 0
                            }}% de aprobación
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Rechazadas</CardTitle>
                        <XCircle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold" style="color: var(--chart-3)">
                            {{ stats.rejected_requests }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{
                                totalRequests > 0
                                    ? Math.round((stats.rejected_requests / totalRequests) * 100)
                                    : 0
                            }}% de rechazo
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Gráficas -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Gráfica Donut -->
                <Card>
                    <CardHeader>
                        <CardTitle>Solicitudes por Estado</CardTitle>
                        <CardDescription
                            >Distribución de solicitudes según su estado actual</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col items-center">
                            <div class="flex w-full items-center justify-center">
                                <VisSingleContainer
                                    :data="donutChartData"
                                    :duration="1000"
                                    :delay="1000"
                                >
                                    <VisDonut
                                        :value="(d: any) => d.value"
                                        :color="(d: any) => d.color"
                                        :arcWidth="50"
                                        :central-label="'Total'"
                                        :central-sub-label="totalRequests.toString()"
                                    />
                                    <ChartSingleTooltip
                                        :selector="Donut.selectors.segment"
                                        index="value"
                                    />
                                </VisSingleContainer>
                            </div>

                            <!-- Resumen de estados -->
                            <div class="mt-6 w-full space-y-2">
                                <div
                                    v-for="item in requestsByState"
                                    :key="item.state"
                                    class="flex items-center justify-between rounded-lg px-3 py-2 transition-colors hover:bg-muted/50"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="h-4 w-4 rounded-full shadow-sm"
                                            :style="{
                                                backgroundColor:
                                                    item.state === 'pending'
                                                        ? 'var(--chart-1)'
                                                        : item.state === 'approved'
                                                          ? 'var(--chart-2)'
                                                          : 'var(--chart-3)',
                                            }"
                                        ></div>
                                        <span class="text-sm font-medium">{{ item.label }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold">{{ item.count }}</span>
                                        <span
                                            class="rounded-md bg-muted px-2 py-1 text-xs text-muted-foreground"
                                        >
                                            {{ item.percentage }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
                <!-- Resumen Mensual -->
                <Card>
                    <CardHeader>
                        <CardTitle>Solicitudes por Mes</CardTitle>
                        <CardDescription>Últimos 6 meses</CardDescription>
                    </CardHeader>
                    <CardContent class="h-full">
                        <div class="h-full w-full">
                            <VisXYContainer :data="monthlyChartData" :duration="300" class="h-full">
                                <VisGroupedBar
                                    :x="(d: any, i: number) => i"
                                    :y="(d: any) => d.count"
                                    color="var(--chart-1)"
                                />
                                <VisAxis
                                    type="x"
                                    :numTicks="6"
                                    :tickFormat="
                                        (value: any) => monthlyChartData[value]?.month || ''
                                    "
                                />
                                <VisAxis type="y" :numTicks="5" />
                                <ChartSingleTooltip
                                    :selector="GroupedBar.selectors.bar"
                                    index="month"
                                    :value-formatter="(count: any) => `Total: ${count} solicitudes`"
                                />
                            </VisXYContainer>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Métricas Adicionales -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Tiempo Promedio</CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ avgResponseTime }} días</div>
                        <p class="text-xs text-muted-foreground">Para responder solicitudes</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Revisadas este mes</CardTitle>
                        <CheckCircle class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ reviewsThisMonth }}</div>
                        <p class="text-xs text-muted-foreground">Solicitudes procesadas</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Formularios</CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.total_forms }}
                        </div>
                        <p class="text-xs text-muted-foreground">Formularios procesados</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Requisitos</CardTitle>
                        <ClipboardCheck class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ stats.total_requirements }}
                        </div>
                        <p class="text-xs text-muted-foreground">Requisitos revisados</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Tablas de Top -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Top Requisitos -->
                <Card>
                    <CardHeader>
                        <CardTitle>Requisitos Más Usados en las Solicitudes</CardTitle>
                        <CardDescription>Los 5 requisitos más frecuentes</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="(req, index) in topRequirements"
                                :key="req.name"
                                class="flex items-center justify-between"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-muted"
                                    >
                                        <span class="text-sm font-bold">{{ index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ req.name }}</p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ req.category || 'General' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold">{{ req.count }}</p>
                                    <p class="text-xs text-muted-foreground">veces</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Top Formularios -->
                <Card>
                    <CardHeader>
                        <CardTitle>Formularios Más Usados en las Solicitudes</CardTitle>
                        <CardDescription>Los 5 formularios más utilizados</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div
                                v-for="(form, index) in topForms"
                                :key="form.name"
                                class="flex items-center justify-between"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-muted"
                                    >
                                        <span class="text-sm font-bold">{{ index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">{{ form.name }}</p>
                                        <p class="text-xs text-muted-foreground">
                                            {{ form.description || 'Formulario' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold">{{ form.count }}</p>
                                    <p class="text-xs text-muted-foreground">usos</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
