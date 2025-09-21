import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';

export type Replacements = Record<string, string | number>;

export default function useLang() {
    const page = usePage<SharedData>();
    const translations = page.props.translations;

    function trans(key: string, replacements: Replacements = {}): string {
        let translation: string = translations[key] ?? key;
        for (const replacement in replacements) {
            translation = translation.replace(`:${replacement}`, String(replacements[replacement]));
        }

        return translation;
    }

    return { trans };
}
