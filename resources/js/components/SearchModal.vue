<script setup lang="ts">
import { cn } from '@/lib/utils';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import { FileText, Loader2, Search, X } from 'lucide-vue-next';
import { computed, nextTick, ref, watch } from 'vue';

interface SearchResult {
    id: number;
    name: string;
    description: string;
    category: string;
    department: string;
    url: string;
}

const props = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const searchQuery = ref('');
const results = ref<SearchResult[]>([]);
const isSearching = ref(false);
const searchInput = ref<HTMLInputElement | null>(null);
const selectedIndex = ref(-1);

const hasResults = computed(() => results.value.length > 0);
const showNoResults = computed(
    () => searchQuery.value.length >= 2 && !isSearching.value && results.value.length === 0,
);

// Enfocar input cuando se abre el modal
watch(
    () => props.isOpen,
    async (isOpen) => {
        if (isOpen) {
            await nextTick();
            // El componente Input renderiza directamente un input HTML
            if (searchInput.value) {
                searchInput.value.focus();
            }
        } else {
            searchQuery.value = '';
            results.value = [];
            selectedIndex.value = -1;
        }
    },
);

// Búsqueda con debounce
let searchTimeout: number;
watch(searchQuery, (newQuery) => {
    selectedIndex.value = -1;

    if (newQuery.length < 2) {
        results.value = [];
        return;
    }

    clearTimeout(searchTimeout);
    isSearching.value = true;

    searchTimeout = setTimeout(async () => {
        try {
            const response = await axios.get<SearchResult[]>('/search', {
                params: { q: newQuery },
            });
            console.log('Respuesta de búsqueda:', response.data);
            results.value = response.data;
        } catch (error) {
            console.error('Error al buscar:', error);
            results.value = [];
        } finally {
            isSearching.value = false;
        }
    }, 300);
});

function handleClose() {
    emit('close');
}

function handleNavigate(url: string) {
    emit('close');
    router.visit(url);
}

function handleKeydown(event: KeyboardEvent) {
    if (event.key === 'Escape') {
        handleClose();
    } else if (event.key === 'ArrowDown') {
        event.preventDefault();
        selectedIndex.value = Math.min(selectedIndex.value + 1, results.value.length - 1);
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        selectedIndex.value = Math.max(selectedIndex.value - 1, -1);
    } else if (event.key === 'Enter' && selectedIndex.value >= 0) {
        event.preventDefault();
        handleNavigate(results.value[selectedIndex.value].url);
    }
}
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                class="fixed inset-0 z-50 bg-black/50 backdrop-blur-sm"
                @click="handleClose"
            />
        </Transition>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div
                v-if="isOpen"
                class="fixed top-[15%] left-1/2 z-50 w-full max-w-2xl -translate-x-1/2 px-4"
                @keydown="handleKeydown"
            >
                <div class="overflow-hidden rounded-xl border bg-white shadow-2xl">
                    <!-- Input de búsqueda -->
                    <div class="flex items-center gap-3 border-b px-4 py-3">
                        <Search class="h-5 w-5 text-gray-400" />
                        <input
                            ref="searchInput"
                            v-model="searchQuery"
                            type="text"
                            placeholder="Buscar trámites, servicios..."
                            :class="
                                cn(
                                    'flex-1 border-0 p-0 text-base outline-none focus-visible:ring-0',
                                    'selection:bg-primary selection:text-primary-foreground file:text-foreground placeholder:text-muted-foreground',
                                    'focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50',
                                )
                            "
                            @keydown.esc="handleClose"
                        />
                        <Loader2 v-if="isSearching" class="h-5 w-5 animate-spin text-gray-400" />
                        <button class="rounded-md p-1 hover:bg-gray-100" @click="handleClose">
                            <X class="h-5 w-5 text-gray-400" />
                        </button>
                    </div>

                    <!-- Resultados -->
                    <div class="max-h-[60vh] overflow-y-auto">
                        <!-- Lista de resultados -->
                        <div v-if="hasResults" class="py-2">
                            <button
                                v-for="(result, index) in results"
                                :key="result.id"
                                class="flex w-full items-start gap-3 px-4 py-3 text-left transition-colors hover:bg-gray-50"
                                :class="{
                                    'bg-gray-50': index === selectedIndex,
                                }"
                                @click="handleNavigate(result.url)"
                                @mouseenter="selectedIndex = index"
                            >
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50"
                                >
                                    <FileText class="h-5 w-5 text-blue-600" />
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <div class="truncate font-medium text-gray-900">
                                        {{ result.name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ result.department }} • {{ result.category }}
                                    </div>
                                    <div
                                        v-if="result.description"
                                        class="mt-1 line-clamp-2 text-sm text-gray-600"
                                    >
                                        {{ result.description }}
                                    </div>
                                </div>
                            </button>
                        </div>

                        <!-- Sin resultados -->
                        <div v-else-if="showNoResults" class="px-4 py-8 text-center">
                            <Search class="mx-auto mb-2 h-12 w-12 text-gray-300" />
                            <p class="font-medium text-gray-900">No se encontraron resultados</p>
                            <p class="mt-1 text-sm text-gray-500">
                                Intenta con otros términos de búsqueda
                            </p>
                        </div>

                        <!-- Estado inicial -->
                        <div v-else-if="searchQuery.length < 2" class="px-4 py-8 text-center">
                            <Search class="mx-auto mb-2 h-12 w-12 text-gray-300" />
                            <p class="font-medium text-gray-900">Busca trámites y servicios</p>
                            <p class="mt-1 text-sm text-gray-500">
                                Escribe al menos 2 caracteres para buscar
                            </p>
                        </div>
                    </div>

                    <!-- Footer con atajos de teclado -->
                    <div
                        class="flex items-center justify-between border-t bg-gray-50 px-4 py-2 text-xs text-gray-500"
                    >
                        <div class="flex gap-4">
                            <div class="flex items-center gap-1">
                                <kbd class="rounded border bg-white px-1.5 py-0.5">↑↓</kbd>
                                <span>Navegar</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <kbd class="rounded border bg-white px-1.5 py-0.5">Enter</kbd>
                                <span>Seleccionar</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <kbd class="rounded border bg-white px-1.5 py-0.5">Esc</kbd>
                            <span>Cerrar</span>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
