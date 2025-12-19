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
import { Spinner } from '@/components/ui/spinner';
import { Switch } from '@/components/ui/switch';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/funcionario/rentas/forms';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Formularios', href: index().url },
    { title: 'Crear Formulario', href: create().url },
];
</script>

<template>
    <Head title="Crear Formulario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Formulario</h1>
            </div>

            <Form
                :action="store()"
                :transform="(data) => ({ ...data, is_active: data.is_active === 'on' })"
                v-slot="{ errors, processing }"
                class="space-y-6"
                enctype="multipart/form-data"
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Formulario</CardTitle>
                        <CardDescription>
                            Completa los datos del formulario que los solicitantes deben descargar
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name"
                                >Nombre del Formulario<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="name"
                                name="name"
                                :tabindex="1"
                                type="text"
                                placeholder="Ej: Solicitud de Patente Comercial"
                            />
                            <InputError :message="errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="description">Descripción</Label>
                            <Textarea
                                id="description"
                                name="description"
                                :tabindex="2"
                                placeholder="Describe brevemente para qué sirve este formulario"
                                rows="3"
                            />
                            <InputError :message="errors.description" />
                        </div>

                        <div class="space-y-2">
                            <Label for="template_file"
                                >Archivo de Plantilla<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="template_file"
                                name="template_file"
                                :tabindex="3"
                                type="file"
                                accept=".pdf,.doc,.docx"
                            />
                            <p class="text-xs text-gray-500">
                                Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 2MB
                            </p>
                            <InputError :message="errors.template_file" />
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Configuración</CardTitle>
                        <CardDescription>
                            Define si este formulario estará disponible para adjuntar en las
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
                                    :tabindex="4"
                                />
                                <Label for="is_active" class="text-sm font-medium text-gray-700">
                                    Formulario activo
                                </Label>
                            </div>
                            <InputError :message="errors.is_active" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Button :tabindex="5" variant="outline" as-child>
                        <Link :href="index().url">Cancelar</Link>
                    </Button>
                    <Button type="submit" :tabindex="6" :disabled="processing">
                        <Spinner v-if="processing" />
                        {{ processing ? 'Guardando...' : 'Crear Formulario' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
