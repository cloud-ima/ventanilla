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
import { create, edit, index } from '@/routes/funcionario/rentas/forms';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Download, MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface PatentForm {
    id: number;
    name: string;
    description: string | null;
    template_file: string;
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
    forms: PaginationResponse<PatentForm>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Formularios',
        href: index().url,
    },
];

const deleteDialogOpen = ref(false);
const formToDelete = ref<PatentForm | null>(null);

function openDeleteDialog(form: PatentForm) {
    formToDelete.value = form;
    deleteDialogOpen.value = true;
}

let deleteToastId: string | number | null = null;

function confirmDelete() {
    if (formToDelete.value) {
        router.delete(`/funcionario/rentas/forms/${formToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando formulario...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Formulario eliminado correctamente.');
                deleteDialogOpen.value = false;
                formToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el formulario.');
            },
        });
    }
}

function downloadTemplate(form: PatentForm) {
    window.open(`/storage/${form.template_file}`, '_blank');
}
</script>

<template>
    <Head title="Formularios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Formularios</CardTitle>
                    <Button as-child>
                        <Link :href="create()">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Formulario
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Descripción</TableHead>
                                <TableHead>Archivo</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="form in forms.data" :key="form.id">
                                <TableCell class="font-medium">{{ form.name }}</TableCell>
                                <TableCell>
                                    <span class="text-sm text-gray-600">
                                        {{ form.description || '-' }}
                                    </span>
                                </TableCell>
                                <TableCell>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="downloadTemplate(form)"
                                    >
                                        <Download class="mr-2 h-4 w-4" />
                                        Descargar
                                    </Button>
                                </TableCell>
                                <TableCell>
                                    <Badge :variant="form.is_active ? 'default' : 'secondary'">
                                        {{ form.is_active ? 'Activo' : 'Inactivo' }}
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
                                                <Link :href="edit(form.id).url">
                                                    <Pencil class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                class="text-destructive"
                                                @select="openDeleteDialog(form)"
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
                    :items-per-page="forms.per_page"
                    :total="forms.total"
                    :default-page="forms.current_page"
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
                <AlertDialogTitle>¿Eliminar formulario?</AlertDialogTitle>
                <AlertDialogDescription>
                    Esta acción no se puede deshacer. Se eliminará permanentemente el formulario "{{
                        formToDelete?.name
                    }}" y su archivo asociado.
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
