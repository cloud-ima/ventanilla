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
import { create, edit, index } from '@/routes/admin/requirements';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Requirement {
    id: number;
    name: string;
    description: string | null;
    how_to_obtain: string | null;
    procedures_count: number;
}

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
    response: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Requisitos', href: index().url }];

const page = usePage();

// Verificar si hay error del servidor y mostrarlo
if (page.props.flash?.error) {
    toast.error(page.props.flash.error);
}

const deleteDialogOpen = ref(false);
const requirementToDelete = ref<Requirement | null>(null);

function openDeleteDialog(requirement: Requirement) {
    requirementToDelete.value = requirement;
    deleteDialogOpen.value = true;
}

let deleteToastId: string | number | null = null;

function confirmDelete() {
    if (requirementToDelete.value) {
        router.delete(`/admin/requirements/${requirementToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando requisito...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Requisito eliminado correctamente.');
                deleteDialogOpen.value = false;
                requirementToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el requisito.');
            },
        });
    }
}
</script>

<template>
    <Head title="Requisitos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Requisitos</CardTitle>
                    <Button as-child>
                        <Link :href="create()">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Requisito
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <Table v-if="response.data.length">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Descripción</TableHead>
                                <TableHead>Usado en</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="requirement in response.data" :key="requirement.id">
                                <TableCell class="font-medium">{{ requirement.name }}</TableCell>
                                <TableCell class="max-w-xs truncate">
                                    {{ requirement.description || '-' }}
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline">
                                        {{ requirement.procedures_count }} trámites
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
                                                <Link :href="edit(requirement.id)">
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
                <AlertDialogTitle>¿Eliminar requisito?</AlertDialogTitle>
                <AlertDialogDescription>
                    <template v-if="(requirementToDelete?.procedures_count ?? 0) > 0">
                        <strong class="text-destructive">No se puede eliminar</strong>
                        el requisito "{{ requirementToDelete?.name }}" porque está siendo usado por
                        {{ requirementToDelete?.procedures_count }}
                        trámite(s).
                        <br /><br />
                        Para eliminarlo, primero debes quitarlo de todos los trámites que lo usan.
                    </template>
                    <template v-else>
                        Esta acción no se puede deshacer. Se eliminará permanentemente el requisito
                        "{{ requirementToDelete?.name }}".
                    </template>
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>
                    {{
                        (requirementToDelete?.procedures_count ?? 0) > 0 ? 'Entendido' : 'Cancelar'
                    }}
                </AlertDialogCancel>
                <AlertDialogAction
                    v-if="(requirementToDelete?.procedures_count ?? 0) === 0"
                    class="bg-destructive hover:bg-destructive/70 focus:ring-destructive/50"
                    @click="confirmDelete"
                >
                    Eliminar
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
