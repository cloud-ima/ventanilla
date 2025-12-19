<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Switch from '@/components/ui/switch/Switch.vue';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { index } from '@/routes/funcionario/rentas/forms';
import type { BreadcrumbItem } from '@/types';
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

interface PatentForm {
    id: number;
    name: string;
    description: string | null;
    template_file: string | null;
    is_active: boolean;
    created_by: number;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    form: PatentForm;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Formularios', href: index().url },
    { title: 'Editar Formulario', href: '#' },
];
</script>

<template>
    <Head title="Editar Formulario" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link :href="index().url">
                        <ArrowLeft class="h-5 w-5" />
                    </Link>
                </Button>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Editar Formulario</h1>
                    <p class="mt-1 text-gray-500">Modifica la información del formulario</p>
                </div>
            </div>

            <!-- Form -->
            <Form
                :action="`/funcionario/rentas/forms/${props.form.id}`"
                method="post"
                v-slot="{ errors, processing }"
                :transform="
                    (data) => ({
                        ...data,
                        _method: 'PUT',
                        is_active: data.is_active === 'on',
                    })
                "
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Formulario</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <Label for="name">Nombre del Formulario</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    :default-value="form.name"
                                    placeholder="Ej: Solicitud de Patente Comercial"
                                />
                                <InputError :message="errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="description">Descripción</Label>
                                <Textarea
                                    id="description"
                                    name="description"
                                    :default-value="form.description ?? ''"
                                    placeholder="Describe el formulario y su uso..."
                                    rows="3"
                                />
                                <InputError :message="errors.description" />
                            </div>

                            <div class="space-y-2">
                                <Label for="template_file">Plantilla del Formulario</Label>
                                <Input
                                    id="template_file"
                                    type="file"
                                    accept=".pdf,.doc,.docx"
                                    name="template_file"
                                    :disabled="processing"
                                />
                                <p class="text-sm text-gray-500">
                                    Formatos aceptados: PDF, DOC, DOCX (Máx. 2MB)
                                </p>
                                <p v-if="processing" class="text-sm text-blue-600">
                                    Subiendo archivo... Por favor espera.
                                </p>
                                <InputError :message="errors.template_file" />
                                <p
                                    v-if="form.template_file && !processing"
                                    class="text-sm text-blue-600"
                                >
                                    Archivo actual:
                                    {{
                                        form.template_file
                                            ?.split('/')
                                            .pop()
                                            ?.replace(
                                                /_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}\./,
                                                '.',
                                            ) || 'archivo.pdf'
                                    }}
                                </p>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="is_active"
                                    name="is_active"
                                    :default-value="form.is_active"
                                />
                                <Label for="is_active">Formulario activo</Label>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-3 pt-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="router.visit(index().url)"
                                    :disabled="processing"
                                >
                                    Cancelar
                                </Button>
                                <Button type="submit" :disabled="processing">
                                    {{ processing ? 'Guardando...' : 'Guardar Cambios' }}
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </Form>
        </div>
    </AppLayout>
</template>
