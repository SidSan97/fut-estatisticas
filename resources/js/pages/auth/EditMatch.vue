<template>
    <div class="container-fluid mb-3">
        <h2 class="text-center mb-3 mt-3">Editar dados do baba</h2>

        <button class="btn btn-warning">
            <a :href="route('panel')" class="nav-link"> <i class="bi bi-arrow-bar-left"></i> Voltar</a>
        </button> <br>

        <label for="dataJogo">Insira a data do baba</label>
        <input type="date" class="form-control w-50 mb-2" id="dataJogo" v-model="dataJogo">

        <div class="table-responsive mb-3">
            <table class="table table-striped table-bordered table-estatistica">
                <thead class="table-dark">
                    <tr>
                        <th>Nome</th>
                        <th>Gols</th>
                        <th>Gols Contra</th>
                        <th>Assistências</th>
                        <th>Cartões Amarelos</th>
                        <th>Cartões Vermelhos</th>
                        <th>Cartões Azuis</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in estatisticas" :key="idx">
                        <td v-if="item.jogador_id !== null">
                            {{ item.nome }}
                        </td>
                        <td v-else>
                            <input type="text" class="form-control" v-model="item.nome">
                        </td>
                        <td><input type="number" class="form-control" v-model.number="item.total_gols" min="0"></td>
                        <td><input type="number" class="form-control" v-model.number="item.total_gols_contra" min="0"></td>
                        <td><input type="number" class="form-control" v-model.number="item.total_assistencias" min="0"></td>
                        <td><input type="number" class="form-control" v-model.number="item.total_amarelo" min="0"></td>
                        <td><input type="number" class="form-control" v-model.number="item.total_vermelho" min="0"></td>
                        <td><input type="number" class="form-control" v-model.number="item.total_azul" min="0"></td>
                        <td>
                            <button type="button" class="btn btn-outline-danger" @click="removerJogador(item, idx)">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="btn btn-secondary" type="button" @click="addVisitante(null, '')">
            <i class="bi bi-plus-circle"></i> Adicionar visitante
        </button> <br>

        <button class="btn btn-success ms-2 mt-4" @click="atualizarJogo">
            Atualizar baba
        </button>
    </div>
</template>

<script>
    import axios from 'axios';
    import { swalError, swalSuccess } from '@/utils/utils';

    export default {
        name: 'EditMatch',

        props: {
            jogo: {
                type: Array,
                required: true
            }
        },
        data() {
            return {
                estatisticas: this.jogo,
                dataJogo: this.jogo[0].data_partida,
                idsDelete: [],
            }
        },
        methods: {
            addVisitante(id, nome) {
                this.estatisticas.push({
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
            removerJogador(data, indice) {
                if(data.jogador_id) {
                    this.idsDelete.push(data.jogador_id)
                }

                this.estatisticas.splice(indice, 1);
            },
            async atualizarJogo() {
                if(this.dataJogo == '') {
                    swalError("Insira a data do baba.");
                    return;
                }

                this.$loading.show();
                try {
                    const pathname = window.location.pathname;
                    const partes = pathname.split('/');
                    const id = parseInt(partes[partes.length - 1]);

                    await axios.post(route('editar.partida'), {
                        jogo: this.estatisticas,
                        jogoId: id,
                        dataJogo: this.dataJogo,
                        jogadoresDeletados: this.idsDelete.length > 0 ? this.idsDelete : null,
                    });
                    swalSuccess("Baba atualizado com sucesso!");
                    setTimeout(location.reload(), 3000);

                } catch (error) {
                    console.error(error)
                    swalError("Erro ao atualizar baba.");
                }
                finally {
                    this.$loading.hide();
                }
            },
        }
    }
</script>

<style scoped>
    @media(max-width: 499px) {
        tbody td input {
            width: 70px !important;
        }
    }
</style>
