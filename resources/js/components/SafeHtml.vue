<script setup>
import { computed } from "vue";
import DOMPurify from "dompurify";

const props = defineProps({
    content: {
        type: String,
        default: "",
    },
});

const sanitizedContent = computed(() => {
    if (
        !props.content ||
        typeof DOMPurify.sanitize !== "function" ||
        !DOMPurify.isSupported
    ) {
        return "";
    }

    return DOMPurify.sanitize(props.content, {
        ALLOWED_TAGS: [
            "p",
            "br",
            "hr",

            "h1",
            "h2",
            "h3",
            "h4",
            "h5",
            "h6",

            "strong",
            "b",
            "em",
            "i",
            "u",
            "s",
            "del",
            "sub",
            "sup",

            "ul",
            "ol",
            "li",

            "blockquote",
            "pre",
            "code",

            "a",
            "img",
            "figure",
            "figcaption",

            "table",
            "thead",
            "tbody",
            "tfoot",
            "tr",
            "th",
            "td",

            "div",
            "span",
        ],

        ALLOWED_ATTR: [
            "href",
            "src",
            "alt",
            "title",
            "width",
            "height",
            "colspan",
            "rowspan",
            "scope",
            "start",
        ],

        ALLOW_DATA_ATTR: false,

        ALLOW_UNKNOWN_PROTOCOLS: false,

        FORBID_TAGS: [
            "script",
            "iframe",
            "object",
            "embed",
            "form",
            "style",
            "svg",
            "math",
        ],

        FORBID_ATTR: ["style", "srcset", "target", "id", "name"],
    });
});
</script>

<template>
    <div class="rich-content break-words" v-html="sanitizedContent"></div>
</template>
