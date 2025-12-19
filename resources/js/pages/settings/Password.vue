<script setup lang="ts">
import PasswordController from '@/actions/App/Http/Controllers/Settings/PasswordController';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { edit } from '@/routes/user-password';
import { Form, Head } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: edit().url,
    },
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
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Cambiar contraseña" />

        <SettingsLayout>
            <div class="space-y-6">
                <HeadingSmall
                    title="Cambiar contraseña"
                    description="Asegúrate de usar una contraseña larga y aleatoria para mantener segura tu cuenta."
                />

                <Form
                    v-bind="PasswordController.update()"
                    :options="{
                        preserveScroll: true,
                    }"
                    reset-on-success
                    :reset-on-error="['password', 'password_confirmation', 'current_password']"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="current_password">Contraseña actual</Label>
                        <Input
                            id="current_password"
                            name="current_password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="current-password"
                            placeholder="contraseña actual"
                        />
                        <InputError :message="errors.current_password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password">Nueva contraseña</Label>
                        <Input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            v-model="password"
                            placeholder="nueva contraseña"
                        />
                        <InputError :message="errors.password" />
                    </div>

                    <!-- Indicador de requisitos de contraseña -->
                    <div class="mt-2 space-y-1.5 rounded-lg border bg-muted/50 p-3">
                        <p class="font-medium text-muted-foreground">Requisitos de contraseña:</p>
                        <ul class="space-y-1">
                            <li
                                v-for="(requirement, index) in passwordRequirements"
                                :key="index"
                                class="flex items-center gap-2 transition-colors"
                                :class="
                                    requirement.met ? 'text-green-600' : 'text-muted-foreground'
                                "
                            >
                                <Check v-if="requirement.met" :size="14" class="flex-shrink-0" />
                                <X v-else :size="14" class="flex-shrink-0" />
                                <span>{{ requirement.label }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation">Confirmar contraseña</Label>
                        <Input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="confirmar contraseña"
                        />
                        <InputError :message="errors.password_confirmation" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing" data-test="update-password-button">{{
                            processing ? 'Guardando...' : 'Guardar contraseña'
                        }}</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="recentlySuccessful" class="text-neutral-600">Guardado.</p>
                        </Transition>
                    </div>
                </Form>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
