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
import { create, edit, index } from '@/routes/admin/procedures';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Department {
    id: number;
    name: string;
}

interface Category {
    id: number;
    name: string;
    department: Department;
}

interface Procedure {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    modality: 'online' | 'presencial' | 'mixto';
    cost: string | null;
    duration: string | null;
    order: number;
    is_active: boolean;
    category: Category;
    requirements_count: number;
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
    response: PaginationResponse<Procedure>;
}>();

const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Trámites', href: index().url }];

// Verificar si hay error del servidor y mostrarlo
if (page.props.flash?.error) {
    toast.error(page.props.flash.error);
}

const deleteDialogOpen = ref(false);
const procedureToDelete = ref<Procedure | null>(null);

function openDeleteDialog(procedure: Procedure) {
    procedureToDelete.value = procedure;
    deleteDialogOpen.value = true;
}

let deleteToastId: string | number | null = null;

function confirmDelete() {
    if (procedureToDelete.value) {
        router.delete(`/admin/procedures/${procedureToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando trámite...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Trámite eliminado correctamente.');
                deleteDialogOpen.value = false;
                procedureToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el procedimiento.');
            },
        });
    }
}

const modalityBadge = (modality: string) => {
    const variants: Record<
        string,
        { label: string; variant: 'default' | 'secondary' | 'outline' }
    > = {
        online: { label: 'En línea', variant: 'default' },
        presencial: { label: 'Presencial', variant: 'secondary' },
        mixto: { label: 'Mixto', variant: 'outline' },
    };
    return variants[modality] || variants.presencial;
};
</script>

<template>
    <Head title="Trámites" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Trámites</CardTitle>
                    <Button as-child>
                        <Link :href="create().url">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Trámite
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Categoría</TableHead>
                                <TableHead>Unidad</TableHead>
                                <TableHead>Modalidad</TableHead>
                                <TableHead>Requisitos</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="procedure in response.data" :key="procedure.id">
                                <TableCell class="font-medium">{{ procedure.name }}</TableCell>
                                <TableCell>{{ procedure.category.name }}</TableCell>
                                <TableCell>{{ procedure.category.department.name }}</TableCell>
                                <TableCell>
                                    <Badge :variant="modalityBadge(procedure.modality).variant">
                                        {{ modalityBadge(procedure.modality).label }}
                                    </Badge>
                                </TableCell>
                                <TableCell>{{ procedure.requirements_count }}</TableCell>
                                <TableCell>
                                    <Badge :variant="procedure.is_active ? 'default' : 'secondary'">
                                        {{ procedure.is_active ? 'Activo' : 'Inactivo' }}
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
                                                <Link :href="edit(procedure.id).url">
                                                    <Pencil class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                class="text-destructive"
                                                @select="openDeleteDialog(procedure)"
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
                    :items-per-page="response.per_page"
                    :total="response.total"
                    :default-page="response.current_page"
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
                <AlertDialogTitle>¿Eliminar trámite?</AlertDialogTitle>
                <AlertDialogDescription>
                    Esta acción no se puede deshacer. Se eliminará permanentemente el trámite "{{
                        procedureToDelete?.name
                    }}".
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
