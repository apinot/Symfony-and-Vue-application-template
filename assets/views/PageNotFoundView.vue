<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <main>
        {{ t('404 not found') }}
    </main>
</template>
