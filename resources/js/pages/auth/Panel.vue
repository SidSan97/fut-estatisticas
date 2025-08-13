<template>
    <div class="container-fluid">
        <h2 class="text-center mt-3 mb-3">Painel administrativo</h2>

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
                        <input type="date" class="form-control w-25 mb-2" id="dataJogo" v-model="dataJogo">

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
                                        <td><input type="number" class="form-control" v-model.number="item.gols" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.gols_contra" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.assistencias" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.cartao_amarelo" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.cartao_vermelho" min="0"></td>
                                        <td><input type="number" class="form-control" v-model.number="item.cartao_azul" min="0"></td>
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
                    </div>
                    <button class="btn btn-success ms-1" @click="registrarJogo">
                        Registrar baba
                    </button>
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
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Accordion Item #3
                    </button>
                </h2>

                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
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
                        gols: 0,
                        gols_contra: 0,
                        assistencias: 0,
                        cartao_amarelo: 0,
                        cartao_vermelho: 0,
                        cartao_azul: 0
                    }));
                }catch(error) {
                    console.error(error);
                    swalError(error.response.data.error)
                }finally{
                    this.$loading.hide();
                }
            },
            async salvarMensalistas() {
                try {
                    await axios.post('atualizar-mensalistas', { mensalistas: this.mensalistas });
                    swalSuccess('Dados salvos com sucesso!');
                    this.pegarListaMensalistas();
                } catch (error) {
                    console.error(error)
                    swalError("Erro ao atualizar lista de mensalistas.");
                }
            },
            async registrarJogo() {
                console.log(this.jogo)
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
</style>
