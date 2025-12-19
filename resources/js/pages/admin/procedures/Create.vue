<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
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
import { create, index, store } from '@/routes/admin/procedures';
import { type BreadcrumbItem } from '@/types';
import { Form, Head, Link } from '@inertiajs/vue3';
import { Plus, X } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Trámites', href: index().url },
    { title: 'Crear Trámite', href: create().url },
];

interface Department {
    id: number;
    name: string;
}

interface Category {
    id: number;
    name: string;
    department: Department;
}

interface Requirement {
    id: number;
    name: string;
}

const categoriesByDepartment = computed(() => {
    const grouped: Record<string, Category[]> = {};
    props.categories.forEach((cat) => {
        const deptName = cat.department.name;
        if (!grouped[deptName]) grouped[deptName] = [];
        grouped[deptName].push(cat);
    });
    return grouped;
});

const props = defineProps<{
    categories: Category[];
    requirements: Requirement[];
}>();

const selectedRequirement = ref<{ id: number; is_mandatory: boolean; order: number }[]>([]);

const userRequeriments = ref<string[]>([]);
const instructions = ref<string[]>([]);

function addUserRequeriment() {
    userRequeriments.value.push('');
}

function removeUserRequeriment(index: number) {
    userRequeriments.value.splice(index, 1);
}

function addInstruction() {
    instructions.value.push('');
}

function removeInstruction(index: number) {
    instructions.value.splice(index, 1);
}

function isRequirementSelected(requirementId: number): boolean {
    return selectedRequirement.value.some((r) => r.id === requirementId);
}

function toggleRequirement(requirementId: number, checked: boolean | 'indeterminate') {
    const index = selectedRequirement.value.findIndex((r) => r.id === requirementId);
    const isChecked = checked === true;

    if (isChecked && index < 0) {
        selectedRequirement.value.push({
            id: requirementId,
            is_mandatory: true,
            order: selectedRequirement.value.length,
        });
    } else if (!isChecked && index >= 0) {
        selectedRequirement.value.splice(index, 1);
    }
}

function getRequirementMandatory(requirementId: number): boolean {
    const req = selectedRequirement.value.find((r) => r.id === requirementId);
    return req?.is_mandatory ?? true;
}

function setRequirementMandatory(requirementId: number, value: boolean) {
    const req = selectedRequirement.value.find((r) => r.id === requirementId);
    if (req) {
        req.is_mandatory = value;
    }
}
</script>

<template>
    <Head title="Crear Trámite" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <!-- Header -->
            <div>
                <h1 class="text-xl font-bold text-gray-900">Crear Trámite</h1>
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
                <!-- Información General -->
                <Card>
                    <CardHeader>
                        <CardTitle>Información General</CardTitle>
                        <p class="text-gray-500">Datos básicos del trámite</p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <Label for="name"
                                >Nombre del trámite<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="name"
                                name="name"
                                type="text"
                                :tabindex="1"
                                placeholder="Ej: Patente Comercial Definitiva"
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
                                placeholder="Descripción del trámite..."
                                :aria-invalid="!!errors.description"
                            />
                            <InputError :message="errors.description" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Clasificación -->
                <Card>
                    <CardHeader>
                        <CardTitle>Clasificación</CardTitle>
                        <p class="text-gray-500">Categoría y modalidad del trámite</p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Categoría -->
                        <div class="space-y-2">
                            <Label for="category_id"
                                >Categoría<span class="text-red-500">*</span></Label
                            >
                            <Select name="category_id">
                                <SelectTrigger id="category_id" :tabindex="3">
                                    <SelectValue placeholder="Seleccionar categoría" />
                                </SelectTrigger>
                                <SelectContent>
                                    <template
                                        v-for="(cats, deptName) in categoriesByDepartment"
                                        :key="deptName"
                                    >
                                        <div
                                            class="px-2 py-1.5 font-semibold text-muted-foreground"
                                        >
                                            {{ deptName }}
                                        </div>
                                        <SelectItem
                                            v-for="cat in cats"
                                            :key="cat.id"
                                            :value="cat.id"
                                        >
                                            {{ cat.name }}
                                        </SelectItem>
                                    </template>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.category_id" />
                        </div>
                        <!-- Modalidad -->
                        <div class="space-y-2">
                            <Label for="modality"
                                >Modalidad<span class="text-red-500">*</span></Label
                            >
                            <Select name="modality">
                                <SelectTrigger id="modality" :tabindex="4">
                                    <SelectValue placeholder="Seleccionar modalidad" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="online">En línea</SelectItem>
                                    <SelectItem value="presencial">Presencial</SelectItem>
                                    <SelectItem value="mixto">Mixto</SelectItem>
                                </SelectContent>
                            </Select>
                            <InputError :message="errors.modality" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Detalles del Servicio -->
                <Card>
                    <CardHeader>
                        <CardTitle>Detalles del Servicio</CardTitle>
                        <p class="text-gray-500">Costo, duración y estado del trámite</p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Costo -->
                        <div class="space-y-2">
                            <Label for="cost">Costo<span class="text-red-500">*</span></Label>
                            <Input
                                id="cost"
                                :tabindex="5"
                                name="cost"
                                type="text"
                                placeholder="Ej: 1 UTM, Gratuito"
                            />
                            <InputError :message="errors.cost" />
                        </div>
                        <!-- Duración -->
                        <div class="space-y-2">
                            <Label for="duration"
                                >Duración<span class="text-red-500">*</span></Label
                            >
                            <Input
                                id="duration"
                                :tabindex="6"
                                name="duration"
                                type="text"
                                placeholder="Ej: 5 a 10 días hábiles"
                            />
                            <InputError :message="errors.duration" />
                        </div>
                        <!-- Orden -->
                        <div class="space-y-2">
                            <Label for="order">Orden<span class="text-red-500">*</span></Label>
                            <Input
                                id="order"
                                :tabindex="7"
                                name="order"
                                type="number"
                                min="0"
                                :default-value="0"
                            />
                            <InputError :message="errors.order" />
                        </div>
                        <!-- Activo -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <Switch
                                    :tabindex="8"
                                    id="is_active"
                                    name="is_active"
                                    :default-value="true"
                                />
                                <Label for="is_active">Activo</Label>
                                <InputError :message="errors.is_active" />
                            </div>
                            <p class="text-sm text-gray-500">
                                Esta opción controla si el trámite será visible en el portal
                                público. Si se desactiva, el trámite no se eliminará pero no
                                aparecerá para los contribuyentes.
                            </p>
                        </div>
                    </CardContent>
                </Card>
                <!-- Requisitos del Trámite -->
                <Card>
                    <CardHeader>
                        <CardTitle>Requisitos del Trámite</CardTitle>
                        <p class="text-gray-500">Documentos y condiciones necesarios</p>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Documentos Requeridos -->
                        <div class="space-y-4">
                            <Label as="div">Documentos<span class="text-red-500">*</span></Label>
                            <div
                                class="max-h-96 space-y-3 overflow-y-auto rounded-lg border p-4 focus:ring-2 focus:ring-primary focus:outline-none"
                                :tabindex="9"
                            >
                                <div
                                    v-for="requiremnt in requirements"
                                    :key="requiremnt.id"
                                    class="flex items-center justify-between gap-4 rounded p-2 hover:bg-muted/50"
                                >
                                    <!-- Checkbox y nombre -->
                                    <div class="flex items-center gap-3">
                                        <Checkbox
                                            :modelValue="isRequirementSelected(requiremnt.id)"
                                            @update:modelValue="
                                                (checked) =>
                                                    toggleRequirement(requiremnt.id, checked)
                                            "
                                        />
                                        <span>{{ requiremnt.name }}</span>
                                    </div>

                                    <!-- Switch "Obligatorio" -->
                                    <div
                                        v-if="isRequirementSelected(requiremnt.id)"
                                        class="flex items-center gap-2"
                                    >
                                        <Label class="text-muted-foreground"> Obligatorio </Label>
                                        <Switch
                                            :modelValue="getRequirementMandatory(requiremnt.id)"
                                            @update:modelValue="
                                                (value) =>
                                                    setRequirementMandatory(requiremnt.id, value)
                                            "
                                        />
                                    </div>
                                </div>
                                <p
                                    v-if="requirements.length === 0"
                                    class="py-4 text-center text-muted-foreground"
                                >
                                    No hay requisitos disponibles. Crea algunos primero.
                                </p>
                            </div>
                            <InputError :message="errors.requirements" />
                        </div>
                        <!-- Requisitos de usuario -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <Label
                                    >Condiciones del Usuario<span class="text-red-500"
                                        >*</span
                                    ></Label
                                >
                                <Button
                                    :tabindex="10"
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    @click="addUserRequeriment"
                                >
                                    <Plus class="mr-1 h-4 w-4" />
                                    Agregar
                                </Button>
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="(_, index) in userRequeriments"
                                    :key="index"
                                    class="flex items-center gap-2"
                                >
                                    <Input
                                        v-model="userRequeriments[index]"
                                        :name="`user_requirements[${index}]`"
                                        placeholder="Ej: Ser mayor de 18 años"
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon"
                                        @click="removeUserRequeriment(index)"
                                    >
                                        <X class="h-4 w-4" />
                                    </Button>
                                </div>
                                <p
                                    v-if="userRequeriments.length === 0"
                                    class="text-muted-foreground"
                                >
                                    Sin condiciones del usuario definidas.
                                </p>
                            </div>
                            <InputError :message="errors.user_requirements" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Proceso del Trámite -->
                <Card>
                    <CardHeader>
                        <CardTitle>Proceso del Trámite</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <!-- Instrucciones paso a paso -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <Label
                                    >Instrucciones Paso a Paso<span class="text-red-500"
                                        >*</span
                                    ></Label
                                >
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    :tabindex="11"
                                    @click="addInstruction"
                                >
                                    <Plus class="mr-1 h-4 w-4" />
                                    Agregar paso
                                </Button>
                            </div>
                            <div class="space-y-2">
                                <div
                                    v-for="(_, index) in instructions"
                                    :key="index"
                                    class="flex items-center gap-2"
                                >
                                    <span class="w-6 text-muted-foreground">{{ index + 1 }}.</span>
                                    <Input
                                        v-model="instructions[index]"
                                        :name="`instructions[${index}]`"
                                        placeholder="Descripción del paso..."
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon"
                                        @click="removeInstruction(index)"
                                    >
                                        <X class="h-4 w-4" />
                                    </Button>
                                </div>
                                <p v-if="instructions.length === 0" class="text-muted-foreground">
                                    Sin instrucciones definidas.
                                </p>
                            </div>
                            <InputError :message="errors.instructions" />
                        </div>
                        <!-- Marco Legal -->
                        <div class="space-y-2">
                            <Label for="legal_framework">Marco Legal</Label>
                            <Textarea
                                id="legal_framework"
                                name="legal_framework"
                                :tabindex="12"
                                placeholder="Ley, decreto o normativa que respalda este trámite..."
                                rows="2"
                            />
                            <InputError :message="errors.marco_legal" />
                        </div>
                        <!-- Hidden inputs para requisitos seleccionados -->
                        <template v-for="(req, index) in selectedRequirement" :key="req.id">
                            <input
                                type="hidden"
                                :name="`requirements[${index}][id]`"
                                :value="req.id"
                            />
                            <input
                                type="hidden"
                                :name="`requirements[${index}][is_mandatory]`"
                                :value="req.is_mandatory ? '1' : '0'"
                            />
                        </template>
                    </CardContent>
                </Card>
                <!-- Botones -->
                <div class="flex justify-end gap-4">
                    <Button variant="outline" as-child :tabindex="13">
                        <Link :href="index()"> Cancelar </Link>
                    </Button>
                    <Button type="submit" :disabled="processing" :tabindex="14">
                        <Spinner v-if="processing" />
                        {{ processing ? 'Creando...' : 'Crear Trámite' }}
                    </Button>
                </div>
            </Form>
        </div>
    </AppLayout>
</template>
