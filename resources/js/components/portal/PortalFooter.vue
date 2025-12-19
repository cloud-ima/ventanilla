<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Facebook, Instagram, MapPin, Phone, Youtube } from 'lucide-vue-next';

const socialLinks = [
    {
        icon: Facebook,
        href: 'https://www.facebook.com/MuniArica',
        label: 'Facebook',
    },
    {
        icon: Instagram,
        href: 'https://www.instagram.com/muniarica',
        label: 'Instagram',
    },
    {
        icon: Youtube,
        href: 'https://www.youtube.com/@MuniArica',
        label: 'YouTube',
    },
];

const quickLinks = [
    { title: 'Municipio', href: 'https://www.muniarica.cl/municipio/unidades-municipales' },
    { title: 'Seguridad', href: 'https://www.muniarica.cl/seguridad' },
    { title: 'Noticias', href: 'https://www.muniarica.cl/noticias' },
    { title: 'Contacto', href: 'mailto:comunicaciones@municipalidadarica.cl' },
];

const legalLinks = [
    { title: 'Intranet Municipal', href: 'https://intranet.imaarica.cl/' },
    { title: 'Intranet RRHH', href: 'https://intranetarica.smc.cl/login.aspx?ReturnUrl=%2f' },
    { title: 'WebMail', href: 'https://workspace.google.com/intl/es-419/gmail/' },
    {
        title: 'Mapa de sitio',
        href: 'https://docs.muniarica.cl/web/media/documentos/2025/10/mapa-navegacion-municipalidad-arica.pdf',
    },
    {
        title: 'Términos y Condiciones de uso',
        href: 'https://www.muniarica.cl/terminos-y-condiciones-de-uso',
    },
    { title: 'Política de Privacidad', href: 'https://www.muniarica.cl/politica-de-privacidad' },
];
</script>

<template>
    <footer class="mt-auto" role="contentinfo">
        <!-- Imagen decorativa móvil -->
        <img
            src="/footer-ilustracion-movil.png"
            alt=""
            aria-hidden="true"
            class="h-auto w-full object-cover md:hidden"
        />
        <!-- Imagen decorativa desktop -->
        <img
            src="/footer-ilustracion.png"
            alt=""
            aria-hidden="true"
            class="hidden h-auto w-full object-cover md:block"
        />

        <!-- Contenido del footer -->
        <div class="bg-[#06048c] py-10 text-white">
            <div class="container mx-auto px-6">
                <div
                    class="grid grid-cols-1 gap-8 divide-y divide-white/20 md:grid-cols-2 md:divide-y-0 lg:grid-cols-4 lg:divide-x"
                >
                    <!-- Columna 1: Información de contacto -->
                    <section
                        class="space-y-4 pt-8 pb-8 first:pt-0 md:py-0 lg:pr-8"
                        aria-labelledby="footer-contact"
                    >
                        <img
                            src="/logo.svg"
                            alt="Logo Municipalidad de Arica"
                            class="h-16 w-auto"
                        />

                        <h2 id="footer-contact" class="font-semibold">
                            Ilustre Municipalidad de Arica
                        </h2>

                        <address class="space-y-2 text-white/80 not-italic">
                            <p class="flex items-start gap-2">
                                <MapPin
                                    class="mt-1 h-4 w-4 flex-shrink-0 text-yellow-400"
                                    aria-hidden="true"
                                />
                                <span>Avenida Rafael Sotomayor #415, Arica XV Región - Chile</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <Phone
                                    class="h-4 w-4 flex-shrink-0 text-yellow-400"
                                    aria-hidden="true"
                                />
                                <a
                                    href="tel:+56432380004"
                                    class="transition-colors hover:text-yellow-400"
                                >
                                    +56 432380004
                                </a>
                            </p>
                        </address>

                        <Button
                            variant="outline"
                            class="rounded-3xl border-yellow-400 bg-[#ffcb49] px-6 font-extrabold text-[#06048c] hover:bg-[#e6b93b] hover:text-[#06048c]"
                            as-child
                        >
                            <a href="https://www.muniarica.cl/contacto">Contacto</a>
                        </Button>
                    </section>

                    <!-- Columna 2: Redes Sociales -->
                    <section class="space-y-4 py-8 md:py-0 lg:px-8" aria-labelledby="footer-social">
                        <h2 id="footer-social" class="font-semibold">¡Síguenos!</h2>

                        <nav aria-label="Redes sociales" class="flex flex-wrap gap-3">
                            <a
                                v-for="social in socialLinks"
                                :key="social.label"
                                :href="social.href"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="rounded-full bg-white p-2 text-[#06048c] transition-colors hover:bg-yellow-400"
                                :aria-label="social.label"
                            >
                                <component :is="social.icon" class="h-5 w-5" aria-hidden="true" />
                            </a>
                        </nav>

                        <div class="pt-4">
                            <p class="mb-3 font-semibold">
                                Suscríbete a nuestras últimas novedades:
                            </p>
                            <Button
                                class="rounded-3xl bg-[#00b9f1] px-6 font-extrabold text-white hover:bg-[#00a0d1]"
                            >
                                Suscribirme
                            </Button>
                        </div>
                    </section>

                    <!-- Columna 3: Enlaces del municipio -->
                    <nav
                        class="space-y-4 py-8 font-bold md:py-0 lg:px-8"
                        aria-labelledby="footer-links"
                    >
                        <h2 id="footer-links" class="sr-only">Enlaces del municipio</h2>
                        <ul class="space-y-2" role="list">
                            <li v-for="link in quickLinks" :key="link.title">
                                <a
                                    :href="link.href"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center gap-2 transition-colors hover:text-yellow-400"
                                >
                                    <span
                                        class="h-2 w-2 rounded-full bg-[#02bb94]"
                                        aria-hidden="true"
                                    ></span>
                                    {{ link.title }}
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Columna 4: Enlaces legales -->
                    <nav
                        class="space-y-4 pt-8 font-bold md:pt-0 lg:pl-8"
                        aria-labelledby="footer-legal"
                    >
                        <h2 id="footer-legal" class="sr-only">Enlaces legales e institucionales</h2>
                        <ul class="space-y-2" role="list">
                            <li v-for="link in legalLinks" :key="link.title">
                                <a
                                    :href="link.href"
                                    class="flex items-center gap-2 transition-colors hover:text-yellow-400"
                                >
                                    <span
                                        class="h-2 w-2 rounded-full bg-[#02bb94]"
                                        aria-hidden="true"
                                    ></span>
                                    {{ link.title }}
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </footer>
</template>
