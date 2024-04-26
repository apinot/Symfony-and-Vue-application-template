import { createI18n } from 'vue-i18n';
import messages from '@intlify/unplugin-vue-i18n/messages';

const locale = 'fr';
const fallbackLocale = 'fr';

const i18n = createI18n({
    legacy: false,
    locale,
    fallbackLocale,
    messages,
});

export default i18n;
