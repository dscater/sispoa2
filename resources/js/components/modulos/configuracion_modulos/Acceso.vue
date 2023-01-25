<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="w-10 text-md font-weight-bold text-center">
                        {{ o_modulo.modulo }}
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label
                        :class="{
                            'text-danger': errors.acceso,
                        }"
                        >Editar</label
                    >
                    <el-switch
                        :disabled="enviando"
                        style="display: block"
                        v-model="sw_editar"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                        active-text="HABILITADO"
                        inactive-text="DESHABILITADO"
                        @change="actualizaModulo"
                    >
                    </el-switch>
                </div>
                <div class="form-group col-md-6">
                    <label
                        :class="{
                            'text-danger': errors.acceso,
                        }"
                        >Eliminar</label
                    >
                    <el-switch
                        :disabled="enviando"
                        style="display: block"
                        v-model="sw_eliminar"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                        active-text="HABILITADO"
                        inactive-text="DESHABILITADO"
                        @change="actualizaModulo"
                    >
                    </el-switch>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["modulo"],
    data() {
        return {
            o_modulo: this.modulo,
            errors: [],
            sw_editar: this.modulo.editar == 1 ? true : false,
            sw_eliminar: this.modulo.eliminar == 1 ? true : false,
            enviando: false,
        };
    },
    watch: {
        modulo(newVal, oldVal) {
            this.o_modulo = newVal;
            this.sw_editar = this.o_modulo.editar == 1 ? true : false;
            this.sw_eliminar = this.o_modulo.eliminar == 1 ? true : false;
        },
    },
    methods: {
        actualizaModulo() {
            try {
                this.enviando = true;
                let url = "/admin/configuracion_modulos/" + this.modulo.id;
                let datos = {
                    _method: "put",
                    editar: this.sw_editar ? 1 : 0,
                    eliminar: this.sw_eliminar ? 1 : 0,
                };
                axios
                    .post(url, datos)
                    .then((res) => {
                        this.enviando = false;
                        // Swal.fire({
                        //     icon: "success",
                        //     title: res.data.msj,
                        //     showConfirmButton: false,
                        //     timer: 1500,
                        // });
                    })
                    .catch((error) => {
                        this.enviando = false;
                        if (this.accion == "edit") {
                            this.textoBtn = "Actualizar";
                        } else {
                            this.textoBtn = "Registrar";
                        }
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.errors = error.response.data.errors;
                            }
                        }
                    });
            } catch (e) {
                this.enviando = false;
                console.log(e);
            }
        },
    },
};
</script>
<style></style>
