/**
 *  организация проверки данных формы
 *  отправка AJAX запроса
 *  обработка и вывод ответа
 */
var validINN = require('../validators/validationINN');
var validNumDoc = require('../validators/validationNumDoc');
var AjaxSearch = require('../Ajax/ajaxSearchReestrForm');
var dt = require( 'datatables.net' );

// после загрузки DOM настраиваем поля
function init() {
    initRangeDate();
    notSubmitFormFromEnter();
    inputOnlyInteget();
}

// приводим значения месяца и года к правильным значениям - двухзначным
function getValidMonthAndDay(value) {
    if (value<10){
        return "0"+value;
    }else {
        return value;
    }
}

/**
 * При получении полем ввода search_reestr_dateCreateDoc фокуса установим границы ввода даты
 * начало и конец месяца введеного в #search_reestr_monthCreate и года введенного в #search_erpn_yearCreate
 */
function initRangeDate() {

    var month  = $('#search_reestr_monthCreate').val();
    var year  = $('#search_reestr_yearCreate').val();
    var dateCreate = $('#search_reestr_dateCreateDoc');
     dateCreate.attr('type','date');
    // установим допустимые границы ввода данных
    var beginData = new Date(year,month-1,1);
    var strBeginDate = beginData.getFullYear()+"-"+ getValidMonthAndDay(beginData.getMonth()+1)+"-"+getValidMonthAndDay(beginData.getDate());
    dateCreate.attr('min',strBeginDate);
    var endData = new Date(year,month,0);
    var strEndDate = endData.getFullYear()+"-"+ getValidMonthAndDay(endData.getMonth()+1)+"-"+getValidMonthAndDay(endData.getDate());
    dateCreate.attr('max',strEndDate);
    // установим начальное значение - первый день месяца
    dateCreate.attr('value',strBeginDate);
}

// блокируем ввод в строку ИНН любых символов кроме цифр
function inputOnlyInteget() {
    $("#search_reestr_iNN").keyup(function () {
        this.value.replace(/[^0-9]/g,'')
    })
}

/**
 * Блокирование отправки формы после нажатия Энтер
 * link https://stackoverflow.com/questions/699065/submitting-a-form-on-enter-with-jquery
 */
function notSubmitFormFromEnter() {
    $(':input').keydown(function (e) {
        if (e.which == '13') {
            $('form#search_reestr').submit();
            e.preventDefault();
        }
    });
}

/**
 * Обработка данных формы
 * @param event
 */
function handlerDataForm(event) {
    event.preventDefault();
    $("#error_inn").remove();
    $("#error_num_doc").remove();
    let INN = new validINN($('#search_reestr_iNN'));
    /**
     * Валидация данных формы
     * @returns {boolean}
     */
 function validator() {
     var returnResult;
     returnResult = true;
     let INN = new validINN($('#search_reestr_iNN').val());
     if(!INN.validINN()){
         $('#search_reestr_iNN').after("<p id='error_inn' style='color: red'> Длина ИНН не верная. ИНН может быть или 10 или 12 символов! </p>");
         returnResult = false;
     }
     var ND = new validNumDoc($("#search_reestr_numDoc").val());
     if (!ND.validNumDoc()){
         $('#search_reestr_numDoc').after("<p id='error_num_doc' style='color: red'> Не верный номер документа. Номер документа может содержать только цифры и //</p>");
         returnResult = false;
     }
     return returnResult;
 }
 if (validator()){
        var Ajax = new AjaxSearch($("form").serialize());
         Ajax.workAlax();
         //Ajax.successAjax(res);
 }

}
// централизация назначений всех событий документа
function addEventToThisDocument() {
    $("#search_reestr_dateCreateDoc").focus(initRangeDate);
    $("form").submit(handlerDataForm(event));
}

$(document).ready(init());

$("#search_reestr_dateCreateDoc").focus(initRangeDate);
//$("#search_erpn_iNN").change(validINN)
//$("#search_erpn_dateCreateDoc").click();
$("form").submit(function (event){handlerDataForm(event)});
//addEventToThisDocument();

