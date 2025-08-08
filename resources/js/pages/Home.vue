<template>
    <div>
        <h1 align="center" class="mt-3">Estatísticas do Baba</h1>

        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a
                    class="nav-link"
                    :class="{ active: abaAtiva === 'general' }"
                    href="#"
                    @click.prevent="abaAtiva = 'general'"
                >Números Gerais</a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link"
                    :class="{ active: abaAtiva === 'dashboard' }"
                    href="#"
                    @click.prevent="abaAtiva = 'dashboard'"
                >Dashboard</a>
            </li>
        </ul>

        <general-data v-if="abaAtiva === 'general'" />
        <dashboard-data v-if="abaAtiva === 'dashboard'" />
    </div>
</template>

<script>
    import GeneralData from '@/components/GeneralData.vue';
    import DashboardData from '@/components/DashboardData.vue';

    export default {
        name: 'Home',
        components: {
            GeneralData,
            DashboardData
        },

        data() {
            return {
                abaAtiva: 'general'
            };
        }
    }
</script>

<style scoped>
    .nav-link.active {
        background-color: #0b51cb;
        color: white;
        font-weight: 600;
    }
</style>

