<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import Card from '@/components/ui/card/Card.vue';
import CardContent from '@/components/ui/card/CardContent.vue';
import CardHeader from '@/components/ui/card/CardHeader.vue';
import CardTitle from '@/components/ui/card/CardTitle.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Spinner } from '@/components/ui/spinner';
import Textarea from '@/components/ui/textarea/Textarea.vue';
import { AVAILABLE_ICONS } from '@/config/available-icon';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, index, store } from '@/routes/admin/departments';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Departamentos', href: index().url },
    { title: 'Crear', href: create().url },
];

const selectedIcon = ref('Building2');

const selectedIconData = computed(() => {
    return AVAILABLE_ICONS.find((i) => i.name === selectedIcon.value) || AVAILABLE_ICONS[0];
});
</script>

<template>
    <Head title="Crear Departamento" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Departamento</h1>
            </div>
            <Form :action="store()" v-slot="{ errors, processing }" class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Crear Departamento</CardTitle>
                        <p class="text-gray-500">Información del departamento.</p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Nombre<span class="text-red-500">*</span></Label>
                            <Input
                                id="name"
                                name="name"
                                :tabindex="1"
                                type="text"
                                placeholder="Nombre completo del departamento"
                            />
                            <InputError :message="errors.name" />
                        </div>
                        <div class="space-y-2">
                            <Label for="slug">Slug<span class="text-red-500">*</span></Label>
                            <Input
                                id="slug"
                                name="slug"
                                :tabindex="2"
                                type="text"
                                placeholder="slug-del-departamento"
                            />
                            <InputError :message="errors.slug" />
                        </div>
                        <div class="space-y-2">
                            <Label for="description"
                                >Descripción<span class="text-red-500">*</span></Label
                            >
                            <Textarea
                                id="description"
                                name="description"
                                :tabindex="3"
                                placeholder="Descripción del departamento"
                            />
                            <InputError :message="errors.description" />
                        </div>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader>
                        <CardTitle>Diseño en el Portal</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <Label>Icono</Label>
                            <Popover v-slot="{ close }">
                                <PopoverTrigger as-child :tabindex="4">
                                    <Button
                                        variant="outline"
                                        class="w-full justify-start gap-2"
                                        type="button"
                                    >
                                        <component
                                            :is="selectedIconData.component"
                                            class="h-4 w-4"
                                        />
                                        {{ selectedIconData.label }}
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent class="w-80">
                                    <div class="grid grid-cols-4 gap-2">
                                        <button
                                            v-for="icon in AVAILABLE_ICONS"
                                            :key="icon.name"
                                            type="button"
                                            @click="
                                                selectedIcon = icon.name;
                                                close();
                                            "
                                            :class="[
                                                'relative flex flex-col items-center gap-2 rounded-lg border p-3 transition-colors hover:bg-muted',
                                                selectedIcon === icon.name
                                                    ? 'border-primary bg-primary/5'
                                                    : 'border-transparent',
                                            ]"
                                        >
                                            <component :is="icon.component" class="h-6 w-6" />
                                            <span class="text-xs">{{ icon.label }}</span>
                                            <Check
                                                v-if="selectedIcon === icon.name"
                                                class="absolute top-1 right-1 h-4 w-4 text-primary"
                                            />
                                        </button>
                                    </div>
                                </PopoverContent>
                            </Popover>
                            <InputError :message="errors.icon" />
                            <!-- Hidden input para el form -->
                            <input type="hidden" name="icon" :value="selectedIcon" />
                        </div>
                        <div class="space-y-2">
                            <Label for="color">Color</Label>
                            <Input
                                id="color"
                                name="color"
                                :tabindex="5"
                                type="color"
                                required
                                class="h-12 w-full cursor-pointer"
                            />
                            <p class="text-muted-foreground">
                                Selecciona un color que represente este departamento
                            </p>
                            <InputError :message="errors.color" />
                        </div>
                    </CardContent>
                </Card>
                <div class="flex justify-end gap-4">
                    <Button :tabindex="6" variant="outline" as-child>
                        <Link :href="index().url"> Cancelar </Link>
                    </Button>
                    <Button
                        type="submit"
                        :tabindex="7"
                        :disabled="processing"
                        data-test="create-departamento-button"
                    >
                        <Spinner v-if="processing" />
                        {{ processing ? 'Creando...' : 'Crear Departamento' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
