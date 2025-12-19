<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/admin/requirements';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Requisitos', href: index().url },
    { title: 'Crear', href: create().url },
];
</script>

<template>
    <Head title="Crear Requisito" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Requisito</h1>
            </div>
            <Form :action="store()" v-slot="{ errors, processing }" class="space-y-6">
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
                        {{ processing ? 'Creando...' : 'Crear' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
