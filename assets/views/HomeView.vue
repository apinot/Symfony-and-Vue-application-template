<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
    <main>
        {{ t('app start') }}
    </main>
</template>
