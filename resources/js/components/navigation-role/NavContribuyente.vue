<script setup lang="ts">
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import { FileText } from 'lucide-vue-next';

const page = usePage();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel class="h-10 text-base">Rentas Municipales</SidebarGroupLabel>
        <SidebarMenu>
            <SidebarMenuItem>
                <SidebarMenuButton
                    class="h-12 group-data-[collapsible=icon]:p-2!"
                    as-child
                    :is-active="page.url.startsWith('/contribuyente/patentes')"
                    :tooltip="'Solicitud de Patente'"
                >
                    <Link href="/contribuyente/patentes">
                        <component :is="FileText" />
                        <span class="text-lg">Solicitud de Patente</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>
</template>
