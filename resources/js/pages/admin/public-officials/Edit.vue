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
import AppLayout from '@/layouts/AppLayout.vue';
import { edit, index, update } from '@/routes/admin/public-officials';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link, router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

interface Department {
    id: number;
    name: string;
    slug: string;
}

interface PublicOfficial {
    id: number;
    name: string;
    email: string;
    department_id: number;
    is_active: boolean;
    role: string;
}

const props = defineProps<{
    publicOfficial: PublicOfficial;
    departments: Department[];
}>();

console.log(props.publicOfficial);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Funcionarios', href: index().url },
    { title: 'Editar Funcionario', href: edit(props.publicOfficial.id).url },
];

function handlePasswordReset() {
    router.post(
        `/admin/public-officials/${props.publicOfficial.id}/reset-password`,
        {},
        {
            onSuccess: () => {
                toast.success('Email de restablecimiento enviado correctamente.');
            },
            onError: () => {
                toast.error('Error al enviar el email de restablecimiento.');
            },
        },
    );
}
</script>

<template>
    <Head title="Editar Funcionario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Editar Funcionario</h1>
            </div>
            <Form
                :action="update(props.publicOfficial.id)"
                v-slot="{ errors, processing }"
                :transform="
                    (data) => ({
                        ...data,
                        is_active: data.is_active === 'on',
                    })
                "
                class="space-y-6"
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Información del Funcionario</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nombre <span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                :default-value="publicOfficial.name"
                                name="name"
                                type="text"
                                :tabindex="1"
                                placeholder="Nombre completo"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="space-y-2">
                            <Label for="email">Email <span class="text-red-500">*</span></Label>
                            <Input
                                id="email"
                                :default-value="publicOfficial.email"
                                name="email"
                                type="email"
                                :tabindex="2"
                                placeholder="correo@ejemplo.com"
                            />
                            <InputError :message="errors.email" />
                        </div>
                        <div class="space-y-2">
                            <Label for="department_id"
                                >Departamento <span class="text-red-500">*</span></Label
                            >
                            <Select
                                name="department_id"
                                id="department_id"
                                :default-value="publicOfficial.department_id"
                            >
                                <SelectTrigger :tabindex="3">
                                    <SelectValue placeholder="Seleccionar departamento" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="department in departments"
                                        :key="department.id"
                                        :value="department.id"
                                    >
                                        {{ department.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.department_id" />
                        </div>

                        <div class="space-y-2">
                            <Label for="is_active"
                                >Estado <span class="text-red-500">*</span></Label
                            >
                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="is_active"
                                    name="is_active"
                                    :default-value="publicOfficial.is_active"
                                    :tabindex="4"
                                />
                                <Label for="is_active" class="text-sm font-normal">
                                    Usuario activo
                                </Label>
                            </div>
                            <p class="text-xs text-gray-500">
                                Los usuarios inactivos no pueden acceder al sistema
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Opciones de Contraseña</CardTitle>
                        <p class="text-gray-500">Gestión de acceso del usuario</p>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="space-y-2">
                            <Button
                                type="button"
                                variant="outline"
                                @click="handlePasswordReset"
                                :tabindex="5"
                            >
                                Enviar email de restablecimiento
                            </Button>
                            <p class="text-xs text-gray-500">
                                Se enviará un email al usuario con un enlace para restablecer su
                                contraseña
                            </p>
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Button :tabindex="6" variant="outline" as-child>
                        <Link :href="index().url"> Cancelar </Link>
                    </Button>
                    <Button
                        type="submit"
                        :disabled="processing"
                        :tabindex="7"
                        data-test="create-funcionario-button"
                    >
                        <Spinner v-if="processing" />
                        {{ processing ? 'Guardando...' : 'Guardar Cambios' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
