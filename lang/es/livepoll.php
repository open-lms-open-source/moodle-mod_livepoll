<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin strings are defined here.
 *
 * @package     mod_livepoll
 * @category    string
 * @copyright   Copyright (c) 2019 Open LMS (https://www.openlms.net)
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['livepoll:addinstance'] = 'Agregar encuesta';
$string['livepoll:view'] = 'Ver encuesta';
$string['livepoll:vote'] = 'Votar en encuesta';
$string['livepollfieldset'] = 'Configuración de encuesta en vivo';
$string['livepollname'] = 'Nombre de Encuesta en vivo';
$string['livepollname_help'] = 'Las encuesta en vivo permiten crear en cuesta cuyos resultados aparecen en vivo a todos los participantes.';
$string['livepollsettings'] = 'Configuración';
$string['missingidandcmid'] = 'Falta id o cmid';
$string['modulename'] = 'Encuesta en vivo';
$string['modulename_help'] = 'Contenido asociado a nuevo campo de Encuesta en vivo';
$string['modulenameplural'] = 'Encuestas en vivo';
$string['newlivepoll'] = 'Nueva Encuesta en vivo';
$string['nonewmodules'] = 'No hay nuevas encuestas en vivo';
$string['pluginadministration'] = 'Administración de Encuesta en vivo';
$string['pluginname'] = 'Encuesta en vivo';
$string['privacy:metadata:livepoll_firebase'] = 'La Encuesta en vivo crea hashes que son enviados a firebase basado en el curso y el usuario.';
$string['privacy:metadata:livepoll_firebase:userid'] = 'El userid es usado para generar hash identificador.';
$string['privacy:metadata:livepoll_firebase:votes'] = 'Los votos asociados a una encuesta.';
$string['view'] = 'Ver';
$string['firebaseapikey'] = 'API Key de Firebase';
$string['firebaseapikey_desc'] = 'Puedes obtener tu API key en tu Firebase workspace.';
$string['firebaseprojectid'] = 'Project ID de Firebase';
$string['firebaseprojectid_desc'] = 'Puedes obtener tu Project ID en tu Firebase workspace.';
$string['optionx'] = 'Opción {$a}';
$string['correctoption'] = 'Opción correcta';
$string['livepollfieldset_help'] = 'Por favor agrega los valores de las opciones, A y B son requeridas. Es obligatorio agregar la opción correcta.';
$string['correctoptioninvalid'] = 'La opción correcta seleccionada debe tener un valor.';
$string['yourvote'] = 'Tu voto';
$string['vote'] = 'Votar';
$string['loading'] = 'Cargando';
$string['resultrendering'] = 'Mostrar resultados';
$string['resultrenderinginvalid'] = 'La opción de mostrar resultados debe tener un valor.';
$string['barchart_text_result'] = 'Gráfica de barras y texto';
$string['text_only_result'] = 'Únicamente texto';
$string['piechart_text_result'] = 'Gráfica de pie y texto';
$string['doughnutchart_text_result'] = 'Gráfica de dona y texto';
$string['polarareachart_text_result'] = 'Gráfica de área polar y texto';
$string['radarchart_text_result'] = 'Gráfica de radar y texto';
$string['totalvotes'] = 'Número total de votos: ';

$string['votecontrol'] = 'Control de votos';
$string['control:closevoting'] = 'Cerrar votación';
$string['control:highlightanswer'] = 'Mostrar respuesta';
$string['control:votinghasclosed'] = 'La votación ha sido cerrada por el moderador.';
$string['control:votinghasclosed:close'] = 'Cerrar mensaje';
