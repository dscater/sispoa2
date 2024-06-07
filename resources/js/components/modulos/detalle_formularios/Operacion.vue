<template>
    <div
        class="card detalle contenedor_operacion"
        :class="[(index + 1) % 2 == 0 ? 'bg-white' : '']"
    >
        <div class="card-body">
            <span
                class="rounded-circle numero_detalle"
                v-text="index + 1"
            ></span>
            <button
                class="btn btn-danger rounded-circle btnQuitar"
                @click="quitar"
                v-if="oUser.tipo != 'ENLACE'"
            >
                X
            </button>
            <div class="row">
                <div class="form-group col-md-12" v-if="oUser.tipo != 'ENLACE'">
                    <label>Seleccionar subunidad</label>
                    <el-select
                        placeholder="Sin subunidad"
                        class="w-100 d-block"
                        :class="{
                            'is-invalid': errors.subdireccion_id,
                        }"
                        v-model="o_Operacion.subdireccion_id"
                        clearable
                    >
                        <el-option
                            v-for="(item, index) in listSubdireccions"
                            :key="index"
                            :value="item.id"
                            :label="item.nombre"
                        >
                        </el-option>
                    </el-select>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.subdireccion_id"
                        v-text="errors.subdireccion_id[0]"
                    ></span>
                </div>
                <div class="form-group col-md-12" v-else>
                    <label v-if="o_Operacion.subdireccion_id">Subunidad</label>
                    {{
                        listSubdireccions.filter(
                            (elem) => elem.id == o_Operacion.subdireccion_id
                        )[0]?.nombre
                    }}
                </div>

                <div class="form-group col-md-6">
                    <label>Código de Operación</label>
                    <input
                        placeholder="Código de Operación"
                        class="form-control"
                        :class="{
                            'is-invalid': errors.codigo_operacion,
                        }"
                        v-model="o_Operacion.codigo_operacion"
                        :readonly="oUser.tipo == 'ENLACE'"
                    />
                    <span
                        class="error invalid-feedback"
                        v-if="errors.codigo_operacion"
                        v-text="errors.codigo_operacion[0]"
                    ></span>
                </div>

                <div class="input-group col-md-6">
                    <label>Operación</label>
                    <el-input
                        type="textarea"
                        autosize
                        placeholder="Operación"
                        :class="{
                            'is-invalid': errors.operacion,
                        }"
                        v-model="o_Operacion.operacion"
                        clearable
                        :readonly="oUser.tipo == 'ENLACE'"
                    >
                    </el-input>
                    <span
                        class="error invalid-feedback"
                        v-if="errors.operacion"
                        v-text="errors.operacion[0]"
                    ></span>
                </div>
                <div class="form-group col-md-2">
                    <label>Ponderación %</label>
                    <input
                        type="number"
                        placeholder="Ponderación %"
                        v-model="o_Operacion.ponderacion"
                        class="form-control"
                        step="0.01"
                        :class="{
                            'is-invalid': errors['ponderacion'],
                        }"
                        :readonly="oUser.tipo == 'ENLACE'"
                    />
                    <span
                        class="error invalid-feedback"
                        v-if="errors['ponderacion']"
                        v-text="errors['ponderacion'][0]"
                    ></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Resultado intermedio esperado</label>
                    <el-input
                        type="textarea"
                        autosize
                        placeholder="Resultado intermedio esperado"
                        :class="{
                            'is-invalid': errors['resultado_esperado'],
                        }"
                        v-model="o_Operacion.resultado_esperado"
                        clearable
                        :readonly="oUser.tipo == 'ENLACE'"
                    >
                    </el-input>
                    <span
                        class="error invalid-feedback"
                        v-if="errors['resultado_esperado']"
                        v-text="errors['resultado_esperado'][0]"
                    ></span>
                </div>
                <div class="form-group col-md-4">
                    <label>Medios de verificación</label>
                    <el-input
                        type="textarea"
                        autosize
                        placeholder="Medios de verificación"
                        :class="{
                            'is-invalid': errors['medios_verificacion'],
                        }"
                        v-model="o_Operacion.medios_verificacion"
                        :readonly="oUser.tipo == 'ENLACE'"
                    >
                    </el-input>
                    <span
                        class="error invalid-feedback"
                        v-if="errors['medios_verificacion']"
                        v-text="errors['medios_verificacion'][0]"
                    ></span>
                </div>
            </div>
            <div
                class="row detalle"
                v-for="(
                    detalle, index_detalle
                ) in o_Operacion.detalle_operaciones"
            >
                <span
                    class="numero_operacion_detalle_cuatro"
                    v-text="
                        index +
                        1 +
                        '-' +
                        (index_detalle + 1) +
                        ' Actividad/Tarea'
                    "
                ></span>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-body text-black p-2">
                            <button
                                class="btn btn-danger rounded-circle btnQuitar"
                                @click="
                                    quitarDetalle(index_detalle, detalle.id)
                                "
                                v-if="index_detalle > 0"
                            >
                                X
                            </button>
                            <div class="row mt-3">
                                <div class="form-group col-md-2">
                                    <label>Código tarea</label>
                                    <el-input
                                        placeholder="Código tarea"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'codigo_tarea.' +
                                                        index_detalle
                                                ],
                                        }"
                                        v-model="detalle.codigo_tarea"
                                        clearable
                                        :readonly="oUser.tipo == 'ENLACE'"
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="
                                            errors[
                                                'codigo_tarea.' + index_detalle
                                            ]
                                        "
                                        v-text="
                                            errors[
                                                'codigo_tarea.' + index_detalle
                                            ][0]
                                        "
                                    ></span>
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Actividad/Tarea</label>
                                    <el-input
                                        type="textarea"
                                        autosize
                                        placeholder="Actividad/Tarea"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'actividad_tarea.' +
                                                        index_detalle
                                                ],
                                        }"
                                        v-model="detalle.actividad_tarea"
                                        clearable
                                        :readonly="oUser.tipo == 'ENLACE'"
                                    >
                                    </el-input>
                                    <span
                                        class="error invalid-feedback"
                                        v-if="
                                            errors[
                                                'actividad_tarea.' +
                                                    index_detalle
                                            ]
                                        "
                                        v-text="
                                            errors[
                                                'actividad_tarea.' +
                                                    index_detalle
                                            ][0]
                                        "
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Inicio</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'inicio.' + index_detalle
                                                ],
                                        }"
                                        v-model="detalle.inicio"
                                        :readonly="oUser.tipo == 'ENLACE'"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors['inicio.' + index_detalle]"
                                        v-text="
                                            errors['inicio.' + index_detalle][0]
                                        "
                                    ></span>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Final</label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        :class="{
                                            'is-invalid':
                                                errors[
                                                    'final.' + index_detalle
                                                ],
                                        }"
                                        v-model="detalle.final"
                                        :readonly="oUser.tipo == 'ENLACE'"
                                    />
                                    <span
                                        class="error invalid-feedback"
                                        v-if="errors['final.' + index_detalle]"
                                        v-text="
                                            errors['final.' + index_detalle][0]
                                        "
                                    ></span>
                                </div>

                                <div class="col-md-12 contenedor_tabla">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-center"
                                                    colspan="12"
                                                >
                                                    Programación de ejecución de
                                                    operaciones y actividades
                                                </th>
                                            </tr>
                                            <tr>
                                                <th
                                                    class="text-center"
                                                    colspan="3"
                                                >
                                                    1er Trim.
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="3"
                                                >
                                                    2do Trim.
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="3"
                                                >
                                                    3er Trim.
                                                </th>
                                                <th
                                                    class="text-center"
                                                    colspan="3"
                                                >
                                                    4to Trim.
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="detalle_trimestres">
                                            <tr>
                                                <td class="text-center">
                                                    <label>E</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.pt_e"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'pt_e.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.pt_e,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>F</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.pt_f"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'pt_f.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.pt_f,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>M</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.pt_m"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'pt_m.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.pt_m,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>A</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.st_a"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'st_a.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.st_a,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>M</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.st_m"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'st_m.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.st_m,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>J</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.st_j"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'st_j.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.st_j,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>J</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.tt_j"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'tt_j.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.tt_j,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>A</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.tt_a"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'tt_a.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.tt_a,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>S</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.tt_s"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'tt_s.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.tt_s,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>O</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.ct_o"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'ct_o.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.ct_o,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>N</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.ct_n"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'ct_n.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.ct_n,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>D</label>
                                                    <input
                                                        type="number"
                                                        :readonly="
                                                            oUser.tipo ==
                                                            'ENLACE'
                                                        "
                                                        v-model="detalle.ct_d"
                                                        class="form-control"
                                                        :class="{
                                                            'is-invalid':
                                                                errors[
                                                                    'ct_d.' +
                                                                        index_detalle
                                                                ],
                                                            'bg-teal font-weight-bold':
                                                                detalle.ct_d,
                                                        }"
                                                    />
                                                </td>
                                            </tr>
                                            <tr
                                                class="files"
                                                v-if="oUser.tipo == 'ENLACE'"
                                            >
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.pt_e &&
                                                            detalle.pt_e != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_e_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'e_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'e_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'pt_e_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.pt_f &&
                                                            detalle.pt_f != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_f_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'f_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'f_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'pt_f_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.pt_m &&
                                                            detalle.pt_m != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_m_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'm_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'm_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'pt_m_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.st_a &&
                                                            detalle.st_a != ''
                                                        "
                                                        :class="[
                                                            detalle.st_a_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'a_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'a_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'st_a_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.st_m &&
                                                            detalle.st_m != ''
                                                        "
                                                        :class="[
                                                            detalle.st_m_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'm_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'm_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'st_m_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.st_j &&
                                                            detalle.st_j != ''
                                                        "
                                                        :class="[
                                                            detalle.st_j_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'j_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'j_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'st_j_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.tt_j &&
                                                            detalle.tt_j != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_j_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'j_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'j_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'tt_j_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.tt_a &&
                                                            detalle.tt_a != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_a_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'a_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'a_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'tt_a_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.tt_s &&
                                                            detalle.tt_s != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_s_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            's_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            's_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'tt_s_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.ct_o &&
                                                            detalle.ct_o != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_o_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'o_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'o_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'ct_o_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.ct_n &&
                                                            detalle.ct_n != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_n_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'n_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'n_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'ct_n_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                                <td>
                                                    <label
                                                        v-if="
                                                            detalle.ct_d &&
                                                            detalle.ct_d != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_d_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        :for="
                                                            'd_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></label>
                                                    <input
                                                        type="file"
                                                        :id="
                                                            'd_file_o_d' +
                                                            index +
                                                            index_detalle
                                                        "
                                                        @change="
                                                            cargarArchivo(
                                                                'ct_d_file',
                                                                index_detalle,
                                                                $event
                                                            )
                                                        "
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTON AGREGAR DETALLE -->
            <div class="row" v-if="oUser.tipo != 'ENLACE'">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                class="btn btn-default btn-flat btn-block"
                                @click="agregarDetalle"
                            >
                                <i class="fa fa-plus"></i>
                                Agregar detalle Actividad/Tarea
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        index: {
            type: Number,
            default: 0,
        },
        operacion: {
            type: Object,
            default: {
                id: 0,
                formulario_id: "",
                subdireccion_id: "",
                codigo_operacion: "",
                operacion: "",
                ponderacion: "",
                resultado_esperado: "",
                medios_verificacion: "",
                fecha_registro: "",
                detalle_operaciones: [],
            },
        },
        accion: {
            type: String,
            default: "create",
        },
    },
    data() {
        return {
            oUser:
                typeof localStorage.getItem("user") == "string"
                    ? JSON.parse(localStorage.getItem("user"))
                    : localStorage.getItem("user"),
            sw_accion: this.accion,
            errors: [],
            o_Operacion: this.operacion,
            listSubdireccions: [],
            _detalle_formulario_id: this.detalle_formulario_id,
        };
    },
    mounted() {
        if (this.o_Operacion.id == 0) {
            this.o_Operacion.detalle_operaciones = [];
            this.o_Operacion.detalle_operaciones.push({
                id: 0,
                operacion_id: "",
                ponderacion: "",
                resultado_esperado: "",
                medios_verificacion: "",
                codigo_tarea: "",
                actividad_tarea: "",
                pt_e: "",
                pt_f: "",
                pt_m: "",
                st_a: "",
                st_m: "",
                st_j: "",
                tt_j: "",
                tt_a: "",
                tt_s: "",
                ct_o: "",
                ct_n: "",
                ct_d: "",
                inicio: "",
                final: "",
            });
        }
        this.getSubdirecciones();
    },
    watch: {
        detalle_formulario_id(newVal, oldVal) {
            this._detalle_formulario_id = newVal;
        },
        operacion(newVal, oldVal) {
            this.o_Operacion = newVal;
        },
    },
    methods: {
        // QUITAR OPERACION
        quitar() {
            this.o_Operacion.detalle_operaciones = [];
            this.$emit("quitar", this.index, this.operacion);
        },

        // ACCIONES SOBRE EL DETALLE DE OPERACIONES
        agregarDetalle() {
            this.o_Operacion.detalle_operaciones.push({
                id: 0,
                operacion_id: "",
                ponderacion: "",
                resultado_esperado: "",
                medios_verificacion: "",
                codigo_tarea: "",
                actividad_tarea: "",
                pt_e: "",
                pt_f: "",
                pt_m: "",
                st_a: "",
                st_m: "",
                st_j: "",
                tt_j: "",
                tt_a: "",
                tt_s: "",
                ct_o: "",
                ct_n: "",
                ct_d: "",
                pt_e_file: null,
                pt_f_file: null,
                pt_m_file: null,
                st_a_file: null,
                st_m_file: null,
                st_j_file: null,
                tt_j_file: null,
                tt_a_file: null,
                tt_s_file: null,
                ct_o_file: null,
                ct_n_file: null,
                ct_d_file: null,
                inicio: "",
                final: "",
            });
        },

        cargarArchivo(key, index, e) {
            console.log(this.operacion.detalle_operaciones);
            this.operacion.detalle_operaciones[index][key] = e.target.files[0];
        },

        quitarDetalle(index, id) {
            this.o_Operacion.detalle_operaciones.splice(index, 1);
            if (id != 0) {
                this.$emit("quitar_detalle", id);
            }
        },
        getSubdirecciones() {
            axios.get("/admin/subdireccions").then((response) => {
                this.listSubdireccions = response.data.subdireccions;
            });
        },
    },
};
</script>
<style>
.row.detalle {
    position: relative;
}

.contenedor_tabla {
    overflow: auto;
}

.btnQuitar {
    padding: 0px;
    width: 30px;
    height: 30px;
    position: absolute;
    right: -10px;
    top: -10px;
    z-index: 100;
}

.numero_detalle {
    padding: 2px 0px;
    background: var(--principal);
    width: 37px;
    height: 37px;
    position: absolute;
    z-index: 100;
    left: -15px;
    top: -15px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.numero_operacion_detalle_cuatro {
    padding: 3px;
    background: var(--secondary);
    width: auto;
    height: auto;
    position: absolute;
    z-index: 100;
    left: 0px;
    top: 5px;
    border-radius: 5px;
    color: white;
    text-align: center;
    font-weight: bold;
    font-size: 0.7rem;
}

.detalle_trimestres tr td {
    padding: 0px;
    position: relative;
}

.detalle_trimestres tr td label {
    margin: 0px;
    position: absolute;
    font-size: 0.9rem;
    color: gray;
    top: 0px;
    left: 5px;
}

.detalle_trimestres tr td input {
    min-width: 45px;
    text-align: center;
}

.detalle_trimestres tr.files td label {
    display: block;
    position: relative;
    background-color: rgba(77, 77, 77, 0.445);
    max-width: 95%;
    margin: auto;
    color: white;
    border-radius: 0px;
    text-align: center;
}

.detalle_trimestres tr.files td label.active {
    background-color: rgb(0, 184, 144);
    color: white;
}

.detalle_trimestres tr.files td input {
    display: none !important;
}

.input-group-text {
    padding: 4px;
    min-height: 38px;
    height: 38px;
}

.contenedor_tabla table thead tr th,
.contenedor_tabla table tbody tr td {
    font-size: 0.7em;
}
</style>
