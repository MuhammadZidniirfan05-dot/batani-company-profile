<script setup>
defineProps({
    title: {
        type: String,
        default: "Belum Ada Data",
    },

    description: {
        type: String,
        default: "Informasi belum tersedia saat ini.",
    },
});
</script>

<template>
    <div
        class="rounded-lg border border-dashed border-gray-300 px-6 py-12 text-center"
    >
        <div class="mx-auto mb-4 flex justify-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="40"
                height="40"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
                class="text-gray-400"
                aria-hidden="true"
            >
                <path d="M4 4h16v16H4z" />
                <path d="M8 9h8" />
                <path d="M8 13h8" />
                <path d="M8 17h4" />
            </svg>
        </div>

        <h3 class="text-lg font-semibold text-slate-800">
            {{ title }}
        </h3>

        <p class="mt-2 text-sm text-gray-500">
            {{ description }}
        </p>

        <div v-if="$slots.default" class="mt-5">
            <slot />
        </div>
    </div>
</template>
