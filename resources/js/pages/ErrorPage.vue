<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: Number,
        required: true,
    },
});

const errorMessages = {
    403: {
        title: "Akses Ditolak",
        description: "Anda tidak memiliki izin untuk mengakses halaman ini.",
    },

    404: {
        title: "Halaman Tidak Ditemukan",
        description: "Halaman atau konten yang Anda cari tidak tersedia.",
    },

    419: {
        title: "Sesi Telah Berakhir",
        description: "Silakan muat ulang halaman dan kirim kembali data Anda.",
    },

    429: {
        title: "Terlalu Banyak Permintaan",
        description: "Silakan tunggu beberapa saat sebelum mencoba kembali.",
    },

    500: {
        title: "Terjadi Kesalahan Server",
        description:
            "Terjadi gangguan pada sistem. Silakan coba kembali nanti.",
    },

    503: {
        title: "Layanan Tidak Tersedia",
        description:
            "Layanan sedang tidak tersedia. Silakan coba kembali nanti.",
    },
};

const error = computed(() => {
    return (
        errorMessages[props.status] ?? {
            title: "Terjadi Kesalahan",
            description: "Silakan coba kembali dalam beberapa saat.",
        }
    );
});

function reloadPage() {
    window.location.reload();
}
</script>

<template>
    <Head :title="`${status} - ${error.title}`">
        <meta name="robots" content="noindex, nofollow" />
    </Head>

    <main class="min-h-screen flex items-center justify-center px-6 bg-gray-50">
        <div class="max-w-xl text-center">
            <p class="mb-6 text-lg font-semibold">BATANI</p>

            <h1 class="text-7xl font-bold text-slate-900">
                {{ status }}
            </h1>

            <h2 class="mt-6 text-2xl font-semibold text-slate-800">
                {{ error.title }}
            </h2>

            <p class="mt-4 text-gray-600">
                {{ error.description }}
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a
                    href="/"
                    class="rounded-md bg-slate-900 px-6 py-3 text-white"
                >
                    Kembali ke Beranda
                </a>

                <button
                    v-if="[419, 429, 500, 503].includes(status)"
                    type="button"
                    @click="reloadPage"
                    class="rounded-md border border-gray-300 px-6 py-3"
                >
                    Coba Lagi
                </button>
            </div>
        </div>
    </main>
</template>
