<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import { index, update } from '@/routes/funcionario/rentas/requirements';
import type { BreadcrumbItem } from '@/types';
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    requirement: any;
}>();

const categories = [
    { value: 'municipal', label: 'Municipal' },
    { value: 'sanitario', label: 'Sanitario' },
    { value: 'legal', label: 'Legal' },
    { value: 'profesional', label: 'Profesional' },
    { value: 'financiero', label: 'Financiero' },
    { value: 'transporte', label: 'Transporte' },
    { value: 'educacion', label: 'Educación' },
    { value: 'seguridad', label: 'Seguridad' },
    { value: 'otros', label: 'Otros' },
];

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard().url },
    { title: 'Requerimientos', href: index().url },
    { title: 'Editar Requerimiento', href: '#' },
];
</script>

<template>
    <Head title="Editar Requerimiento" />
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
                    <h1 class="text-3xl font-bold text-gray-900">Editar Requerimiento</h1>
                    <p class="mt-1 text-gray-500">Modifica la información del requerimiento</p>
                </div>
            </div>

            <!-- Form -->
            <Form
                :action="update(requirement.id)"
                v-slot="{ errors, processing }"
                :transform="
                    (data) => ({
                        ...data,
                        is_active: data.is_active === 'on',
                    })
                "
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Requerimiento</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-6">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="code">Código</Label>
                                    <Input
                                        id="code"
                                        name="code"
                                        :default-value="requirement.code"
                                        placeholder="Ej: REQ-001"
                                    />
                                    <InputError :message="errors.code" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="category">Categoría</Label>
                                    <Select name="category" :default-value="requirement.category">
                                        <SelectTrigger
                                            :class="{ 'border-red-500': errors.category }"
                                        >
                                            <SelectValue placeholder="Selecciona una categoría" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="category in categories"
                                                :key="category.value"
                                                :value="category.value"
                                            >
                                                {{ category.label }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="errors.categories" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="name">Nombre del Requerimiento</Label>
                                <Input
                                    id="name"
                                    name="name"
                                    :default-value="requirement.name"
                                    placeholder="Ej: Certificado de Antecedentes"
                                />
                                <InputError :message="errors.name" />
                            </div>

                            <div class="space-y-2">
                                <Label for="description">Descripción</Label>
                                <Textarea
                                    id="description"
                                    name="description"
                                    :default-value="requirement.description || ''"
                                    placeholder="Describe el requerimiento..."
                                />
                                <InputError :message="errors.description" />
                            </div>

                            <div class="space-y-2">
                                <Label for="where_to_obtain">Dónde se obtiene</Label>
                                <Input
                                    id="where_to_obtain"
                                    name="where_to_obtain"
                                    :default-value="requirement.where_to_obtain"
                                    placeholder="Ej: Servicio de Registro Civil"
                                />
                                <InputError :message="errors.where_to_obtain" />
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="obtain_address">Dirección de obtención</Label>
                                    <Input
                                        id="obtain_address"
                                        name="obtain_address"
                                        :default-value="requirement.obtain_address || ''"
                                        placeholder="Ej: Av. Arturo Prat 123"
                                    />
                                    <InputError :message="errors.obtain_address" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="obtain_phone">Teléfono de contacto</Label>
                                    <Input
                                        id="obtain_phone"
                                        name="obtain_phone"
                                        :default-value="requirement.obtain_phone || ''"
                                        placeholder="Ej: +56 2 2123456"
                                    />
                                    <InputError :message="errors.obtain_phone" />
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="info_url">URL de información</Label>
                                    <Input
                                        id="info_url"
                                        name="info_url"
                                        :default-value="requirement.info_url || ''"
                                        type="url"
                                        placeholder="https://..."
                                    />
                                    <InputError :message="errors.info_url" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="validity_days">Días de validez</Label>
                                    <Input
                                        id="validity_days"
                                        name="validity_days"
                                        :default-value="requirement.validity_days"
                                        type="number"
                                        min="1"
                                        placeholder="Ej: 30"
                                    />
                                    <InputError :message="errors.validity_days" />
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="is_active"
                                    name="is_active"
                                    :default-value="requirement.is_active"
                                />
                                <Label for="is_active">Requerimiento activo</Label>
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
                                    {{ processing ? 'Guardando Cambios....' : 'Guardar Cambios' }}
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </Form>
        </div>
    </AppLayout>
</template>
