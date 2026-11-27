import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

import { mediaUrl, safeHttpUrl } from "../utils/media";

export function useSiteSettings() {
    const page = usePage();

    const settings = computed(() => page.props.siteSettings ?? {});

    function getSetting(key, fallback = null) {
        const value = settings.value[key];

        if (
            value === null ||
            value === undefined ||
            (typeof value === "string" && value.trim() === "")
        ) {
            return fallback;
        }

        return value;
    }

    const companyName = computed(() => getSetting("company_name", "BATANI"));

    const companyTagline = computed(() => getSetting("company_tagline", ""));

    const companyDescription = computed(() =>
        getSetting("company_description", ""),
    );

    const companyLogo = computed(() => mediaUrl(getSetting("company_logo")));

    const companyFavicon = computed(() =>
        mediaUrl(getSetting("company_favicon")),
    );

    const companyEmail = computed(() => getSetting("company_email", ""));

    const companyPhone = computed(() => getSetting("company_phone", ""));

    const companyAddress = computed(() => getSetting("company_address", ""));

    const defaultSeoTitle = computed(() =>
        getSetting("default_seo_title", "BATANI"),
    );

    const defaultSeoDescription = computed(() =>
        getSetting("default_seo_description", ""),
    );

    const socialLinks = computed(() => {
        const links = page.props.siteSocialLinks;

        if (!Array.isArray(links)) {
            return [];
        }

        return links
            .map((link) => ({
                ...link,
                url: safeHttpUrl(link.url),
            }))
            .filter((link) => link.url !== null);
    });

    return {
        settings,
        getSetting,

        companyName,
        companyTagline,
        companyDescription,
        companyLogo,
        companyFavicon,

        companyEmail,
        companyPhone,
        companyAddress,

        defaultSeoTitle,
        defaultSeoDescription,

        socialLinks,
    };
}
