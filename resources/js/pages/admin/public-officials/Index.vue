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
import { create, edit, index } from '@/routes/admin/public-officials';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { MoreHorizontal, Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

interface Department {
    id: number;
    name: string;
    slug: string;
}

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    is_active: boolean;
    department?: Department;
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
    response: PaginationResponse<User>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Funcionarios', href: index().url }];
const deleteDialogOpen = ref(false);
const publicOfficialToDelete = ref<User | null>(null);

function openDeleteDialog(user: User) {
    publicOfficialToDelete.value = user;
    deleteDialogOpen.value = true;
}

let deleteToastId: string | number | null = null;

function confirmDelete() {
    if (publicOfficialToDelete.value) {
        router.delete(`/admin/public-officials/${publicOfficialToDelete.value.id}`, {
            preserveScroll: true,
            onStart: () => {
                deleteToastId = toast.loading('Eliminando funcionario...');
            },
            onSuccess: () => {
                toast.dismiss(deleteToastId!);
                toast.success('Funcionario eliminado correctamente.');
                deleteDialogOpen.value = false;
                publicOfficialToDelete.value = null;
            },
            onError: () => {
                toast.dismiss(deleteToastId!);
                toast.error('Error al eliminar el funcionario.');
            },
        });
    }
}
</script>

<template>
    <Head title="Funcionarios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <Card>
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle>Funcionarios</CardTitle>
                    <Button as-child>
                        <Link :href="create()">
                            <Plus class="mr-2 h-4 w-4" />
                            Nuevo Funcionario
                        </Link>
                    </Button>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Departamento</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead class="text-right">Acciones</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="user in response.data" :key="user.id">
                                <TableCell class="font-medium">{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>{{ user.department?.name ?? 'Sin asignar' }}</TableCell>
                                <TableCell>
                                    <Badge :variant="user.is_active ? 'default' : 'secondary'">
                                        {{ user.is_active ? 'Activo' : 'Inactivo' }}
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
                                                <Link :href="edit(user.id).url">
                                                    <Pencil class="mr-2 h-4 w-4" />
                                                    Editar
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem
                                                class="text-destructive"
                                                @select="openDeleteDialog(user)"
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
                <AlertDialogTitle>¿Eliminar funcionario?</AlertDialogTitle>
                <AlertDialogDescription>
                    Esta acción no se puede deshacer. Se eliminará permanentemente el funcionario
                    "{{ publicOfficialToDelete?.name }}".
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
