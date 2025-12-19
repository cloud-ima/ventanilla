<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardDescription from '@/components/ui/card/CardDescription.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import { create, index, store } from '@/routes/funcionario/rentas/requirements';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Requerimientos', href: index().url },
    { title: 'Nuevo Requerimiento', href: create().url },
];
</script>

<template>
    <Head title="Nuevo Requerimiento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Nuevo Requerimiento</h1>
            </div>

            <Form
                :action="store()"
                :transform="(data) => ({ ...data, is_active: data.is_active === 'on' })"
                v-slot="{ errors, processing }"
                class="space-y-6"
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Requerimiento</CardTitle>
                        <CardDescription>
                            Completa los datos del requerimiento que los solicitantes deben
                            presentar
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="code">Código<span class="text-red-500">*</span></Label>
                                <Input
                                    id="code"
                                    name="code"
                                    :tabindex="1"
                                    type="text"
                                    placeholder="Ej: CERT_UBICACION"
                                />
                                <InputError :message="errors.code" />
                            </div>

                            <div class="space-y-2">
                                <Label for="category"
                                    >Categoría<span class="text-red-500">*</span></Label
                                >
                                <Select name="category" :tabindex="2">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Seleccione una categoría" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="municipal">Municipal</SelectItem>
                                        <SelectItem value="sanitario">Sanitario</SelectItem>
                                        <SelectItem value="legal">Legal</SelectItem>
                                        <SelectItem value="profesional">Profesional</SelectItem>
                                        <SelectItem value="financiero">Financiero</SelectItem>
                                        <SelectItem value="transporte">Transporte</SelectItem>
                                        <SelectItem value="educacion">Educación</SelectItem>
                                        <SelectItem value="seguridad">Seguridad</SelectItem>
                                        <SelectItem value="otros">Otros</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="errors.category" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="name"
                                >Nombre del Requerimiento<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="name"
                                name="name"
                                :tabindex="3"
                                type="text"
                                placeholder="Ej: Certificado de Ubicación"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Descripción</Label>
                            <Textarea
                                id="description"
                                name="description"
                                :tabindex="4"
                                placeholder="Describe brevemente para qué sirve este requerimiento"
                                rows="3"
                            />
                            <InputError :message="errors.description" />
                        </div>

                        <div class="space-y-2">
                            <Label for="where_to_obtain"
                                >Dónde obtener<span class="text-red-500">*</span></Label
                            >
                            <Textarea
                                id="where_to_obtain"
                                name="where_to_obtain"
                                :tabindex="5"
                                placeholder="Ej: En la Municipalidad correspondiente, en cualquier oficina presencial"
                                rows="2"
                            />
                            <InputError :message="errors.where_to_obtain" />
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Información Adicional</CardTitle>
                        <CardDescription>
                            Datos opcionales para ayudar al solicitante a obtener el requerimiento
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="obtain_address">Dirección específica</Label>
                            <Input
                                id="obtain_address"
                                name="obtain_address"
                                :tabindex="6"
                                type="text"
                                placeholder="Ej: Av. Providencia 123, Santiago"
                            />
                            <InputError :message="errors.obtain_address" />
                        </div>

                        <div class="space-y-2">
                            <Label for="info_url">URL con más información</Label>
                            <Input
                                id="info_url"
                                name="info_url"
                                :tabindex="7"
                                type="url"
                                placeholder="https://www.sii.cl/ejemplo"
                            />
                            <InputError :message="errors.info_url" />
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Configuración</CardTitle>
                        <CardDescription>
                            Define si este requerimiento estará disponible para adjuntar en las
                            aprobaciones de patentes
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="is_active"
                                    name="is_active"
                                    :default-value="true"
                                    :tabindex="8"
                                />
                                <Label for="is_active" class="text-sm font-medium text-gray-700">
                                    Requerimiento activo
                                </Label>
                            </div>
                            <InputError :message="errors.is_active" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Button :tabindex="9" variant="outline" as-child>
                        <Link :href="index().url">Cancelar</Link>
                    </Button>
                    <Button type="submit" :tabindex="10" :disabled="processing">
                        <Spinner v-if="processing" />
                        {{ processing ? 'Guardando...' : 'Crear Requerimiento' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
