<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavAdmin from '@/components/navigation-role/NavAdmin.vue';
import NavContribuyente from '@/components/navigation-role/NavContribuyente.vue';
import NavFuncionarioRentas from '@/components/navigation-role/rentas/NavFuncionarioRentas.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, home } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { Globe } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage();

const { slug } = page.props.auth.user.department ?? { slug: '' };
const user = page.props.auth.user;

const footerNavItems: NavItem[] = [
    {
        title: 'Portal Ventanilla Única',
        href: home(),
        icon: Globe,
    },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton
                        size="xl"
                        as-child
                        class="fill-primary-foreground hover:fill-primary active:fill-primary"
                    >
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavAdmin v-if="user.role === 'admin'" />
            <template v-else-if="user.role === 'funcionario'">
                <NavFuncionarioRentas v-if="slug === 'rentas'" />
            </template>
            <NavContribuyente v-else-if="user.role === 'contribuyente'" />
        </SidebarContent>
        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
