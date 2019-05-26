/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');
import $ from 'jquery';
import 'bootstrap'; // adds functions to jQuery
global.$ = $;

//global.$ = global.jQuery = $;
//const dt =require( 'datatables.net' )( window, $ );

//require('datatables.net-fixedheader-dt/css/fixedHeader.dataTables.css');
require( 'datatables.net-dt' );
require( 'datatables.net-buttons-dt' );
require( 'datatables.net-buttons/js/buttons.flash.js' );
require( 'datatables.net-buttons/js/buttons.html5.js' );
require( 'datatables.net-fixedcolumns-dt' );
require( 'datatables.net-fixedheader-dt' );
// //require('datatables.net-fixedheader-dt/css/fixedHeader.dataTables.css');
// require( 'datatables.net-dt' );
// require( 'datatables.net-buttons-dt' );
// require( 'datatables.net-buttons/js/buttons.flash.js' );
// require( 'datatables.net-buttons/js/buttons.html5.js' );
// require( 'datatables.net-fixedcolumns-dt' );
// require( 'datatables.net-fixedheader-dt' );

//
// // Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');
//
// global.$ = global.jQuery = $;
// //const dt =require( 'datatables.net' )( window, $ );

console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
