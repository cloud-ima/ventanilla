<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import SidebarGroupContent from '@/components/ui/sidebar/SidebarGroupContent.vue';
import { urlIsActive } from '@/lib/utils';
import * as routeCategories from '@/routes/admin/categories';
import * as routeDepartments from '@/routes/admin/departments';
import * as routeProcedures from '@/routes/admin/procedures';
import * as routeFunctionaries from '@/routes/admin/public-officials';
import * as routeRequirements from '@/routes/admin/requirements';
import { Link, usePage } from '@inertiajs/vue3';
import { Building2, FileText, FolderTree, ListChecks, Users } from 'lucide-vue-next';

const page = usePage();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel class="h-10 text-base">Administración</SidebarGroupLabel>
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="h-12 group-data-[collapsible=icon]:p-2!"
                        as-child
                        :is-active="urlIsActive(routeFunctionaries.index(), page.url)"
                        tooltip="Funcionarios"
                    >
                        <Link :href="routeFunctionaries.index().url">
                            <component :is="Users" />
                            <span class="text-lg">Funcionarios</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="h-12 group-data-[collapsible=icon]:p-2!"
                        as-child
                        :is-active="urlIsActive(routeDepartments.index(), page.url)"
                        tooltip="Departamentos"
                    >
                        <Link :href="routeDepartments.index().url">
                            <component :is="Building2" />
                            <span class="text-lg">Departamentos</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
    <SidebarGroup>
        <SidebarGroupLabel class="h-10 text-base">Gestión Portal</SidebarGroupLabel>
        <SidebarGroupContent>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="h-12 group-data-[collapsible=icon]:p-2!"
                        as-child
                        :is-active="urlIsActive(routeCategories.index(), page.url)"
                        tooltip="Categorías"
                    >
                        <Link :href="routeCategories.index().url">
                            <component :is="FolderTree" />
                            <span class="text-lg">Categorías</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="h-12 group-data-[collapsible=icon]:p-2!"
                        as-child
                        :is-active="urlIsActive(routeProcedures.index(), page.url)"
                        tooltip="Trámites"
                    >
                        <Link :href="routeProcedures.index()">
                            <component :is="FileText" />
                            <span class="text-lg">Trámites</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        class="h-12 group-data-[collapsible=icon]:p-2!"
                        as-child
                        :is-active="urlIsActive(routeRequirements.index(), page.url)"
                        tooltip="Requisitos"
                    >
                        <Link :href="routeRequirements.index()">
                            <component :is="ListChecks" />
                            <span class="text-lg">Requisitos</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarGroupContent>
    </SidebarGroup>
</template>
