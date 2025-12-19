<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-[#06048b] bg-[url('/bg-pattern.png')] bg-center bg-repeat p-6 md:p-10"
    >
        <div class="flex w-full max-w-md flex-col gap-6">
            <Link :href="home()" class="w-full">
                <img src="/logo-blanco.svg" class="h-auto w-full" alt="logo-arica" />
            </Link>
            <div class="flex flex-col gap-6">
                <Card class="rounded-xl">
                    <CardHeader class="px-10 pt-8 pb-0 text-center">
                        <CardTitle class="text-2xl">{{ title }}</CardTitle>
                    </CardHeader>
                    <CardContent class="px-10 py-8">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
