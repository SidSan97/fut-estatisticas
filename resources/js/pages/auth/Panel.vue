<template>
    <div class="container-fluid">
        <h2 class="text-center mt-3 mb-3">Painel administrativo</h2>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Gerenciar Jogadores
                    </button>
                </h2>

                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
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

                            <button class="btn btn-primary mt-3" @click="salvarMensalistas">
                                Salvar alterações
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Accordion Item #2
                    </button>
                </h2>

                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
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
</style>
