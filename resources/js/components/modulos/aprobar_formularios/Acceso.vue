<template>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="w-10 text-md font-weight-bold text-center">
                        {{ user?.unidad.nombre }}
                    </p>
                </div>
                <div class="form-group col-md-12 text-center">
                    <label
                        :class="{
                            'text-danger': errors.acceso,
                        }"
                        >Pendiente/Aprobado</label
                    >
                    <el-switch
                        :disabled="enviando"
                        style="display: block"
                        v-model="sw_estado"
                        active-color="#13ce66"
                        inactive-color="#ff4949"
                        active-text="APROBADO"
                        inactive-text="PENDIENTE"
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
            user: JSON.parse(localStorage.getItem("user")),
            o_modulo: this.modulo,
            errors: [],
            sw_estado: this.modulo.estado == 1 ? true : false,
            sw_eliminar: this.modulo.eliminar == 1 ? true : false,
            enviando: false,
        };
    },
    watch: {
        modulo(newVal, oldVal) {
            this.o_modulo = newVal;
            this.sw_estado = this.o_modulo.estado == 1 ? true : false;
            this.sw_eliminar = this.o_modulo.eliminar == 1 ? true : false;
        },
    },
    methods: {
        actualizaModulo() {
            try {
                this.enviando = true;
                let url = "/admin/aprobar_formularios/" + this.modulo.id;
                let datos = {
                    _method: "put",
                    estado: this.sw_estado ? 1 : 0,
                };
                axios
                    .post(url, datos)
                    .then((res) => {
                        this.enviando = false;
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
