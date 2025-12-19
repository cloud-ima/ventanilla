<script setup lang="ts">
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Link } from '@inertiajs/vue3';

interface BreadcrumbItemType {
    title: string;
    href?: string;
}

defineProps<{
    breadcrumbs: BreadcrumbItemType[];
    classList?: string;
    classItem?: string;
    classPage?: string;
    classLink?: string;
}>();
</script>

<template>
    <Breadcrumb>
        <BreadcrumbList :class="classList">
            <template v-for="(item, index) in breadcrumbs" :key="index">
                <BreadcrumbItem :class="classItem">
                    <template v-if="index === breadcrumbs.length - 1">
                        <BreadcrumbPage :class="classPage">{{ item.title }}</BreadcrumbPage>
                    </template>
                    <template v-else>
                        <BreadcrumbLink as-child :class="classLink">
                            <Link :href="item.href ?? '#'">{{ item.title }}</Link>
                        </BreadcrumbLink>
                    </template>
                </BreadcrumbItem>
                <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
            </template>
        </BreadcrumbList>
    </Breadcrumb>
</template>
