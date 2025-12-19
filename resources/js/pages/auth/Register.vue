<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
import { Form, Head } from '@inertiajs/vue3';
import { Check, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

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
    <AuthBase title="Crear una cuenta">
        <Head title="Registro" />

        <Form
            v-bind="store()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Nombre completo"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Correo electrónico</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="correo@ejemplo.com"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="address">Dirección</Label>
                    <Input
                        id="address"
                        type="text"
                        :tabindex="3"
                        autocomplete="address-line1"
                        name="address"
                        placeholder="Dirección"
                    />
                    <InputError :message="errors.address" />
                </div>

                <div class="grid gap-2">
                    <Label for="phone_number">Número</Label>
                    <Input
                        id="phone_number"
                        type="text"
                        :tabindex="4"
                        autocomplete="tel"
                        name="phone_number"
                        placeholder="+56 9 1234 5678"
                    />
                    <InputError :message="errors.phone_number" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="5"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Contraseña"
                        v-model="password"
                    />
                    <InputError :message="errors.password" />

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
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="6"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Confirmar contraseña"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 w-full"
                    tabindex="7"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Crear cuenta
                </Button>
            </div>

            <div class="text-center text-muted-foreground">
                ¿Ya tienes una cuenta?
                <TextLink :href="login()" class="underline underline-offset-4" :tabindex="8"
                    >Iniciar sesión</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
