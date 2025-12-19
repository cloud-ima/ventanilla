<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { urlIsActive } from '@/lib/utils';
import { dashboard } from '@/routes/funcionario/rentas';
import { index as indexForms } from '@/routes/funcionario/rentas/forms';
import { index as indexPatentes } from '@/routes/funcionario/rentas/patentes';
import { index as indexExternalDocument } from '@/routes/funcionario/rentas/requirements';
import { Link, usePage } from '@inertiajs/vue3';
import { ClipboardCheck, FileText, Inbox, LayoutDashboardIcon } from 'lucide-vue-next';

const page = usePage();
</script>

<template>
    <SidebarGroup>
        <SidebarGroupLabel>Panel</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton
                    class="h-12 group-data-[collapsible=icon]:p-2!"
                    as-child
                    :is-active="urlIsActive(dashboard(), page.url)"
                    :tooltip="'Dashboard'"
                >
                    <Link :href="dashboard()">
                        <component :is="LayoutDashboardIcon" />
                        <span class="text-lg">Dashboard</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
    <SidebarGroup>
        <SidebarGroupLabel>Gestión de Patentes</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton
                    class="h-12 group-data-[collapsible=icon]:p-2!"
                    as-child
                    :is-active="urlIsActive(indexPatentes(), page.url)"
                    :tooltip="'Solicitudes'"
                >
                    <Link :href="indexPatentes()">
                        <component :is="Inbox" />
                        <span class="text-lg">Solicitudes</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Administración para Patentes</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton
                    as-child
                    :is-active="urlIsActive(indexForms(), page.url)"
                    :tooltip="'Formularios'"
                    class="h-12 group-data-[collapsible=icon]:p-2!"
                >
                    <Link :href="indexForms()">
                        <component :is="FileText" />
                        <span class="text-lg">Formularios</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
            <SidebarMenuItem>
                <SidebarMenuButton
                    as-child
                    :is-active="urlIsActive(indexExternalDocument(), page.url)"
                    :tooltip="'Requerimientos'"
                    class="h-12 group-data-[collapsible=icon]:p-2!"
                >
                    <Link :href="indexExternalDocument()">
                        <component :is="ClipboardCheck" />
                        <span class="text-lg">Requerimientos</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
