<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { edit, index, update } from '@/routes/admin/requirements';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';

interface Requirement {
    id: number;
    name: string;
    description: string | null;
    how_to_obtain: string | null;
    is_active: boolean;
    procedures_count: number;
}

const props = defineProps<{
    requirement: Requirement;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Requisitos', href: index().url },
    { title: 'Editar', href: edit(props.requirement.id).url },
];
</script>

<template>
    <Head title="Editar Requisito" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Editar Requisito</h1>
            </div>
            <Form
                :action="update(props.requirement.id)"
                v-slot="{ errors, processing }"
                class="space-y-6"
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Datos del Requisito</CardTitle>
                        <p class="text-gray-500">
                            Información del requisito para los distintos trámites.
                        </p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <Label for="name">Nombre <span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                :tabindex="1"
                                :default-value="requirement.name"
                                type="text"
                                placeholder="Cédula de identidad vigente"
                                :aria-invalid="!!errors.name"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <!-- Descripción -->
                        <div class="space-y-2">
                            <Label for="description">Descripción</Label>
                            <Input
                                id="description"
                                name="description"
                                :tabindex="2"
                                :default-value="requirement.description ?? ''"
                                type="text"
                                placeholder="Cédula de identidad vigente (original y copia)"
                                :aria-invalid="!!errors.description"
                            />
                            <InputError :message="errors.description" />
                        </div>
                        <!-- Cómo obtenerlo -->
                        <div class="space-y-2">
                            <Label for="how_to_obtain">Cómo obtenerlo</Label>
                            <Input
                                id="how_to_obtain"
                                name="how_to_obtain"
                                :tabindex="3"
                                :default-value="requirement.how_to_obtain ?? ''"
                                type="text"
                                placeholder="Registro Civil, Servicio de Impuesto Interno, etc."
                                :aria-invalid="!!errors.how_to_obtain"
                            />
                            <InputError :message="errors.how_to_obtain" />
                        </div>
                    </CardContent>
                </Card>
                <!-- Botones -->
                <div class="flex justify-end gap-4">
                    <Button :tabindex="4" variant="outline" as-child>
                        <Link :href="index()"> Cancelar </Link>
                    </Button>
                    <Button type="submit" :disabled="processing" :tabindex="5">
                        <Spinner v-if="processing" />
                        {{ processing ? 'Guardando...' : 'Guardar' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
