<?php

namespace Database\Seeders;

use App\Models\PatentRequirement;
use Illuminate\Database\Seeder;

class PatentRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $requirements = [
            [
                'code' => 'CERT_INFORME_PATENTE',
                'name' => 'CERTIFICADO INFORME PARA PATENTE',
                'category' => 'municipal',
                'where_to_obtain' => 'Municipalidad correspondiente',
                'description' => 'Certificado de informaciones previas para patente comercial'
            ],
            [
                'code' => 'CERT_UBICACION',
                'name' => 'CERTIFICADO DE UBICACIÓN',
                'category' => 'municipal',
                'where_to_obtain' => 'Municipalidad correspondiente',
                'description' => 'Certificado que verifica la ubicación del comercio'
            ],
            [
                'code' => 'CERT_NUMERO',
                'name' => 'CERTIFICADO DE NÚMERO',
                'category' => 'municipal',
                'where_to_obtain' => 'Municipalidad correspondiente',
                'description' => 'Certificación del número municipal del inmueble'
            ],
            [
                'code' => 'CAPITAL_INICIAL',
                'name' => 'CAPITAL INICIAL REFRENDADO POR CONTADOR Y TITULAR',
                'category' => 'financiero',
                'where_to_obtain' => 'Contador auditor',
                'description' => 'Declaración de capital inicial refrendada por contador'
            ],
            [
                'code' => 'FORM_SII',
                'name' => 'FORM. DEL S.I.I. POR INICIO ACTIVIDADES, APERTURA SUCURSAL O CAMBIO DOMICILIO',
                'category' => 'legal',
                'where_to_obtain' => 'Servicio de Impuestos Internos (SII)',
                'description' => 'Formulario del SII para inicio de actividades'
            ],
            [
                'code' => 'FOTOCOPIA_CEDULA',
                'name' => 'FOTOCOPIA DE CÉDULA DE IDENTIDAD',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría o Servicio de Registro Civil',
                'description' => 'Fotocopia legible de cédula de identidad por ambos lados'
            ],
            [
                'code' => 'CERT_DOMINIO',
                'name' => 'CERTIFICADO DE TITULO DE DOMINIO VIGENTE (6 MESES DE VIGENCIA)',
                'category' => 'legal',
                'where_to_obtain' => 'Conservador de Bienes Raíces',
                'validity_days' => 180,
                'description' => 'Certificado de dominio con no más de 6 meses de antigüedad'
            ],
            [
                'code' => 'CONTRATO_ARRIENDO',
                'name' => 'CONTRATO DE ARRIENDO',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría',
                'description' => 'Contrato de arriendo del local comercial'
            ],
            [
                'code' => 'CERT_GALERIAS',
                'name' => 'CERTIFICADO DE GALERÍAS O FERIAS EMITIDO POR LA ADMINISTRACIÓN',
                'category' => 'municipal',
                'where_to_obtain' => 'Administración de galerías o ferias',
                'description' => 'Certificado de autorización para comerciar en galería o feria'
            ],
            [
                'code' => 'ESCRITURA_SOCIEDAD',
                'name' => 'ESCRITURA DE LA SOCIEDAD, MODIFICACIONES DE LA SOCIEDAD, FOTOCOPIA LEGALIZADA',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría',
                'description' => 'Escritura de constitución de sociedad y modificaciones'
            ],
            [
                'code' => 'INFORME_SANITARIO',
                'name' => 'INFORME SANITARIO, SEREMI DE SALUD EN MAIPÚ N° 410',
                'category' => 'sanitario',
                'where_to_obtain' => 'SEREMI de Salud Maipú N° 410',
                'obtain_address' => 'Maipú',
                'description' => 'Informe sanitario para establecimientos comerciales'
            ],
            [
                'code' => 'AUT_CONCESIONES_MARITIMAS',
                'name' => 'AUTORIZACIÓN DE CONCESIONES MARÍTIMAS EMITIDO POR GOBERNACIÓN MARÍTIMA',
                'category' => 'transporte',
                'where_to_obtain' => 'Gobernación Marítima',
                'description' => 'Autorización para actividades en zonas marítimas'
            ],
            [
                'code' => 'CALIFICACION_TECNICA',
                'name' => 'CALIFACION TECNICA DE SEREMI DE SALUD EN MAIPU N° 410 (ART 83 DFL 725 COD. SANIT.)',
                'category' => 'sanitario',
                'where_to_obtain' => 'SEREMI de Salud Maipú N° 410',
                'description' => 'Calificación técnica según artículo 83 DFL 725'
            ],
            [
                'code' => 'INFORME_IMPACTO_VIAL',
                'name' => 'V°B° INFORME DE IMPACTO VIAL - SEREMI DE TRANSPORTE (VEHICULOS DE CARGA, BUSES)',
                'category' => 'transporte',
                'where_to_obtain' => 'SEREMI de Transporte',
                'description' => 'Informe de impacto vial para vehículos de carga y buses'
            ],
            [
                'code' => 'RESOLUCION_SANITARIA',
                'name' => 'RESOLUCIÓN SANITARIA - CALIFICACIÓN INDUSTRIAL MAIPÚ Nº 410 OFIC. 205 GIRO "ESTACIONAMIENTO"',
                'category' => 'sanitario',
                'where_to_obtain' => 'SEREMI de Salud Maipú N° 410',
                'description' => 'Resolución sanitaria para estacionamientos'
            ],
            [
                'code' => 'CERT_DISTRIBUCION_CAPITAL',
                'name' => 'CERTIFICADO DE DISTRIBUCIÓN DE CAPITAL EMITIDO POR LA MUNICIPALIDAD DONDE ESTÁ LA CASA MATRIZ',
                'category' => 'municipal',
                'where_to_obtain' => 'Municipalidad de la casa matriz',
                'description' => 'Certificado de distribución de capital'
            ],
            [
                'code' => 'RESOLUCION_MINERO',
                'name' => 'RESOLUCION APROBATORIA DE PROYECTO MINERO SERNAGEOMIN',
                'category' => 'profesional',
                'where_to_obtain' => 'SERNAGEOMIN',
                'description' => 'Resolución aprobatoria de proyecto minero'
            ],
            [
                'code' => 'REGISTRO_CMV',
                'name' => 'REGISTRO EN LA COMISION PARA EL MERCADO FINANCIERO',
                'category' => 'financiero',
                'where_to_obtain' => 'Comisión para el Mercado Financiero',
                'description' => 'Registro en CMV para entidades financieras'
            ],
            [
                'code' => 'OFICIO_CARABINEROS',
                'name' => 'OFICIO REMISOR POR AUTORIZACION DE EMPRESA OS-10 DE CARABINEROS',
                'category' => 'seguridad',
                'where_to_obtain' => 'Carabineros de Chile',
                'description' => 'Oficio de autorización para empresas de seguridad'
            ],
            [
                'code' => 'ACREDITACION_OTEC',
                'name' => 'VB ACREDITACION COMO OTEC',
                'category' => 'educacion',
                'where_to_obtain' => 'Ministerio de Educación',
                'description' => 'Acreditación como Organismo Técnico de Capacitación'
            ],
            [
                'code' => 'SOLICITUD_ALCOHOLES',
                'name' => 'SOLICITUD AL SR. ALCALDE PARA OBTENER LA PATENTES DE ALCOHOLES',
                'category' => 'municipal',
                'where_to_obtain' => 'Municipalidad correspondiente',
                'description' => 'Solicitud formal para patente de alcoholes'
            ],
            [
                'code' => 'DECLARACION_ALCOHOLES',
                'name' => 'DECLARACIÓN JURADA NOTARIAL ART. 4TO. LEY DE ALCOHOLES N° 19.925',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría',
                'description' => 'Declaración jurada según ley de alcoholes'
            ],
            [
                'code' => 'CERT_ANTECEDENTES',
                'name' => 'CERTIFICADO DE ANTECEDENTES FINES ESPECIALES',
                'category' => 'seguridad',
                'where_to_obtain' => 'Servicio de Registro Civil',
                'description' => 'Certificado de antecedentes para fines específicos'
            ],
            [
                'code' => 'CERT_SAG',
                'name' => 'CERTIFICADO DEL SERVICIO AGRÍCOLA Y GANADERO SAG, LA RIV IERA N° 445',
                'category' => 'profesional',
                'where_to_obtain' => 'SAG La Rivera N° 445',
                'description' => 'Certificado del Servicio Agrícola y Ganadero'
            ],
            [
                'code' => 'CERT_VINAS',
                'name' => 'CERTIFICADO EMITIDO DIRECTAMENTE POR LAS VIÑAS, PARA LAS PATENTES DE DISTRIBUIDORAS',
                'category' => 'profesional',
                'where_to_obtain' => 'Viñas productoras',
                'description' => 'Certificado de distribuidor autorizado por viñas'
            ],
            [
                'code' => 'CERT_EMISION_ACUSTICA',
                'name' => 'CERTIFICADO DE EMISIÓN ACÚSTICA, EMITIDO POR PROFESIONAL ACÚSTICO PARTICULAR QUE CUENTE CON SONÓMETRO Y CALIBRADOR, CALIBRADOS AL DÍA',
                'category' => 'profesional',
                'where_to_obtain' => 'Profesional acústico certificado',
                'description' => 'Certificado de medición de emisión acústica'
            ],
            [
                'code' => 'SECTOR_TURISMO',
                'name' => 'PARA PATENTE DE TURISMO SOLO SECTORES AUTORIZADO POR PLAN REGULADOR (DOM)',
                'category' => 'municipal',
                'where_to_obtain' => 'Dirección de Obras Municipales',
                'description' => 'Autorización de sector según plan regulador'
            ],
            [
                'code' => 'TITULO_PROFESIONAL',
                'name' => 'TÍTULO PROFESIONAL LEGALIZADO',
                'category' => 'profesional',
                'where_to_obtain' => 'Universidad o Ministerio de Educación',
                'description' => 'Título profesional legalizado ante notario'
            ],
            [
                'code' => 'CERT_COMPETENCIA',
                'name' => 'CERTIFICADO DE COMPETENCIA O DE EXPERIENCIA PARA OFICIOS (3 CERTIFICADOS Y CEDULA IDENTIDAD)',
                'category' => 'profesional',
                'where_to_obtain' => 'Instituciones especializadas',
                'description' => 'Certificados que acrediten competencia en el oficio'
            ],
            [
                'code' => 'CONTRATO_ARRIENDO_LEGALIZADO',
                'name' => 'CONTRATO DE ARRIENDO LEGALIZADO, DECLARACION JURADA NOTARIAL POR DOMICILIO',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría',
                'description' => 'Contrato de arriendo con declaración jurada de domicilio'
            ],
            [
                'code' => 'CERT_GALERIA_ADMIN',
                'name' => 'CERTIFICADO DE GALERÍA, FERIA, MERCADO, EMITIDO POR LA ADMINISTRACIÓN',
                'category' => 'municipal',
                'where_to_obtain' => 'Administración del recinto',
                'description' => 'Certificado de autorización del administrador'
            ],
            [
                'code' => 'V_SEREMI_SALUD',
                'name' => 'V° DEL SEREMI DE SALUD ARICA (PROFESIONALES DEL AREA), MAIPÚ Nº 410',
                'category' => 'sanitario',
                'where_to_obtain' => 'SEREMI de Salud',
                'description' => 'Visto bueno de autoridad sanitaria'
            ],
            [
                'code' => 'CERT_RECONOCIMIENTO_TITULO',
                'name' => 'CERTIFICADO DE RECONOCIMIENTO DE TITULO MINISTERIO RELACIONES EXTERIOR',
                'category' => 'profesional',
                'where_to_obtain' => 'Ministerio de Relaciones Exteriores',
                'description' => 'Reconocimiento de título extranjero'
            ],
            [
                'code' => 'REGISTRO_IMPRESORA',
                'name' => 'IMPRENTA REGISTRO EN DIRECCIÓN DE BIBLIOTECA, ARCHIVO Y MUSEOS CLASIFICADOR 1400 STGO',
                'category' => 'profesional',
                'where_to_obtain' => 'Dirección de Biblioteca, Archivo y Museos',
                'description' => 'Registro de impresora clasificador 1400'
            ],
            [
                'code' => 'CERT_COMISION_MIXTA',
                'name' => 'CERTIFICADO COMISIÓN MIXTA - PATENTE EN SECTOR RURAL - DOM (SAG-MINVU)',
                'category' => 'profesional',
                'where_to_obtain' => 'Comisión Mixta SAG-MINVU',
                'description' => 'Certificado para patentes en sector rural'
            ],
            [
                'code' => 'RESOLUCION_COMBUSTIBLES',
                'name' => 'PATENTES VTA. COMBUSTIBLES, RADIO EMISORAS, 1V, RADIO TAXIS RESOLUCIÓN EMITIDO SEC. AZOLA 1510',
                'category' => 'transporte',
                'where_to_obtain' => 'SEC. AZOLA 1510',
                'description' => 'Resolución para venta de combustibles y radios'
            ],
            [
                'code' => 'PATENTE_ANIMALES',
                'name' => 'PATENTE PARA AUMENTO SUPLEMENTOS Y ADITIVOS DE ANIMALES- SAG. LA RIVIERA N° 445',
                'category' => 'profesional',
                'where_to_obtain' => 'SAG La Rivera N° 445',
                'description' => 'Patente para suplementos y aditivos animales'
            ],
            [
                'code' => 'PATENTE_JARDINES',
                'name' => 'PATENTES DE JARDINES INFANTILES RESOLUCIÓN DEL MINISTERIO DE EDUCACIÓN- JUNJI',
                'category' => 'educacion',
                'where_to_obtain' => 'Ministerio de Educación / JUNJI',
                'description' => 'Resolución para jardines infantiles'
            ],
            [
                'code' => 'PATENTE_COLEGIOS',
                'name' => 'PATENTES DE COLEGIOS, RECINTOS EDUCACIONALES RESOLUCION DEL MINISTERIO EDUCACION',
                'category' => 'educacion',
                'where_to_obtain' => 'Ministerio de Educación',
                'description' => 'Resolución para colegios y recintos educativos'
            ],
            [
                'code' => 'PATENTE_ESCUELAS_CONDUCTORES',
                'name' => 'PATENTES DE ESCUELAS DE CONDUCTORES Y REVISION TECNICA- MINISTERIO DE TRANSPORTE',
                'category' => 'transporte',
                'where_to_obtain' => 'Ministerio de Transporte',
                'description' => 'Patente para escuelas de conductores'
            ],
            [
                'code' => 'PATENTE_SEGURIDAD',
                'name' => 'PATENTE DE SEGURIDAD Y VIGILANCIA - CERTIFICACIÓN EMITIDO POR CARABINEROS DE CHILE',
                'category' => 'seguridad',
                'where_to_obtain' => 'Carabineros de Chile',
                'description' => 'Certificación para empresas de seguridad'
            ],
            [
                'code' => 'CERT_CODEUDOR',
                'name' => 'CERTIFICADO DE CO-DEUDOR SOLIDARIO PARA PATENTE QUE COMPARTAN DOMICILIO, ANTE NOTARIO PUBLICO',
                'category' => 'legal',
                'where_to_obtain' => 'Notaría',
                'description' => 'Certificado de co-deudor solidario para domicilio compartido'
            ]
        ];

        foreach ($requirements as $req) {
            PatentRequirement::create(array_merge($req, [
                'created_by' => 1, // Asumimos que el usuario 1 es el admin
            ]));
        }
    }
}
