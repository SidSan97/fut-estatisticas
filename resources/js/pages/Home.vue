<template>
    <div>
        <h1 align="center" class="mt-3">Estatísticas do Baba</h1>

        <general-data></general-data>
    </div>
</template>

<script>
    import axios from 'axios';
    import GeneralData from '@/components/GeneralData.vue';

    export default {
        name: 'Home',
        components: {
            GeneralData

        },

        data() {
            return {

            };
        }
    }
</script>
