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
                        <input type="date" class="form-control w-25 mb-2" id="dataJogo">

                        <div class="table-responsive mb-3">
                            <table class="table table-striped table-bordered">
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
                                    <!--tr v-for="(item, idx) in estatisticasFiltradas" :key="idx">
                                        <td>{{ item.nome }}</td>
                                        <td >{{ item.total_gols }}</td>
                                        <td :class="colunaDestaque('total_gols_contra')">{{ item.total_gols_contra }}</td>
                                        <td :class="colunaDestaque('total_assistencias')">{{ item.total_assistencias }}</td>
                                        <td :class="colunaDestaque('total_amarelo')">{{ item.total_amarelo }}</td>
                                        <td :class="colunaDestaque('total_vermelho')">{{ item.total_vermelho }}</td>
                                        <td :class="colunaDestaque('total_azul')">{{ item.total_azul }}</td>
                                    </tr-->
                                </tbody>
                            </table>
                        </div>
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
            }
        },
        created() {
            this.pegarListaMensalistas();
        },
        methods: {
            async pegarListaMensalistas() {
                this.$loading.show();
                try {
                    const response = await axios.get('listar-mensalistas');
                    this.mensalistas = response.data.data;
                    console.log(this.mensalistas)
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
            addJogador() {
                this.mensalistas.push({
                    'ativo': 0,
                    'nome': ''
                })
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
</style>
