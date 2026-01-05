<template>
    <div class="container-fluid">
        <h3 class="mb-3 me-2">Números totais</h3>

        <div class="d-lg-flex d-block mb-3">
            <div class="mb-md-2 mb-1">
                <label for="filtro" class="form-label me-2">Filtrar por:</label>
                <select v-model="filtro" id="filtro" class="form-select w-auto d-inline-block">
                    <option value="">Sem filtros</option>
                    <option value="total_gols">Gols</option>
                    <option value="total_gols_contra">Gols Contra</option>
                    <option value="total_assistencias">Assistências</option>
                    <option value="total_amarelo">Cartões Amarelos</option>
                    <option value="total_vermelho">Cartões Vermelhos</option>
                    <option value="total_azul">Cartões Azuis</option>
                </select>
            </div>

            <div class="ms-md-3 ms-0">
                <label for="filtro" class="form-label me-2">Selecionar ano:</label>
                <input type="number" class="form-control w-auto d-inline-block" :value="anoSelecionado" @change="anoSelecionado = $event.target.value">
                <button class="btn btn-primary ms-2" @click="filtrarPorAno">
                    Filtrar
                </button>
            </div>
        </div>

        <div class="table-responsive mb-3">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th :class="colunaDestaque('total_gols')">Gols</th>
                        <th :class="colunaDestaque('total_assistencias')">Assistências</th>
                        <th :class="colunaDestaque('total_amarelo')">Cartões Amarelos</th>
                        <th :class="colunaDestaque('total_vermelho')">Cartões Vermelhos</th>
                        <th :class="colunaDestaque('total_azul')">Cartões Azuis</th>
                        <th :class="colunaDestaque('total_gols_contra')">Gols Contra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in estatisticasFiltradas" :key="idx">
                        <td>{{ item.nome }}</td>
                        <td :class="colunaDestaque('total_gols')">{{ item.total_gols }}</td>
                        <td :class="colunaDestaque('total_assistencias')">{{ item.total_assistencias }}</td>
                        <td :class="colunaDestaque('total_amarelo')">{{ item.total_amarelo }}</td>
                        <td :class="colunaDestaque('total_vermelho')">{{ item.total_vermelho }}</td>
                        <td :class="colunaDestaque('total_azul')">{{ item.total_azul }}</td>
                        <td :class="colunaDestaque('total_gols_contra')">{{ item.total_gols_contra }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br><hr><br>

        <h3 class="mb-3 mt-3 me-2">Números do último baba</h3>

        <div class="table-responsive mb-3">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Gols</th>
                        <th>Assistências</th>
                        <th>Cartões Amarelos</th>
                        <th>Cartões Vermelhos</th>
                        <th>Cartões Azuis</th>
                        <th>Gols Contra</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in estatisticasUltimoJogo" :key="idx">
                        <td>{{ item.nome }}</td>
                        <td>{{ item.total_gols }}</td>
                        <td>{{ item.total_assistencias }}</td>
                        <td>{{ item.total_amarelo }}</td>
                        <td>{{ item.total_vermelho }}</td>
                        <td>{{ item.total_azul }}</td>
                        <td>{{ item.total_gols_contra }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { swalError } from '@/utils/utils';

    export default {
        name: 'GeneralData',

        data() {
            return {
                estatisticas: [],
                estatisticasUltimoJogo: [],
                filtro: '',
                anoSelecionado: new Date().getFullYear()
            }
        },
        computed: {
            estatisticasFiltradas() {
                if (!this.filtro) {
                    return [...this.estatisticas].sort((a, b) => b.total_gols - a.total_gols);

                }

                return [...this.estatisticas]
                    .filter(item => item[this.filtro] > 0)
                    .sort((a, b) => b[this.filtro] - a[this.filtro]);
            }
        },
        created() {
            this.load();
            this.lerDadosUltimoJogo();
        },
        methods: {
            async load() {
                this.$loading.show();
                try {
                    const response = await axios.get('carregar-todas-estatisticas');
                    this.estatisticas = response.data.data;

                }catch(error) {
                    console.error(error);
                    swalError(error.response.data.error)
                }finally{
                    this.$loading.hide();
                }
            },
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
            colunaDestaque(campo) {
                return this.filtro === campo ? 'coluna-destaque' : '';
            },
            async filtrarPorAno() {
                this.$loading.show();
                axios.get(`carregar-estatisticas-ano/${this.anoSelecionado}`)
                    .then(response => {
                        this.estatisticas = response.data.data;
                    })
                    .catch(error => {
                        console.error(error);
                        swalError(error.response.data.error)
                    })
                    .finally(() => {
                        this.$loading.hide();
                    });
            }
        }
    }
</script>

<style scoped>
    .coluna-destaque {
        background-color: #ffe066 !important;
        color: #333 !important;
        font-weight: bold;
    }
</style>
