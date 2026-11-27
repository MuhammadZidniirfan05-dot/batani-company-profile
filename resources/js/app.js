import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    title: (title) => {
        const value = (title ?? "").trim();

        if (!value || value.toUpperCase() === "BATANI") {
            return "BATANI";
        }

        if (/\|\s*BATANI$/i.test(value)) {
            return value;
        }

        return `${value} | BATANI`;
    },

    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob("./pages/**/*.vue"),
        ),

    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props),
        })
            .use(plugin)
            .mount(el);
    },

    progress: {
        color: "#64748b",
        showSpinner: false,
    },
});
