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
import { getIconComponent } from '@/config/available-icon';
import AppLayout from '@/layouts/AppLayout.vue';
import { create, edit, index } from '@/routes/admin/departments';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Department {
    id: number;
    name: string;
    slug: string;
    color: string;
    icon: string;
    categories_count: number;
    users_count: number;
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
    response: PaginationResponse<Department>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Departamentos',
        href: index().url,
    },
];

const deleteDialogOpen = ref(false);
const departmentToDelete = ref<Department | null>(null);

function openDeleteDialog(department: Department) {
    departmentToDelete.value = department;
    deleteDialogOpen.value = true;
}

let deleteToastId: string | number | null = null;

function confirmDelete() {
    if (departmentToDelete.value) {
        router.delete(`/admin/departments/${departmentToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando departamento...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Departamento eliminado correctamente.');
                deleteDialogOpen.value = false;
                departmentToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el departamento.');
            },
        });
    }
}
</script>

<template>
    <Head title="Departamentos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Departamentos</CardTitle>
                    <Button as-child>
                        <Link :href="create()">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Departamento
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Color</TableHead>
                                <TableHead>Icono</TableHead>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Slug</TableHead>
                                <TableHead>Categorías</TableHead>
                                <TableHead>Funcionarios</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="department in response.data" :key="department.id">
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="h-8 w-8 rounded border border-gray-200"
                                            :style="{
                                                backgroundColor: department.color,
                                            }"
                                        />
                                        <span class="font-mono text-xs text-muted-foreground">{{
                                            department.color
                                        }}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <component
                                        :is="getIconComponent(department.icon)"
                                        class="h-6 w-6"
                                    />
                                </TableCell>
                                <TableCell class="font-medium">{{ department.name }}</TableCell>
                                <TableCell>{{ department.slug }}</TableCell>
                                <TableCell>
                                    {{ department.categories_count }}
                                </TableCell>
                                <TableCell>
                                    {{ department.users_count }}
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
                                                <Link :href="edit(department.id).url">
                                                    <Pencil class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                class="text-destructive"
                                                @select="openDeleteDialog(department)"
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
                <AlertDialogTitle>¿Eliminar departamento?</AlertDialogTitle>
                <AlertDialogDescription>
                    <template
                        v-if="
                            (departmentToDelete?.categories_count ?? 0) > 0 ||
                            (departmentToDelete?.users_count ?? 0) > 0
                        "
                    >
                        <strong class="text-destructive">No se puede eliminar</strong>
                        el departamento "{{ departmentToDelete?.name }}" porque:
                        <ul class="mt-2 list-disc pl-5">
                            <li v-if="(departmentToDelete?.categories_count ?? 0) > 0">
                                Tiene
                                {{ departmentToDelete?.categories_count }}
                                categoría(s) asociada(s)
                            </li>
                            <li v-if="(departmentToDelete?.users_count ?? 0) > 0">
                                Tiene
                                {{ departmentToDelete?.users_count }} usuario(s) asignado(s)
                            </li>
                        </ul>
                        <p class="mt-2">
                            Elimina o reasigna primero todos los recursos asociados antes de
                            eliminar este departamento.
                        </p>
                    </template>
                    <template v-else>
                        Esta acción no se puede deshacer. Se eliminará permanentemente el
                        departamento "{{ departmentToDelete?.name }}".
                    </template>
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>
                    {{
                        (departmentToDelete?.categories_count ?? 0) > 0 ||
                        (departmentToDelete?.users_count ?? 0) > 0
                            ? 'Entendido'
                            : 'Cancelar'
                    }}
                </AlertDialogCancel>
                <AlertDialogAction
                    v-if="
                        (departmentToDelete?.categories_count ?? 0) === 0 &&
                        (departmentToDelete?.users_count ?? 0) === 0
                    "
                    class="bg-destructive hover:bg-destructive/70 focus:ring-destructive/50"
                    @click="confirmDelete"
                >
                    Eliminar
                </AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
