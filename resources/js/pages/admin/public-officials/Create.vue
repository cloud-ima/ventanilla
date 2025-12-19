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
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/admin/public-officials';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Department {
    id: number;
    name: string;
    slug: string;
}

defineProps<{
    departments: Department[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Funcionarios', href: index().url },
    { title: 'Crear Funcionario', href: create().url },
];
const password = ref('');

const passwordRequirements = computed(() => [
    {
        label: 'Sin espacios en blanco',
        met: !/\s/.test(password.value),
    },
    {
        label: 'Mínimo 8 caracteres',
        met: password.value.length >= 8,
    },
    {
        label: 'Al menos una mayúscula',
        met: /[A-Z]/.test(password.value),
    },
    {
        label: 'Al menos un número',
        met: /[0-9]/.test(password.value),
    },
    {
        label: 'Al menos un carácter (-_!@#$%^&*)',
        met: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password.value),
    },
]);
</script>

<template>
    <Head title="Crear Funcionario" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Funcionario</h1>
            </div>
            <Form
                :action="store()"
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
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                :tabindex="1"
                                placeholder="Nombre completo"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="space-y-2">
                            <Label for="email">Email<span class="text-red-500">*</span></Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                :tabindex="2"
                                placeholder="correo@ejemplo.com"
                            />
                            <InputError :message="errors.email" />
                        </div>
                        <div class="space-y-2">
                            <Label for="department_id"
                                >Departamento<span class="text-red-500">*</span></Label
                            >
                            <Select name="department_id" id="department_id">
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
                            <Label for="password"
                                >Contraseña<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="password"
                                name="password"
                                type="password"
                                :tabindex="4"
                                placeholder="Contraseña"
                                v-model="password"
                            />
                            <InputError :message="errors.password" />
                            <!-- Indicador de requisitos de contraseña -->
                            <div class="mt-2 space-y-1.5 rounded-lg border bg-muted/50 p-3">
                                <p class="font-medium text-muted-foreground">
                                    Requisitos de contraseña:
                                </p>
                                <ul class="space-y-1">
                                    <li
                                        v-for="(requirement, index) in passwordRequirements"
                                        :key="index"
                                        class="flex items-center gap-2 transition-colors"
                                        :class="
                                            requirement.met
                                                ? 'text-green-600'
                                                : 'text-muted-foreground'
                                        "
                                    >
                                        <Check
                                            v-if="requirement.met"
                                            :size="14"
                                            class="flex-shrink-0"
                                        />
                                        <X v-else :size="14" class="flex-shrink-0" />
                                        <span>{{ requirement.label }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirmar Contraseña</Label>
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                :tabindex="5"
                                placeholder="Confirmar contraseña"
                            />
                            <InputError :message="errors.password_confirmation" />
                        </div>
                    </CardContent>
                </Card>

                <div class="flex justify-end gap-4">
                    <Button :tabindex="7" variant="outline" as-child>
                        <Link :href="index().url"> Cancelar </Link>
                    </Button>
                    <Button
                        type="submit"
                        :disabled="processing"
                        :tabindex="8"
                        data-test="create-funcionario-button"
                    >
                        <Spinner v-if="processing" />
                        {{ processing ? 'Creando...' : 'Crear Funcionario' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
