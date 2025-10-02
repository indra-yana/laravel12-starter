import { App } from "vue";
import useLang, { Replacements } from "@/composables/useLang";

export default {
    install(app: App) {
        function __(key: string, replacements: Replacements = {}): string {
            const { trans } = useLang();
            return trans(key, replacements);
        }

        app.config.globalProperties.trans = __;
        window.trans = __;
    },
};
