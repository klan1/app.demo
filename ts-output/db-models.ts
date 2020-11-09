import { Injectable } from '@angular/core';

@Injectable()
export class DiasFestivosModel {
    IdDiaFestivo: number = null;
    FechaDiaFestivo: string = null;
    NombreDiaFestivo: string = null;
    DiaDeLaSemana: number = null;

}
export class AmbientesModel {
    id: number = null;
    name: string = null;

}
export class AnimalGrupoLineaPesoModel {
    peso_id: number = null;
    fk_linea_id: number = null;
    peso_genero: string = null;
    peso_semana: string = null;
    peso_unidad: string = null;
    peso_min: number = null;
    peso_max: number = null;
    peso_prom: number = null;
    postura: number = null;
    consumo: number = null;
    huevos: number = null;

}
export class AnimalGruposModel {
    animal_grupo_id: number = null;
    grupo_nombre: string = null;
    fk_animal_id: number = null;
    estado: number = null;

}
export class AnimalLineaModel {
    linea_id: number = null;
    fk_animal_grupo_id: number = null;
    fk_animal_id: number = null;
    linea_nombre: string = null;
    linea_descripcion: string = null;

}
export class AnimalesModel {
    animal_id: number = null;
    animal_nombre: string = null;
    animal_descripcion: string = null;
    estado: number = null;

}
export class CodCostaricaModel {
    Provincia: string = null;
    Canton: string = null;
    Distrito: string = null;
    Poblado: string = null;
    DistritoId: string = null;

}
export class CodEcuadorModel {
    cod_prov: string = null;
    cod_cant: string = null;
    cod_parro: string = null;
    nom_pro: string = null;
    nom_cant: string = null;
    nom_parro: string = null;

}
export class CodParaguayModel {
    cod: number = null;
    region: string = null;
    ciudad: string = null;
    cod_postal: string = null;

}
export class DepartamentoModel {
    departamento_id: number = null;
    fk_pais_id: string = null;
    departamento_nombre: string = null;

}
export class EmpresaGranjaGalponesModel {
    galpone_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    galpon_nombre: string = null;
    tipo_ambiente: string = null;
    sistema_produccion: string = null;
    estado: number = null;

}
export class EmpresaGranjasModel {
    granja_id: number = null;
    fk_empresa_id: number = null;
    granja_nombre: string = null;
    estado: number = null;

}
export class EmpresasModel {
    empresa_id: number = null;
    fk_municipio_id: number = null;
    fk_departamento_id: number = null;
    fk_pais_id: string = null;
    nit: string = null;
    nombre_empresa: string = null;
    razon_social: string = null;
    estado: number = null;

}
export class EstudioGalponIndividiosModel {
    individio_id: number = null;
    fk_estudio_galpon_id: number = null;
    fk_estudio_id: number = null;
    fk_animal_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    fk_linea_id: number = null;
    fk_galpone_id: number = null;

}
export class EstudioGalponResultadosModel {
    resultado_id: number = null;
    fk_estudio_galpon_id: number = null;
    fk_estudio_id: number = null;
    lote: string = null;
    fk_animal_id: number = null;
    fk_empresa_id: number = null;
    fk_granja_id: number = null;
    fk_linea_id: number = null;
    fk_galpone_id: number = null;
    fk_variable_id: number = null;
    resultado: string = null;
    maximo_esperado: number = null;
    ir: number = null;
    nivel_afeccion: number = null;
    porcentaje_afectacion: number = null;
    calificacion: string = null;
    index_g: number = null;
    isag: number = null;

}
export class EstudioGalponesModel {
    estudio_galpon_id: number = null;
    fk_estudio_id: number = null;
    fk_animal_id: number = null;
    fk_empresa_id: number = null;
    fk_granja_id: number = null;
    fk_linea_id: number = null;
    fk_galpone_id: number = null;
    individuos_semanas: number = null;
    individios_genero: string = null;
    estudio_galpon_lote: string = null;
    description: string = null;
    estado: number = null;
    postura: number = null;
    consumo: number = null;
    huevos: number = null;

}
export class EstudioIndividioVariableModel {
    fk_variable_id: number = null;
    fecha_estudio_individuo: string = null;
    variable_valor: number = null;
    fk_individio_id: number = null;
    fk_estudio_galpon_id: number = null;
    fk_estudio_id: number = null;
    fk_animal_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    fk_linea_id: number = null;
    fk_galpone_id: number = null;
    individuo_imagen: string = null;
    estado: number = null;

}
export class EstudioIpigValoresModel {
    resultado_ipig_id: number = null;
    fk_estudio_ipig_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    variable_id: number = null;
    tipo_variable: string = null;
    dias: number = null;
    valor: number = null;
    calificacion: number = null;

}
export class EstudiosModel {
    estudio_id: number = null;
    fk_animal_id: number = null;
    fk_empresa_id: number = null;
    fk_granja_id: number = null;
    fk_user_id: number = null;
    estudio_num_animales: number = null;
    estudios: string = null;
    fecha_estudio: string = null;

}
export class EstudiosGrupoVariablesModel {
    fk_grupo_variable_id: number = null;
    fk_estudio_id: number = null;

}
export class EstudiosIpigModel {
    estudio_ipig_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    fk_user_id: number = null;
    nombre: string = null;
    tipo_cria: number = null;
    tipo_cria_dias: number = null;
    tipo_levante: number = null;
    tipo_levante_dias: number = null;
    tipo_levante_alimento: string = null;
    tipo_ceba: number = null;
    tipo_ceba_dias: number = null;
    tipo_ceba_alimento: string = null;
    resultado: number = null;
    resultado_fecha: string = null;

}
export class EventsModel {
    id: number = null;
    event_name: string = null;
    start_date: string = null;
    end_date: string = null;
    fk_empresa_id: number = null;
    nombre_empresa: string = null;
    fk_granja_id: number = null;
    granja_nombre: string = null;
    fk_galpone_id: number = null;
    galpon_nombre: string = null;
    estudio_galpon_lote: string = null;
    animal_nombre: string = null;
    linea_nombre: string = null;
    individios_genero: string = null;
    estudio_num_animales: number = null;
    individuos_semanas: number = null;
    link: string = null;
    fk_user_id: number = null;
    email: string = null;
    nombres: string = null;
    apellidos: string = null;
    estudio_tipo: string = null;
    estudio_completado: string = null;

}
export class EventsModel {
    id: number = null;
    created_at: number = null;
    updated_at: number = null;
    event_name: string = null;
    start_date: string = null;
    end_date: string = null;
    link: string = null;

}
export class EventsUltimosModel {
    id: number = null;
    event_name: string = null;
    start_date: string = null;
    end_date: string = null;
    fk_empresa_id: number = null;
    nombre_empresa: string = null;
    fk_granja_id: number = null;
    granja_nombre: string = null;
    fk_galpone_id: number = null;
    galpon_nombre: string = null;
    estudio_galpon_lote: string = null;
    animal_nombre: string = null;
    linea_nombre: string = null;
    individios_genero: string = null;
    estudio_num_animales: number = null;
    individuos_semanas: number = null;
    link: string = null;
    fk_user_id: number = null;
    email: string = null;
    nombres: string = null;
    apellidos: string = null;
    estudio_tipo: string = null;
    estudio_completado: string = null;

}
export class GrupoVariablesModel {
    grupo_variable_id: number = null;
    grupo_nombre: string = null;
    constante: number = null;

}
export class MigrationsModel {
    id: number = null;
    migration: string = null;
    batch: number = null;

}
export class MunicipioModel {
    municipio_id: number = null;
    fk_departamento_id: number = null;
    fk_pais_id: string = null;
    municipio_nombre: string = null;

}
export class PaisModel {
    pais_id: string = null;
    pais_nombre: string = null;

}
export class PasswordResetsModel {
    email: string = null;
    token: string = null;
    created_at: number = null;

}
export class SistemasModel {
    id: number = null;
    name: string = null;

}
export class UsersModel {
    id: number = null;
    name: string = null;
    email: string = null;
    password: string = null;
    remember_token: string = null;
    created_at: number = null;
    updated_at: number = null;
    nombres: string = null;
    apellidos: string = null;
    cedula: string = null;
    rol: string = null;
    type1: number = null;
    type2: number = null;
    type3: number = null;
    type4: number = null;
    id_empresa: number = null;

}
export class UsersEmpresasGranjasModel {
    id: number = null;
    user_id: number = null;
    fk_granja_id: number = null;
    fk_empresa_id: number = null;
    created_at: number = null;
    updated_at: number = null;

}
export class VariablesModel {
    variable_id: number = null;
    fk_animal_id: number = null;
    variables_macro: string = null;
    variable_nombre: string = null;
    variable_descripcion: string = null;
    variable_min: number = null;
    variable_max: number = null;
    index_g: number = null;
    variables_apoyo_visual: string = null;
    ir_operador: string = null;
    calificacion_operador: string = null;
    ir_tolerancia: number = null;
    calificacion_tolerancia: number = null;
    variable_max_prog: number = null;
    calificacion_texto_pos: string = null;
    calificacion_texto_neg: string = null;
    operacion_resultado: string = null;
    con: number = null;

}
export class VariablesGrupoVariablesModel {
    fk_variable_id: number = null;
    fk_animal_id: number = null;
    fk_grupo_variable_id: number = null;

}
export class VariablesIpigModel {
    variable_id: number = null;
    tipo_variable: string = null;
    nombre: string = null;

}
export class ViewEstudiosIpigModel {
    estudio_ipig_id: number = null;
    fk_granja_id: number = null;
    granja_nombre: string = null;
    fk_empresa_id: number = null;
    nombre_empresa: string = null;
    fk_user_id: number = null;
    nombre: string = null;
    tipo_cria: number = null;
    tipo_cria_dias: number = null;
    tipo_levante: number = null;
    tipo_levante_dias: number = null;
    tipo_levante_alimento: string = null;
    tipo_ceba: number = null;
    tipo_ceba_dias: number = null;
    tipo_ceba_alimento: string = null;
    resultado: number = null;
    resultado_fecha: string = null;

}
