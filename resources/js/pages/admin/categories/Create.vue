<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
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
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/admin/categories';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';

interface Department {
    id: number;
    name: string;
}

defineProps<{
    departments: Department[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Categorías', href: index().url },
    { title: 'Crear', href: create().url },
];
</script>

<template>
    <Head title="Crear Categoría" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Categoría</h1>
            </div>
            <Form
                :action="store()"
                :transform="
                    (data) => ({
                        ...data,
                        is_active: data.is_active === 'on',
                    })
                "
                v-slot="{ errors, processing }"
                class="space-y-6"
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Categoría</CardTitle>
                        <p class="text-gray-500">
                            Información de la categoría para los distintos trámites.
                        </p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Unidad Perteneciente -->
                        <div class="space-y-2">
                            <Label for="department_id"
                                >Unidad<span class="text-red-500">*</span></Label
                            >
                            <Select name="department_id">
                                <SelectTrigger id="department_id" :tabindex="1">
                                    <SelectValue placeholder="Seleccionar unidad" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="dept in departments"
                                        :key="dept.id"
                                        :value="String(dept.id)"
                                    >
                                        {{ dept.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.department_id" />
                        </div>
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                :tabindex="2"
                                placeholder="Ej: Patentes"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <!-- Descripción -->
                        <div class="space-y-2">
                            <Label for="description">Descripción</Label>
                            <Textarea
                                id="description"
                                name="description"
                                :tabindex="3"
                                placeholder="Descripción de la categoría..."
                            />
                            <InputError :message="errors.description" />
                        </div>
                        <!-- Orden -->
                        <div class="space-y-2">
                            <Label for="order">Orden<span class="text-red-500">*</span></Label>
                            <Input
                                id="order"
                                name="order"
                                type="number"
                                min="0"
                                :tabindex="4"
                                :default-value="0"
                            />
                            <InputError :message="errors.order" />
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <Switch
                                    id="is_active"
                                    name="is_active"
                                    :default-value="true"
                                    :tabindex="5"
                                />
                                <Label for="is_active">Activo</Label>
                                <InputError :message="errors.is_active" />
                            </div>
                            <p class="text-sm text-gray-500">
                                Esta opción controla si la categoría será visible en el portal
                                público. Si se desactiva, la categoría no se eliminará pero no
                                aparecerá para los contribuyentes.
                            </p>
                        </div>
                    </CardContent>
                </Card>
                <div class="flex justify-end gap-4">
                    <Button variant="outline" as-child :tabindex="6">
                        <Link :href="index()">Cancelar</Link>
                    </Button>
                    <Button type="submit" :disabled="processing" :tabindex="7">
                        <Spinner v-if="processing" class="mr-2" />
                        {{ processing ? 'Creando...' : 'Crear Categoría' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
