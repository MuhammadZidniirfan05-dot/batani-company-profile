<script setup>
import { computed } from "vue";
import { Head } from "@inertiajs/vue3";

import { useSiteSettings } from "../composables/useSiteSettings";
import { mediaUrl } from "../utils/media";

const props = defineProps({
    title: {
        type: String,
        default: "",
    },

    description: {
        type: String,
        default: "",
    },

    image: {
        type: String,
        default: "",
    },

    type: {
        type: String,
        default: "website",
    },

    noindex: {
        type: Boolean,
        default: false,
    },
});

const {
    companyName,
    companyLogo,
    companyFavicon,
    defaultSeoTitle,
    defaultSeoDescription,
} = useSiteSettings();

const pageTitle = computed(
    () => props.title.trim() || defaultSeoTitle.value || companyName.value,
);

const pageDescription = computed(
    () => props.description.trim() || defaultSeoDescription.value || "",
);

const pageImage = computed(() => {
    const image = mediaUrl(props.image) || companyLogo.value;

    if (!image || typeof window === "undefined") {
        return image;
    }

    return new URL(image, window.location.origin).href;
});
</script>

<template>
    <Head :title="pageTitle">
        <meta
            v-if="pageDescription"
            head-key="description"
            name="description"
            :content="pageDescription"
        />

        <meta
            head-key="robots"
            name="robots"
            :content="noindex ? 'noindex, nofollow' : 'index, follow'"
        />

        <meta head-key="og:title" property="og:title" :content="pageTitle" />

        <meta
            v-if="pageDescription"
            head-key="og:description"
            property="og:description"
            :content="pageDescription"
        />

        <meta head-key="og:type" property="og:type" :content="type" />

        <meta
            head-key="og:site_name"
            property="og:site_name"
            :content="companyName"
        />

        <meta head-key="og:locale" property="og:locale" content="id_ID" />

        <meta
            v-if="pageImage"
            head-key="og:image"
            property="og:image"
            :content="pageImage"
        />

        <link
            v-if="companyFavicon"
            head-key="favicon"
            rel="icon"
            :href="companyFavicon"
        />
    </Head>
</template>
