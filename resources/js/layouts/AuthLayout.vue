<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthCardLayout.vue';

defineProps<{
    title?: string;
}>();
</script>

<template>
    <AuthLayout :title="title">
        <slot />
    </AuthLayout>
</template>
