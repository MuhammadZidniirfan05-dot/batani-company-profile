<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

import { internalUrl } from "../utils/media";

const props = defineProps({
    pagination: {
        type: Object,
        default: () => ({}),
    },
});

const links = computed(() => {
    if (!Array.isArray(props.pagination?.links)) {
        return [];
    }

    return props.pagination.links.map((link) => ({
        ...link,
        url: internalUrl(link.url),
    }));
});

const lastPage = computed(() => Number(props.pagination?.last_page ?? 1));

function formatLabel(label) {
    const value = String(label ?? "");

    if (/previous|sebelumnya|&laquo;|«/i.test(value)) {
        return "Sebelumnya";
    }

    if (/next|selanjutnya|&raquo;|»/i.test(value)) {
        return "Selanjutnya";
    }

    return value;
}
</script>

<template>
    <div v-if="lastPage > 1 && links.length" class="mt-8 space-y-4">
        <p v-if="pagination.total" class="text-center text-sm text-gray-500">
            Menampilkan {{ pagination.from }} sampai {{ pagination.to }} dari
            {{ pagination.total }} data
        </p>

        <nav
            class="flex flex-wrap items-center justify-center gap-2"
            aria-label="Navigasi halaman"
        >
            <template v-for="(link, index) in links" :key="index">
                <Link
                    v-if="link.url && !link.active"
                    :href="link.url"
                    class="rounded-md border border-gray-300 px-4 py-2 text-sm hover:bg-gray-100"
                >
                    {{ formatLabel(link.label) }}
                </Link>

                <span
                    v-else
                    :aria-current="link.active ? 'page' : undefined"
                    :class="[
                        'rounded-md border px-4 py-2 text-sm',

                        link.active
                            ? 'border-slate-900 bg-slate-900 text-white'
                            : 'border-gray-200 text-gray-400',
                    ]"
                >
                    {{ formatLabel(link.label) }}
                </span>
            </template>
        </nav>
    </div>
</template>
