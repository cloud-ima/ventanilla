<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Pagination,
    PaginationContent,
    PaginationItem,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes/funcionario/rentas';
import { create, edit, index } from '@/routes/funcionario/rentas/requirements';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface PatentRequirement {
    id: number;
    code: string;
    name: string;
    description: string | null;
    category: string;
    where_to_obtain: string;
    obtain_address: string | null;
    obtain_phone: string | null;
    info_url: string | null;
    validity_days: number | null;
    is_active: boolean;
    created_at: string;
}

interface PaginationResponse<T> {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    current_page_url: string;
    first_page_url: string;
    last_page_url: string;
    next_page_url: string | null;
    prev_page_url: string | null;
    path: string;
    from: number;
    to: number;
    data: T[];
}

defineProps<{
    requirements: PaginationResponse<PatentRequirement>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Requerimientos',
        href: index().url,
    },
];

const deleteDialogOpen = ref(false);
const requirementToDelete = ref<PatentRequirement | null>(null);
let deleteToastId: string | number | null = null;

// Funciones
function openDeleteDialog(requirement: PatentRequirement) {
    requirementToDelete.value = requirement;
    deleteDialogOpen.value = true;
}

function confirmDelete() {
    if (requirementToDelete.value) {
        router.delete(`/funcionario/rentas/requirements/${requirementToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando requerimiento...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Requerimiento eliminado correctamente.');
                deleteDialogOpen.value = false;
                requirementToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el requerimiento.');
            },
        });
    }
}

function getCategoryLabel(category: string) {
    const categories = [
        { value: '', label: 'Todas las categorías' },
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
    const cat = categories.find((c) => c.value === category);
    return cat ? cat.label : category;
}
</script>

<template>
    <Head title="Requerimientos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-2xl">Requerimientos</CardTitle>
                    <Button as-child>
                        <Link :href="create()">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Requerimiento
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <!-- Tabla de Requerimientos -->
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Código</TableHead>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Categoría</TableHead>
                                <TableHead>Descripción</TableHead>
                                <TableHead>Dónde obtener</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="requirement in requirements.data"
                                :key="requirement.id"
                            >
                                <TableCell class="font-medium">{{ requirement.code }}</TableCell>
                                <TableCell class="font-medium">{{ requirement.name }}</TableCell>
                                <TableCell>
                                    <Badge variant="outline">
                                        {{ getCategoryLabel(requirement.category) }}
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm text-gray-600">
                                        {{ requirement.description || '-' }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm">
                                        {{ requirement.where_to_obtain }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="requirement.is_active ? 'default' : 'secondary'"
                                    >
                                        {{ requirement.is_active ? 'Activo' : 'Inactivo' }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="icon">
                                                <MoreHorizontal class="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem as-child>
                                                <Link :href="edit(requirement.id).url">
                                                    <Pencil class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                class="text-destructive"
                                                @select="openDeleteDialog(requirement)"
                                            >
                                                <Trash2 class="mr-2 h-4 w-4" />
                                                Eliminar
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
            <!-- Pagination Controls -->
            <div class="mt-4 flex items-center justify-between px-2">
                <Pagination
                    v-slot="{ page }"
                    :items-per-page="requirements.per_page"
                    :total="requirements.total"
                    :default-page="requirements.current_page"
                    @update:page="
                        (newPage) =>
                            router.get(
                                index().url,
                                { page: newPage },
                                { preserveState: true, preserveScroll: true },
                            )
                    "
                >
                    <PaginationContent v-slot="{ items }">
                        <PaginationPrevious />
                        <template v-for="(item, index) in items" :key="index">
                            <PaginationItem
                                v-if="item.type === 'page'"
                                :value="item.value"
                                :is-active="item.value === page"
                            >
                                {{ item.value }}
                            </PaginationItem>
                        </template>
                        <PaginationNext />
                    </PaginationContent>
                </Pagination>
            </div>
        </div>
    </AppLayout>
    <AlertDialog v-model:open="deleteDialogOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>¿Eliminar requerimiento?</AlertDialogTitle>
                <AlertDialogDescription>
                    Esta acción no se puede deshacer. Se eliminará permanentemente el requerimiento
                    "{{ requirementToDelete?.name }}".
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>Cancelar</AlertDialogCancel>
                <AlertDialogAction
                    class="bg-destructive hover:bg-destructive/70 focus:ring-destructive/50"
                    @click="confirmDelete"
                >
                    Eliminar
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
