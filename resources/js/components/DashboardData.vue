<template>
    <div class="container-fluid">
        <h3 align="center" class="mb-3">Dashboard</h3>

        <!--DADOS SEMANAIS-->
        <h5 class="text-success">Destaques positivos da semana</h5>
        <div class="row mb-3">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartArtilheirosSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartAssistenciasSemana"
                    :options="chartOptions"
                />
            </div>
        </div>

        <br>

        <h5 class="text-danger">Destaques negativos da semana</h5>

        <div class="row mb-5">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesAmarelosSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesVermelhosSemana"
                    :options="chartOptions"
                />
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesAzuisSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisGolsContraSemana"
                    :options="chartOptions"
                />
            </div>
        </div>
        <hr>

        <!--DADOS ANUAIS-->
        <h5 class="text-success">Destaques positivos de {{ anoAtual }}</h5>
        <div class="row mb-3">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartArtilheirosSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartAssistenciasSemana"
                    :options="chartOptions"
                />
            </div>
        </div>

        <br>

        <h5 class="text-danger">Destaques negativos de {{ anoAtual }}</h5>

        <div class="row mb-5">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesAmarelosSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesVermelhosSemana"
                    :options="chartOptions"
                />
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisCartoesAzuisSemana"
                    :options="chartOptions"
                />
            </div>

            <div class="col-lg-6 mb-2">
                <Bar
                    :data="chartMaisGolsContraSemana"
                    :options="chartOptions"
                />
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';
    import { Bar } from 'vue-chartjs'
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
            Bar
        },
        data() {
            return {
                estatisticasUltimoJogo: [],
                estatisticas: [],
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
                        title: { display: true, text: 'Gols por Jogador' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        }
                    },
                }
            },
            //DADOS SEMANAIS
            chartArtilheirosSemana() {
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
            chartAssistenciasSemana() {
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
            chartMaisCartoesAmarelosSemana() {
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
            chartMaisCartoesVermelhosSemana() {
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
            chartMaisCartoesAzuisSemana() {
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
            chartMaisGolsContraSemana() {
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

            //DADOS ANUAIS

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
                    this.estatisticas = response.data.data;

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
