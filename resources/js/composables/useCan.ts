import { usePage } from '@inertiajs/vue3';

type Auth = {
    check: boolean;
    permissions: string[];
};

// Cache regex supaya tidak dibuat ulang
const regexCache = new Map<string, RegExp>();

function toRegex(pattern: string): RegExp {
    if (regexCache.has(pattern)) {
        return regexCache.get(pattern)!;
    }

    // Escape semua karakter regex kecuali *
    const regex = new RegExp(
        "^" + pattern.replace(/[.+?^${}()|[\]\\]/g, "\\$&").replace(/\*/g, ".*") + "$"
    );

    regexCache.set(pattern, regex);
    return regex;
}

function matchPermission(permissions: string[], key: string): boolean {
    if (key.includes("*")) {
        const regex = toRegex(key);
        return permissions.some((perm) => regex.test(perm));
    }

    return permissions.includes(key);
}

export default function useCan() {
    const page = usePage<{ auth: Auth }>();
    const auth = page.props.auth;

    function can(key: string): boolean {
        if (!auth?.check) {
            return false;
        }

        const permissions = auth?.permissions || [];
        return matchPermission(permissions, key);
    }

    function canAny(key: string | string[]): boolean {
        if (!auth?.check) {
            return false;
        }

        const keys = typeof key === "string" ? [key] : key;
        const permissions = auth?.permissions || [];

        return keys.some((permission) => matchPermission(permissions, permission));
    }

    return { can, canAny };
}
