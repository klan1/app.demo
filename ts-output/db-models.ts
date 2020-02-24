import { Injectable } from '@angular/core';

@Injectable()
export class DiasFestivosModel {
    IdDiaFestivo: number;
    FechaDiaFestivo: string;
    NombreDiaFestivo: string;
    DiaDeLaSemana: number;

}
export class AmbientesModel {
    id: number;
    name: string;

}
export class AnimalGrupoLineaPesoModel {
    peso_id: number;
    fk_linea_id: number;
    peso_genero: string;
    peso_semana: string;
    peso_unidad: string;
    peso_min: number;
    peso_max: number;
    peso_prom: number;
    postura: number;
    consumo: number;
    huevos: number;

}
export class AnimalGruposModel {
    animal_grupo_id: number;
    grupo_nombre: string;
    fk_animal_id: number;
    estado: number;

}
export class AnimalLineaModel {
    linea_id: number;
    fk_animal_grupo_id: number;
    fk_animal_id: number;
    linea_nombre: string;
    linea_descripcion: string;

}
export class AnimalesModel {
    animal_id: number;
    animal_nombre: string;
    animal_descripcion: string;
    estado: number;

}
export class CodCostaricaModel {
    Provincia: string;
    Canton: string;
    Distrito: string;
    Poblado: string;
    DistritoId: string;

}
export class CodEcuadorModel {
    cod_prov: string;
    cod_cant: string;
    cod_parro: string;
    nom_pro: string;
    nom_cant: string;
    nom_parro: string;

}
export class CodParaguayModel {
    cod: number;
    region: string;
    ciudad: string;
    cod_postal: string;

}
export class DepartamentoModel {
    departamento_id: number;
    fk_pais_id: string;
    departamento_nombre: string;

}
export class EmpresaGranjaGalponesModel {
    galpone_id: number;
    fk_granja_id: number;
    fk_empresa_id: number;
    galpon_nombre: string;
    tipo_ambiente: string;
    sistema_produccion: string;
    estado: number;

}
export class EmpresaGranjasModel {
    granja_id: number;
    fk_empresa_id: number;
    granja_nombre: string;
    estado: number;

}
export class EmpresasModel {
    empresa_id: number;
    fk_municipio_id: number;
    fk_departamento_id: number;
    fk_pais_id: string;
    nit: string;
    nombre_empresa: string;
    razon_social: string;
    estado: number;

}
export class EstudioGalponIndividiosModel {
    individio_id: number;
    fk_estudio_galpon_id: number;
    fk_estudio_id: number;
    fk_animal_id: number;
    fk_granja_id: number;
    fk_empresa_id: number;
    fk_linea_id: number;
    fk_galpone_id: number;

}
export class EstudioGalponResultadosModel {
    resultado_id: number;
    fk_estudio_galpon_id: number;
    fk_estudio_id: number;
    lote: string;
    fk_animal_id: number;
    fk_empresa_id: number;
    fk_granja_id: number;
    fk_linea_id: number;
    fk_galpone_id: number;
    fk_variable_id: number;
    resultado: string;
    maximo_esperado: number;
    ir: number;
    nivel_afeccion: number;
    porcentaje_afectacion: number;
    calificacion: string;
    index_g: number;
    isag: number;

}
export class EstudioGalponesModel {
    estudio_galpon_id: number;
    fk_estudio_id: number;
    fk_animal_id: number;
    fk_empresa_id: number;
    fk_granja_id: number;
    fk_linea_id: number;
    fk_galpone_id: number;
    individuos_semanas: number;
    individios_genero: string;
    estudio_galpon_lote: string;
    description: string;
    estado: number;
    postura: number;
    consumo: number;
    huevos: number;

}
export class EstudioIndividioVariableModel {
    fk_variable_id: number;
    fecha_estudio_individuo: string;
    variable_valor: number;
    fk_individio_id: number;
    fk_estudio_galpon_id: number;
    fk_estudio_id: number;
    fk_animal_id: number;
    fk_granja_id: number;
    fk_empresa_id: number;
    fk_linea_id: number;
    fk_galpone_id: number;
    individuo_imagen: string;
    estado: number;

}
export class EstudiosModel {
    estudio_id: number;
    fk_animal_id: number;
    fk_empresa_id: number;
    fk_granja_id: number;
    fk_user_id: number;
    estudio_num_animales: number;
    estudios: string;
    fecha_estudio: string;

}
export class EstudiosGrupoVariablesModel {
    fk_grupo_variable_id: number;
    fk_estudio_id: number;

}
export class EventsModel {
    id: number;
    event_name: string;
    start_date: string;
    end_date: string;
    fk_empresa_id: number;
    nombre_empresa: string;
    fk_granja_id: number;
    granja_nombre: string;
    fk_galpone_id: number;
    galpon_nombre: string;
    estudio_galpon_lote: string;
    animal_nombre: string;
    linea_nombre: string;
    individios_genero: string;
    estudio_num_animales: number;
    individuos_semanas: number;
    link: string;
    fk_user_id: number;
    email: string;
    nombres: string;
    apellidos: string;
    estudio_tipo: string;
    estudio_completado: string;

}
export class EventsModel {
    id: number;
    created_at: number;
    updated_at: number;
    event_name: string;
    start_date: string;
    end_date: string;
    link: string;

}
export class EventsUltimosModel {
    id: number;
    event_name: string;
    start_date: string;
    end_date: string;
    fk_empresa_id: number;
    nombre_empresa: string;
    fk_granja_id: number;
    granja_nombre: string;
    fk_galpone_id: number;
    galpon_nombre: string;
    estudio_galpon_lote: string;
    animal_nombre: string;
    linea_nombre: string;
    individios_genero: string;
    estudio_num_animales: number;
    individuos_semanas: number;
    link: string;
    fk_user_id: number;
    email: string;
    nombres: string;
    apellidos: string;
    estudio_tipo: string;
    estudio_completado: string;

}
export class GrupoVariablesModel {
    grupo_variable_id: number;
    grupo_nombre: string;
    constante: number;

}
export class MigrationsModel {
    id: number;
    migration: string;
    batch: number;

}
export class MunicipioModel {
    municipio_id: number;
    fk_departamento_id: number;
    fk_pais_id: string;
    municipio_nombre: string;

}
export class PaisModel {
    pais_id: string;
    pais_nombre: string;

}
export class PasswordResetsModel {
    email: string;
    token: string;
    created_at: number;

}
export class SistemasModel {
    id: number;
    name: string;

}
export class UsersModel {
    id: number;
    name: string;
    email: string;
    password: string;
    remember_token: string;
    created_at: number;
    updated_at: number;
    nombres: string;
    apellidos: string;
    cedula: string;
    rol: string;
    type1: number;
    type2: number;
    type3: number;
    type4: number;
    id_empresa: number;

}
export class UsersEmpresasGranjasModel {
    id: number;
    user_id: number;
    fk_granja_id: number;
    fk_empresa_id: number;
    created_at: number;
    updated_at: number;

}
export class VariablesModel {
    variable_id: number;
    fk_animal_id: number;
    variables_macro: string;
    variable_nombre: string;
    variable_descripcion: string;
    variable_min: number;
    variable_max: number;
    index_g: number;
    variables_apoyo_visual: string;
    ir_operador: string;
    calificacion_operador: string;
    ir_tolerancia: number;
    calificacion_tolerancia: number;
    variable_max_prog: number;
    calificacion_texto_pos: string;
    calificacion_texto_neg: string;
    operacion_resultado: string;
    con: number;

}
export class VariablesGrupoVariablesModel {
    fk_variable_id: number;
    fk_animal_id: number;
    fk_grupo_variable_id: number;

}
