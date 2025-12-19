<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Check } from 'lucide-vue-next';
import { computed } from 'vue';
import { AVAILABLE_ICONS } from '../config/available-icon';

const props = defineProps<{
    modelValue?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const selectedIcon = computed({
    get: () => props.modelValue || 'Building2',
    set: (value) => emit('update:modelValue', value),
});

const selectedIconData = computed(() => {
    return AVAILABLE_ICONS.find((i) => i.name === selectedIcon.value) || AVAILABLE_ICONS[0];
});
</script>

<template>
    <div class="space-y-2">
        <Label>Icono</Label>
        <Popover v-slot="{ close }">
            <PopoverTrigger as-child>
                <Button variant="outline" class="w-full justify-start gap-2" type="button">
                    <component :is="selectedIconData.component" class="h-4 w-4" />
                    {{ selectedIconData.label }}
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-80">
                <div class="grid grid-cols-4 gap-2">
                    <button
                        v-for="icon in AVAILABLE_ICONS"
                        :key="icon.name"
                        type="button"
                        @click="
                            selectedIcon = icon.name;
                            close();
                        "
                        :class="[
                            'relative flex flex-col items-center gap-2 rounded-lg border p-3 transition-colors hover:bg-muted',
                            selectedIcon === icon.name
                                ? 'border-primary bg-primary/5'
                                : 'border-transparent',
                        ]"
                    >
                        <component :is="icon.component" class="h-6 w-6" />
                        <span class="text-xs">{{ icon.label }}</span>
                        <Check
                            v-if="selectedIcon === icon.name"
                            class="absolute top-1 right-1 h-4 w-4 text-primary"
                        />
                    </button>
                </div>
            </PopoverContent>
        </Popover>
        <!-- Hidden input para el form -->
        <input type="hidden" name="icon" :value="selectedIcon" />
    </div>
</template>
