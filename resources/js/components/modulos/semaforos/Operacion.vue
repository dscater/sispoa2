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
            <div class="row">
                <div class="col-md-12">
                    <label v-if="o_Operacion.subdireccion_id">Subunidad</label>
                    <div class="px-2 py-2 border">
                        {{
                            listSubdireccions.filter(
                                (elem) => elem.id == o_Operacion.subdireccion_id
                            )[0]?.nombre
                        }}
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label>Código de Operación</label>
                    <div class="px-2 py-2 w-100 border">
                        {{ o_Operacion.codigo_operacion }}
                    </div>
                </div>

                <div class="col-md-6 mt-3">
                    <label>Operación</label>
                    <div class="px-2 py-2 w-100 border">
                        {{ o_Operacion.operacion }}
                    </div>
                </div>
                <div class="form-group col-md-2 mt-3">
                    <label>Ponderación %</label>
                    <div class="px-2 py-2 w-100 border">
                        {{ o_Operacion.ponderacion }}
                    </div>
                </div>
                <div class="form-group col-md-4 mt-3">
                    <label>Resultado intermedio esperado</label>
                    <div class="px-2 py-2 w-100 border">
                        {{ o_Operacion.resultado_esperado }}
                    </div>
                </div>
                <div class="form-group col-md-4 mt-3">
                    <label>Medios de verificación</label>
                    <div class="px-2 py-2 w-100 border">
                        {{ o_Operacion.medios_verificacion }}
                    </div>
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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                        readonly
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
                                                        readonly
                                                        v-model="detalle.pt_e"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.pt_e,
                                                            'bg-red':
                                                                detalle.pt_e_est ==
                                                                    0 &&
                                                                detalle.pt_e !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.pt_e_est ==
                                                                    1 ||
                                                                detalle.pt_e_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.pt_e_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>F</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.pt_f"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.pt_f,
                                                            'bg-red':
                                                                detalle.pt_f_est ==
                                                                    0 &&
                                                                detalle.pt_f !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.pt_f_est ==
                                                                    1 ||
                                                                detalle.pt_f_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.pt_f_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>M</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.pt_m"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.pt_m,
                                                            'bg-red':
                                                                detalle.pt_m_est ==
                                                                    0 &&
                                                                detalle.pt_m !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.pt_m_est ==
                                                                    1 ||
                                                                detalle.pt_m_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.pt_m_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>A</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.st_a"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.st_a,
                                                            'bg-red':
                                                                detalle.st_a_est ==
                                                                    0 &&
                                                                detalle.st_a !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.st_a_est ==
                                                                    1 ||
                                                                detalle.st_a_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.st_a_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>M</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.st_m"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.st_m,
                                                            'bg-red':
                                                                detalle.st_m_est ==
                                                                    0 &&
                                                                detalle.st_m !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.st_m_est ==
                                                                    1 ||
                                                                detalle.st_m_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.st_m_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>J</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.st_j"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.st_j,
                                                            'bg-red':
                                                                detalle.st_j_est ==
                                                                    0 &&
                                                                detalle.st_j !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.st_j_est ==
                                                                    1 ||
                                                                detalle.st_j_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.st_j_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>J</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.tt_j"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.tt_j,
                                                            'bg-red':
                                                                detalle.tt_j_est ==
                                                                    0 &&
                                                                detalle.tt_j !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.tt_j_est ==
                                                                    1 ||
                                                                detalle.tt_j_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.tt_j_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>A</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.tt_a"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.tt_a,
                                                            'bg-red':
                                                                detalle.tt_a_est ==
                                                                    0 &&
                                                                detalle.tt_a !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.tt_a_est ==
                                                                    1 ||
                                                                detalle.tt_a_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.tt_a_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>S</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.tt_s"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.tt_s,
                                                            'bg-red':
                                                                detalle.tt_s_est ==
                                                                    0 &&
                                                                detalle.tt_s !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.tt_s_est ==
                                                                    1 ||
                                                                detalle.tt_s_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.tt_s_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>O</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.ct_o"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.ct_o,
                                                            'bg-red':
                                                                detalle.ct_o_est ==
                                                                    0 &&
                                                                detalle.ct_o !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.ct_o_est ==
                                                                    1 ||
                                                                detalle.ct_o_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.ct_o_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>N</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.ct_n"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.ct_n,
                                                            'bg-red':
                                                                detalle.ct_n_est ==
                                                                    0 &&
                                                                detalle.ct_n !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.ct_n_est ==
                                                                    1 ||
                                                                detalle.ct_n_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.ct_n_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                                <td class="text-center">
                                                    <label>D</label>
                                                    <input
                                                        type="number"
                                                        readonly
                                                        v-model="detalle.ct_d"
                                                        class="form-control"
                                                        :class="{
                                                            'font-weight-bold':
                                                                detalle.ct_d,
                                                            'bg-red':
                                                                detalle.ct_d_est ==
                                                                    0 &&
                                                                detalle.ct_d !=
                                                                    '',
                                                            'bg-warning':
                                                                detalle.ct_d_est ==
                                                                    1 ||
                                                                detalle.ct_d_est ==
                                                                    3,
                                                            'bg-success':
                                                                detalle.ct_d_est ==
                                                                2,
                                                        }"
                                                    />
                                                </td>
                                            </tr>
                                            <tr class="estados_files">
                                                <td>
                                                    <a
                                                        :href="detalle.pt_e_url"
                                                        :disabled="
                                                            detalle.pt_e_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.pt_e &&
                                                            detalle.pt_e != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_e_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.pt_f_url"
                                                        :disabled="
                                                            detalle.pt_f_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.pt_f &&
                                                            detalle.pt_f != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_f_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.pt_m_url"
                                                        :disabled="
                                                            detalle.pt_m_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.pt_m &&
                                                            detalle.pt_m != ''
                                                        "
                                                        :class="[
                                                            detalle.pt_m_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.st_a_url"
                                                        :disabled="
                                                            detalle.st_a_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.st_a &&
                                                            detalle.st_a != ''
                                                        "
                                                        :class="[
                                                            detalle.st_a_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.st_m_url"
                                                        :disabled="
                                                            detalle.st_m_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.st_m &&
                                                            detalle.st_m != ''
                                                        "
                                                        :class="[
                                                            detalle.st_m_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.st_j_url"
                                                        :disabled="
                                                            detalle.st_j_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.st_j &&
                                                            detalle.st_j != ''
                                                        "
                                                        :class="[
                                                            detalle.st_j_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.tt_j_url"
                                                        :disabled="
                                                            detalle.tt_j_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.tt_j &&
                                                            detalle.tt_j != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_j_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.tt_a_url"
                                                        :disabled="
                                                            detalle.tt_a_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.tt_a &&
                                                            detalle.tt_a != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_a_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.tt_s_url"
                                                        :disabled="
                                                            detalle.tt_s_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.tt_s &&
                                                            detalle.tt_s != ''
                                                        "
                                                        :class="[
                                                            detalle.tt_s_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.ct_o_url"
                                                        :disabled="
                                                            detalle.ct_o_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.ct_o &&
                                                            detalle.ct_o != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_o_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.ct_n_url"
                                                        :disabled="
                                                            detalle.ct_n_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.ct_n &&
                                                            detalle.ct_n != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_n_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                                <td>
                                                    <a
                                                        :href="detalle.ct_d_url"
                                                        :disabled="
                                                            detalle.ct_d_url
                                                                ? false
                                                                : true
                                                        "
                                                        target="_blank"
                                                        v-if="
                                                            detalle.ct_d &&
                                                            detalle.ct_d != ''
                                                        "
                                                        :class="[
                                                            detalle.ct_d_file
                                                                ? 'active'
                                                                : '',
                                                        ]"
                                                        ><i
                                                            class="fa fa-paperclip"
                                                        ></i
                                                    ></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.pt_e_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.pt_e_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.pt_e != ''" class="font-weight-bold">{{ detalle.pt_e_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.pt_f_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.pt_f_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.pt_f != ''" class="font-weight-bold">{{ detalle.pt_f_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.pt_m_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.pt_m_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.pt_m != ''" class="font-weight-bold">{{ detalle.pt_m_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.st_a_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.st_a_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.st_a != ''" class="font-weight-bold">{{ detalle.st_a_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.st_m_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.st_m_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.st_m != ''" class="font-weight-bold">{{ detalle.st_m_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.st_j_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.st_j_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.st_j != ''" class="font-weight-bold">{{ detalle.st_j_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.tt_j_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.tt_j_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.tt_j != ''" class="font-weight-bold">{{ detalle.tt_j_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.tt_a_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.tt_a_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.tt_a != ''" class="font-weight-bold">{{ detalle.tt_a_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.tt_s_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.tt_s_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.tt_s != ''" class="font-weight-bold">{{ detalle.tt_s_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.ct_o_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.ct_o_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.ct_o != ''" class="font-weight-bold">{{ detalle.ct_o_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.ct_n_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.ct_n_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.ct_n != ''" class="font-weight-bold">{{ detalle.ct_n_est_t }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <select
                                                        v-if="detalle.ct_d_file && oUser.tipo=='SUPER USUARIO'"
                                                        class="form-select rounded-0"
                                                        v-model="
                                                            detalle.ct_d_est
                                                        "
                                                    >
                                                        <option
                                                            v-for="item_list in listEstados"
                                                            :value="
                                                                item_list.value
                                                            "
                                                        >
                                                            {{
                                                                item_list.label
                                                            }}
                                                        </option>
                                                    </select>
                                                    <span v-if="detalle.ct_d != ''" class="font-weight-bold">{{ detalle.ct_d_est_t }}</span>
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
            listEstados: [
                {
                    value: 2,
                    label: "REVISADO",
                },
                {
                    value: 3,
                    label: "RECHAZADO",
                },
            ],
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

.detalle_trimestres tr.estados_files td a {
    display: block;
    padding: 4px;
    font-size: 1em;
    position: relative;
    background-color: rgba(77, 77, 77, 0.445);
    max-width: 100%;
    margin: auto;
    color: white;
    border-radius: 0px;
    text-align: center;
}

.detalle_trimestres tr.estados_files td a[disabled="disabled"] {
    cursor: not-allowed;
}

.detalle_trimestres tr.estados_files td a.active {
    background-color: rgb(0, 184, 144);
    color: white;
}

.detalle_trimestres tr.estados_files td input {
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
