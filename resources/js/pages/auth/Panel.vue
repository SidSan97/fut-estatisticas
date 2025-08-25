<template>
    <div class="container-fluid">
        <h2 class="text-center mt-3 mb-3">Painel administrativo</h2>

        <div class="d-flex justify-content-end me-2 mb-2">
            <button class="btn btn-warning">
                <a class="nav-link" :href="route('home')">Página principal</a>
            </button>
        </div>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <i class="bi bi-plus-circle me-3"></i> Registrar novo baba
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <label for="dataJogo">Insira a data do baba</label>
                        <input type="date" class="form-control w-50 mb-2" id="dataJogo" v-model="dataJogo">

                        <div class="table-responsive mb-3">
                            <table class="table table-striped table-bordered table-estatistica">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Gols Marcados</th>
                                        <th>Assistências</th>
                                        <th>Cartões Amarelos</th>
                                        <th>Cartões Vermelhos</th>
                                        <th>Cartões Azuis</th>
                                        <th>Gols Contra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, idx) in jogo" :key="idx">
                                        <td v-if="item.jogador_id !== null">
                                            {{ item.nome }}
                                        </td>
                                        <td v-else>
                                            <input type="text" class="form-control" v-model="item.nome">
                                        </td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_gols" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_assistencias" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_amarelo" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_vermelho" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_azul" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.total_gols_contra" min="0"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="dropdown ms-3">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Adicionar visitante
                            </button>
                            <ul class="dropdown-menu">
                                <li v-for="(v, idx) in jogadoresVisitantes" :key="idx">
                                    <a class="dropdown-item" @click="addVisitante(v.id, v.nome)">{{ v.nome }}</a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" @click="addVisitante(null, '')">Outro jogador</a>
                                </li>
                            </ul>
                        </div>

                        <button class="btn btn-success ms-1 mt-4" @click="registrarJogo">
                            Registrar baba
                        </button>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                       <i class="bi bi-person-fill me-3"></i> Gerenciar mensalistas
                    </button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="table-responsive mb-3">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Membro ativo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, idx) in mensalistas" :key="idx">
                                        <td>
                                            <input type="text" class="form-control" v-model="item.nome">
                                        </td>
                                        <td>
                                            <input
                                                class="form-check-input me-2"
                                                type="checkbox"
                                                :id="'checkDefault' + idx"
                                                v-model="item.ativo"
                                                :true-value="1"
                                                :false-value="0"
                                            >
                                            <label class="form-check-label" :for="'checkDefault' + idx">
                                                Ativo
                                            </label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <button class="btn btn-secondary mt-2 ms-3" @click="addJogador()">
                                Add jogador <i class="bi bi-plus-circle"></i>
                            </button>
                        </div>

                        <button class="btn btn-primary mt-3" @click="salvarMensalistas">
                            Salvar alterações
                        </button>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree" @click="carregarListaDeJogos">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <i class="bi bi-check-circle-fill me-3"></i> Gerenciar baba
                    </button>
                </h2>

                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="spinner-border" role="status" v-if="loading">
                            <span class="visually-hidden">Loading...</span>
                        </div>

                        <ul class="list-group" v-else>
                            <li class="list-group-item" v-for="(j, idx) in jogosOrdenados" :key="idx">
                                <a :href="'editar-partida/' + j.id">
                                    Baba do dia
                                    <strong>{{ formatarData(j.data_partida) }}</strong>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import { swalError, swalSuccess } from '@/utils/utils';

    export default {
        name: 'Panel',

        data() {
            return {
                mensalistas: [],
                jogo: [],
                dataJogo: null,
                listaJogos: [],
                loading: false,
            }
        },
        created() {
            this.pegarListaMensalistas();
        },
        computed: {
            mensalistasAtivos() {
                return this.mensalistas
                    .filter(m => m.ativo === 1)
                    .sort((a, b) =>
                        a.nome.localeCompare(b.nome, 'pt-BR', { sensitivity: 'base' })
                    );
            },
            jogadoresVisitantes() {
                return this.mensalistas.filter(m => m.ativo === 0);
            },
            jogosOrdenados() {
                return this.listaJogos.sort((a,b) => b.id - a.id);
            }
        },
        methods: {
            async pegarListaMensalistas() {
                this.$loading.show();
                try {
                    const response = await axios.get('listar-mensalistas');
                    this.mensalistas = response.data.data;
                    this.mensalistas.sort((a, b) =>
                        a.nome.localeCompare(b.nome, 'pt-BR', { sensitivity: 'base' })
                    );

                    // Criar tabela de estatísticas com base nos nomes
                    this.jogo = this.mensalistasAtivos.map(m => ({
                        nome: m.nome,
                        jogador_id: m.id,
                        total_gols: 0,
                        total_gols_contra: 0,
                        total_assistencias: 0,
                        total_amarelo: 0,
                        total_vermelho: 0,
                        total_azul: 0
                    }));
                }catch(error) {
                    console.error(error);
                    swalError(error.response.data.error)
                }finally{
                    this.$loading.hide();
                }
            },
            async carregarListaDeJogos() {
                this.loading = true;
                try {
                    const response = await axios.get('carregar-jogos');
                    this.listaJogos = response.data.data;
                }catch(error) {
                    console.error(error);
                    swalError('Nao foi possivel carregar lista de jogos');
                } finally {
                    this.loading = false;
                }
            },
            async salvarMensalistas() {
                try {
                    await axios.post('atualizar-mensalistas', { mensalistas: this.mensalistas });
                    swalSuccess(response.data.message);
                    this.pegarListaMensalistas();
                } catch (error) {
                    console.error(error)
                    swalError("Erro ao atualizar lista de mensalistas.");
                }
            },
            async registrarJogo() {
                if(this.dataJogo === null) {
                    swalError("Insira a data do baba.");
                    return;
                }

                this.$loading.show();
                try {
                    await axios.post('registrar-jogo', { jogo: this.jogo, dataJogo: this.dataJogo });
                    swalSuccess("Baba registrado com sucesso!");
                    this.pegarListaMensalistas();
                } catch (error) {
                    console.error(error)
                    swalError("Erro ao registrar baba.");
                }
                finally {
                    this.$loading.hide();
                }
            },
            addJogador() {
                this.mensalistas.push({
                    'ativo': 0,
                    'nome': ''
                })
            },
            addVisitante(id, nome) {
                this.jogo.push({
                    nome: nome,
                    jogador_id: id,
                    gols: 0,
                    gols_contra: 0,
                    assistencias: 0,
                    cartao_amarelo: 0,
                    cartao_vermelho: 0,
                    cartao_azul: 0
                });
            },
            formatarData(isoDate) {
                const data = new Date(isoDate);

                const dia = String(data.getDate()).padStart(2, '0');
                const mes = String(data.getMonth() + 1).padStart(2, '0'); // mês começa em 0
                const ano = data.getFullYear();

                const formatoBR = `${dia}/${mes}/${ano}`;

                return formatoBR;
            }
        }
    }
</script>

<style scoped>
    .collapse {
        visibility: visible !important;
    }
    .container-fluid {
        padding-bottom: 20px
    }
    label {
        font-weight: 500;
    }
    .dropdown-item {
        cursor: pointer;
    }

    @media(max-width: 499px) {
        .table-estatistica input[type='text'] {
            width: 80px !important;
        }
    }
</style>
