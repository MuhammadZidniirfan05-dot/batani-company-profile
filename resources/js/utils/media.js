export function safeHttpUrl(value, fallback = null) {
    if (typeof value !== "string") {
        return fallback;
    }

    const url = value.trim();

    if (!/^https?:\/\//i.test(url)) {
        return fallback;
    }

    try {
        const parsed = new URL(url);

        if (
            !["http:", "https:"].includes(parsed.protocol) ||
            parsed.username ||
            parsed.password
        ) {
            return fallback;
        }

        return parsed.href;
    } catch {
        return fallback;
    }
}

export function mediaUrl(value, fallback = null) {
    if (typeof value !== "string") {
        return fallback;
    }

    const path = value.trim();

    if (
        !path ||
        /[\u0000-\u001F\u007F\\]/.test(path) ||
        path.startsWith("//")
    ) {
        return fallback;
    }

    // URL eksternal.
    if (/^https?:\/\//i.test(path)) {
        return safeHttpUrl(path, fallback);
    }

    // Menolak skema URL lainnya.
    if (/^[a-z][a-z0-9+.-]*:/i.test(path)) {
        return fallback;
    }

    // Path relatif dari Laravel Storage.
    const relativePath = path.replace(/^\/+/, "").replace(/^storage\//, "");

    const segments = relativePath.split("/");

    if (
        segments.some(
            (segment) =>
                !segment ||
                segment === "." ||
                segment === ".." ||
                /[%?#]/.test(segment),
        )
    ) {
        return fallback;
    }

    return (
        "/storage/" +
        segments.map((segment) => encodeURIComponent(segment)).join("/")
    );
}

export function internalUrl(value, fallback = null) {
    if (typeof value !== "string" || !value.trim()) {
        return fallback;
    }

    if (typeof window === "undefined") {
        return fallback;
    }

    try {
        const parsed = new URL(value, window.location.origin);

        if (
            parsed.origin !== window.location.origin ||
            !["http:", "https:"].includes(parsed.protocol) ||
            parsed.username ||
            parsed.password
        ) {
            return fallback;
        }

        return parsed.pathname + parsed.search + parsed.hash;
    } catch {
        return fallback;
    }
}
