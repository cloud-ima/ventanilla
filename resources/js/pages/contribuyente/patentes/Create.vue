<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { index, store } from '@/routes/contribuyente/patentes';
import type { BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';
import { Info } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Solicitudes de Patente',
        href: '/contribuyente/patentes',
    },
    {
        title: 'Nueva Solicitud',
        href: '',
    },
];

// Estado para el RUT
const rutValue = ref('');

// Función para formatear RUT
const formatRut = (value: string) => {
    // Limpiar: solo números y guion
    let cleaned = value.replace(/[^0-9-]/g, '');

    // Eliminar todos los guiones
    cleaned = cleaned.replace(/-/g, '');

    // Si no hay números, retornar vacío
    if (cleaned.length === 0) {
        return '';
    }

    // Separar números y dígito verificador
    const numbers = cleaned.slice(0, -1);
    const verifier = cleaned.slice(-1);

    // Formatear: números + guion + dígito verificador
    return numbers + '-' + verifier;
};

// Watch para formatear en vivo (sin validación)
watch(rutValue, (newValue) => {
    const formatted = formatRut(newValue);
    if (formatted !== newValue) {
        rutValue.value = formatted;
    }
});
</script>

<template>
    <Head title="Nueva Solicitud de Patente" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nueva Solicitud de Patente</h1>
                <p class="mt-1 text-sm text-gray-500">Inicia tu trámite de patente municipal</p>
            </div>

            <!-- Alerta informativa -->
            <Alert>
                <Info class="h-4 w-4" />
                <AlertTitle>Solicitud de Patente Municipal</AlertTitle>
                <AlertDescription>
                    Completa el formulario con los datos de tu establecimiento. Una vez enviada, un
                    funcionario municipal evaluará tu solicitud y te notificará el resultado por
                    correo electrónico.
                </AlertDescription>
            </Alert>

            <!-- Formulario -->
            <Form :action="store()" v-slot="{ errors, processing }" class="space-y-6">
                <!-- Datos del Solicitante -->
                <Card>
                    <CardHeader>
                        <CardTitle>Datos del Establecimiento</CardTitle>
                        <p class="text-sm text-gray-500">
                            Información básica para la evaluación inicial
                        </p>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- RUT -->
                        <div class="space-y-2">
                            <Label for="rut"
                                >RUT (Empresa o Persona Natural)
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="rut"
                                name="rut"
                                :tabindex="1"
                                type="text"
                                v-model="rutValue"
                                placeholder="12345678-9"
                            />
                            <p class="text-sm text-gray-500">
                                RUT de la empresa o persona natural que solicita la patente (ej:
                                12345678-9)
                            </p>
                            <InputError :message="errors.rut" />
                        </div>

                        <!-- Dirección -->
                        <div class="space-y-2">
                            <Label for="business_address"
                                >Dirección del Establecimiento
                                <span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="business_address"
                                name="business_address"
                                :tabindex="2"
                                type="text"
                                placeholder="Calle 21 de Mayo #123, Arica"
                            />
                            <p class="text-sm text-gray-500">
                                Dirección exacta donde funcionará el establecimiento
                            </p>
                            <InputError :message="errors.business_address" />
                        </div>

                        <!-- Giro -->
                        <div class="space-y-2">
                            <Label for="business_activity"
                                >Giro o Rubro Comercial <span class="text-red-500">*</span></Label
                            >
                            <Textarea
                                id="business_activity"
                                name="business_activity"
                                :tabindex="3"
                                placeholder="Ej: Panadería y pastelería, Venta de vinos y licores, Servicios legales"
                                rows="3"
                            />
                            <p class="text-sm text-gray-500">
                                Describe la actividad económica que realizarás
                            </p>
                            <InputError :message="errors.business_activity" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Información adicional -->
                <Alert variant="default">
                    <Info class="h-4 w-4" />
                    <AlertTitle>Próximos pasos</AlertTitle>
                    <AlertDescription>
                        <ol class="mt-2 list-inside list-decimal space-y-1">
                            <li>Un funcionario municipal revisará tu solicitud.</li>
                            <li>Recibirás una respuesta por correo electrónico.</li>
                            <li>
                                Si es aprobada, te indicaremos si debes presentar documentos
                                adicionales en la municipalidad.
                            </li>
                        </ol>
                    </AlertDescription>
                </Alert>

                <!-- Botones -->
                <div class="flex items-center justify-end gap-3">
                    <Button :tabindex="4" variant="outline" as-child>
                        <Link :href="index()"> Cancelar </Link>
                    </Button>
                    <Button type="submit" :disabled="processing" :tabindex="5">
                        <Spinner v-if="processing" />
                        {{ processing ? 'Enviando...' : 'Enviar Solicitud' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
