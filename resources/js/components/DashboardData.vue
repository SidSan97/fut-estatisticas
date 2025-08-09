<template>
    <div class="container-fluid">
        <h3 align="center" class="mb-3">Dashboard</h3>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Estatísticas do último baba
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <h5 class="text-success">Destaques positivos da semana</h5>
                        <div class="row mb-3">
                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Principais artilheiros</p>
                                <Bar
                                    :data="chartArtilheiros"
                                    :options="chartOptions"
                                />
                            </div>

                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Principais garçons</p>
                                <Bar
                                    :data="chartAssistencias"
                                    :options="chartOptions"
                                />
                            </div>
                        </div>

                        <br>

                        <h5 class="text-danger">Destaques negativos da semana</h5>

                        <div class="row mb-5">
                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Mais cartões amarelos</p>
                                <Bar
                                    :data="chartMaisCartoesAmarelos"
                                    :options="chartOptions"
                                />
                            </div>

                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Mais cartões vermelhos</p>
                                <Bar
                                    :data="chartMaisCartoesVermelhos"
                                    :options="chartOptions"
                                />
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Mais cartões azuis</p>
                                <Bar
                                    :data="chartMaisCartoesAzuis"
                                    :options="chartOptions"
                                />
                            </div>

                            <div class="col-lg-6 mb-2">
                                <p class="text-center legenda-chart">Mais gols contra</p>
                                <Bar
                                    :data="chartMaisGolsContra"
                                    :options="chartOptions"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Estatísticas do ano
                    </button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <dashboard-anual
                            :estatisticas="estatisticasAnuais"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { Bar } from 'vue-chartjs';
    import DashboardAnual from './DashboardAnual.vue';
    import { swalError } from '@/utils/utils';
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        BarElement,
        CategoryScale,
        LinearScale,
    } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

    export default {
        name: 'Dashboard',
        components: {
            Bar,
            DashboardAnual,
        },
        data() {
            return {
                estatisticasUltimoJogo: [],
                estatisticasAnuais: [],
                anoAtual: new Date().getFullYear(),
            }
        },
        created() {
            this.lerDadosUltimoJogo();
            this.lerEstatisticasDoAno();
        },
        computed: {
            chartOptions() {
                return {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: false, text: '' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        x: {
                            ticks: {
                                color: '#black',
                                font: {
                                    size: 12,
                                    weight: 'bold',
                                    family: 'Arial',
                                }
                            }
                        },
                    },
                }
            },
            //DADOS SEMANAIS
            chartArtilheiros() {
                const principaisArtilheiros = this.estatisticasUltimoJogo.slice(0, 5);
                return {
                    labels: principaisArtilheiros.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Gols',
                            backgroundColor: 'rgba(61, 217, 64, 0.6)',
                            data: principaisArtilheiros.map(item => item.total_gols)
                        }
                    ]
                }
            },
            chartAssistencias() {
                let asssitencias = this.estatisticasFiltradas("total_assistencias");
                asssitencias = asssitencias.slice(0,5);

                return {
                    labels: asssitencias.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Assistências',
                            backgroundColor: 'rgba(55, 133, 211, 0.6)',
                            data: asssitencias.map(item => item.total_assistencias)
                        }
                    ]
                }
            },
            chartMaisCartoesAmarelos() {
                let cartoes = this.estatisticasFiltradas("total_amarelo");
                cartoes = cartoes.slice(0,5);

                return {
                    labels: cartoes.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Cartões Amarelos',
                            backgroundColor: 'rgba(236, 225, 90, 0.6)',
                            data: cartoes.map(item => item.total_amarelo)
                        }
                    ]
                }
            },
            chartMaisCartoesVermelhos() {
                let cartoes = this.estatisticasFiltradas("total_vermelho");
                cartoes = cartoes.slice(0,5);

                return {
                    labels: cartoes.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Cartões Vermelhos',
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            data: cartoes.map(item => item.total_vermelho)
                        }
                    ]
                }
            },
            chartMaisCartoesAzuis() {
                let cartoes = this.estatisticasFiltradas("total_azul");
                cartoes = cartoes.slice(0,5);

                return {
                    labels: cartoes.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Cartões Azuis',
                            backgroundColor: '#0b51cb',
                            data: cartoes.map(item => item.total_azul)
                        }
                    ]
                }
            },
            chartMaisGolsContra() {
                let golsContra = this.estatisticasFiltradas("total_gols_contra");
                golsContra = golsContra.slice(0,5);

                return {
                    labels: golsContra.map(item => item.nome),
                    datasets: [
                        {
                            label: 'Gols Contra',
                            backgroundColor: 'rgba(246, 36, 38, 1)',
                            data: golsContra.map(item => item.total_gols_contra)
                        }
                    ]
                }
            },
        },
        methods: {
            async lerDadosUltimoJogo() {
                this.$loading.show();
                try {
                    const response = await axios.get('carregar-estatisticas-ultimo-jogo');
                    this.estatisticasUltimoJogo = response.data.data;

                }catch(error) {
                    console.error(error);
                    swalError(error.response.data.error)
                }finally{
                    this.$loading.hide();
                }
            },
            async lerEstatisticasDoAno() {
                this.$loading.show();
                try {
                    const response = await axios.get('carregar-todas-estatisticas-do-ano/' + this.anoAtual);
                    this.estatisticasAnuais = response.data.data;

                }catch(error) {
                    console.error(error);
                    swalError(error.response.data.error)
                }finally{
                    this.$loading.hide();
                }
            },
            estatisticasFiltradas(filtro) {
                return [...this.estatisticasUltimoJogo]
                    .filter(item => item[filtro] > 0)
                    .sort((a, b) => b[filtro] - a[filtro]);
            }
        }
    }
</script>

<style scoped>
    .legenda-chart {
        color: #474646;
        font-weight: 600;
    }
    .collapse {
        visibility: visible !important;
    }
    .container-fluid {
        padding-bottom: 20px
    }
</style>
