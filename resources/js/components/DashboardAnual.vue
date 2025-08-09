<template>
    <div>
        <div class="container-fluid">
            <h5 class="text-success">Destaques positivos de {{ anoAtual }}</h5>
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

            <h5 class="text-danger">Destaques negativos de {{ anoAtual }}</h5>

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
</template>

<script>
    import { Bar } from 'vue-chartjs';
    import ChartDataLabels from 'chartjs-plugin-datalabels';
    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        BarElement,
        CategoryScale,
        LinearScale,
    } from 'chart.js'

    ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ChartDataLabels);

    export default {
        name: 'DashboardAnual',
        components: {
            Bar
        },
        data() {
            return {
                anoAtual: new Date().getFullYear(),
            }
        },
        props: {
            estatisticas: {
                type: Array,
                required: true,
            },
        },
        computed: {
            chartOptions() {
                return {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: { display: false, text: '' },
                        datalabels: {
                            anchor: 'center',
                            align: 'top',
                            color: '#333',
                            font: {
                                weight: 'bold',
                                size: 14,
                            },
                            formatter: function(value) {
                                return value;
                            }
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            },
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
            chartArtilheiros() {
                const principaisArtilheiros = this.estatisticas.slice(0, 5);
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
                            backgroundColor: 'rgba(242, 123, 124, 0.4)',
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
            estatisticasFiltradas(filtro) {
                return [...this.estatisticas]
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
</style>
