<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { dashboard, home, login } from '@/routes';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth?.user;

// Links de transparencia
const transparenciaLinks = [
    {
        title: 'DATOS ABIERTOS',
        subtitle: 'Ley de Transparencia',
        href: 'https://datos.gob.cl/organization/municipalidad_de_arica',
    },
    {
        title: 'TRANSPARENCIA ACTIVA',
        subtitle: 'Ley de Transparencia',
        href: 'https://www.portaltransparencia.cl/PortalPdT/pdtta?codOrganismo=MU012',
    },
    {
        title: 'PLATAFORMA',
        subtitle: 'Ley de Lobby',
        href: 'https://www.leylobby.gob.cl/instituciones/MU012',
    },
    {
        title: 'SOLICITAR INFORMACIÓN',
        subtitle: 'Ley de Transparencia',
        href: 'https://www.portaltransparencia.cl/PortalPdT/web/guest/directorio-de-organismos-regulados?p_p_id=pdtorganismos_WAR_pdtorganismosportlet&orgcode=9b754cd45ececd0138f367362452894d',
    },
    { title: 'LICITACIONES', subtitle: 'Públicas', href: 'https://www.mercadopublico.cl/' },
];
</script>

<template>
    <!-- Barra superior de transparencia (no sticky, se oculta al scroll) -->
    <div class="hidden bg-[#06048c] md:block">
        <div class="">
            <div class="flex justify-between">
                <!-- Links de transparencia -->
                <div class="flex flex-1 items-center gap-4 md:pl-[70px]">
                    <a
                        v-for="link in transparenciaLinks"
                        :key="link.title"
                        :href="link.href"
                        target="_blank"
                        class="flex flex-1 items-center gap-4 py-2 transition-colors"
                    >
                        <span class="h-8 w-[3px] bg-white" aria-hidden="true"></span>
                        <div class="text-white">
                            <div class="text-[13px]">{{ link.title }}</div>
                            <div class="text-[13px] font-bold">
                                {{ link.subtitle }}
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Streaming / Arica Segura -->
                <div class="flex">
                    <a
                        href="#"
                        class="flex items-center gap-2 bg-[#02bb94] px-[30px] py-2 text-white"
                    >
                        <img src="/logo-streaming.svg" class="size-10" alt="Arica Segura" />
                        <div>
                            <div class="text-[13px] text-white">STREAMING</div>
                            <div class="text-xs font-bold text-[#06048c]">Concejo Municipal</div>
                        </div>
                    </a>
                    <div class="flex h-full items-center gap-2 bg-[#00b9f1] px-[30px] py-2">
                        <img src="/logo-arica-segura.svg" class="size-10" alt="Arica Segura" />
                        <div class="flex flex-col">
                            <span class="text-[13px] text-white">ARICA SEGURA</span>
                            <span class="text-lg font-bold text-[#06048c]">1526</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header principal (sticky, se mantiene al scroll) -->
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex h-16 items-center justify-between">
                <!-- Logo / Título -->
                <div class="flex items-center gap-3">
                    <Link :href="home()" class="flex flex-col">
                        <span class="text-lg font-bold text-[#06048c]">Ventanilla Única</span>
                    </Link>
                </div>

                <!-- Navegación -->
                <nav class="flex items-center gap-4">
                    <template v-if="user">
                        <Link
                            :href="dashboard()"
                            class="font-medium text-gray-700 transition-colors hover:text-[#06048c]"
                        >
                            Mis Trámites
                        </Link>
                        <Button as-child class="rounded-full bg-[#06048c] px-6 hover:bg-[#050370]">
                            <Link :href="dashboard()">Ir al Panel</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Link
                            :href="login()"
                            class="font-medium text-gray-700 transition-colors hover:text-[#06048c]"
                        >
                            Mis Trámites
                        </Link>
                        <Button as-child class="rounded-full bg-[#06048c] px-6 hover:bg-[#050370]">
                            <Link :href="login()">Iniciar Sesión</Link>
                        </Button>
                    </template>
                </nav>
            </div>
        </div>
    </header>
</template>
