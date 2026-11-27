const LOCALE = "id-ID";
const TIMEZONE = "Asia/Jakarta";

function parseDate(value) {
    if (!value) {
        return null;
    }

    if (value instanceof Date) {
        return Number.isNaN(value.getTime()) ? null : value;
    }

    if (typeof value !== "string") {
        return null;
    }

    let normalized = value.trim().replace(" ", "T");

    // Timestamp tanpa offset dianggap menggunakan UTC.
    if (/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}(:\d{2}(\.\d+)?)?$/.test(normalized)) {
        normalized += "Z";
    }

    const date = new Date(normalized);

    return Number.isNaN(date.getTime()) ? null : date;
}

export function formatDate(value, fallback = "—") {
    const date = parseDate(value);

    if (!date) {
        return fallback;
    }

    const isCalendarDate =
        typeof value === "string" && /^\d{4}-\d{2}-\d{2}$/.test(value.trim());

    return new Intl.DateTimeFormat(LOCALE, {
        day: "numeric",
        month: "long",
        year: "numeric",
        timeZone: isCalendarDate ? "UTC" : TIMEZONE,
    }).format(date);
}

export function formatDateTime(value, fallback = "—") {
    const date = parseDate(value);

    if (!date) {
        return fallback;
    }

    if (typeof value === "string" && /^\d{4}-\d{2}-\d{2}$/.test(value.trim())) {
        return formatDate(value, fallback);
    }

    const formattedDate = formatDate(date);

    const formattedTime = new Intl.DateTimeFormat(LOCALE, {
        hour: "2-digit",
        minute: "2-digit",
        hourCycle: "h23",
        timeZone: TIMEZONE,
    }).format(date);

    return `${formattedDate}, ${formattedTime} WIB`;
}
