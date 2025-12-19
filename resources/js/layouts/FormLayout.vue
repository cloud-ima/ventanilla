<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItemType } from '@/types';

defineProps<{
    breadcrumbs: BreadcrumbItemType[];
    cardTitle: string;
}>();
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="container mx-auto max-w-4xl space-y-6 p-6 md:p-8">
            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>{{ cardTitle }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
