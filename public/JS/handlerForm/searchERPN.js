
// централизация назначений всех событий документа
function addEventToThisDocument() {
    $("#search_erpn_dateCreateDoc").focus(initRangeDate)
    $("#search_erpn_iNN").change(validINN)
}

// после загрузки DOM настраиваем поля
function init() {
    var month  = $('#search_erpn_monthCreate').val();
    var year  = $('#search_erpn_yearCreate').val();
    var dateCreate = $('#search_erpn_dateCreateDoc');
    // установим атрибут - вывод даты
    dateCreate.attr('type','date');
    // установим допустимые границы ввода данных
    var beginData = new Date(year,month-1,1);
    var strBeginDate = beginData.getFullYear()+"-"+ getValidMonthAndDay(beginData.getMonth()+1)+"-"+getValidMonthAndDay(beginData.getDate());
    dateCreate.attr('min',strBeginDate);
    var endData = new Date(year,month,0);
    var strEndDate = endData.getFullYear()+"-"+ getValidMonthAndDay(endData.getMonth()+1)+"-"+getValidMonthAndDay(endData.getDate());
    dateCreate.attr('max',strEndDate);
    // установим начальное значение - первый день месяца
    //dateCreate.setAttribute('value',strBeginDate);
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

// действия при получении полем ввода фокуса установим пределы ввода даты
function initRangeDate() {
    var month  = $('#search_erpn_monthCreate').val();
    var year  = $('#search_erpn_yearCreate').val();
    var dateCreate = $('#search_erpn_dateCreateDoc');
    // установим допустимые границы ввода данных
    var beginData = new Date(year,month-1,1);
    var strBeginDate = beginData.getFullYear()+"-"+ getValidMonthAndDay(beginData.getMonth()+1)+"-"+getValidMonthAndDay(beginData.getDate());
    dateCreate.attr('min',strBeginDate);
    var endData = new Date(year,month,0);
    var strEndDate = endData.getFullYear()+"-"+ getValidMonthAndDay(endData.getMonth()+1)+"-"+getValidMonthAndDay(endData.getDate());
    dateCreate.attr('max',strEndDate);
    // установим начальное значение - первый день месяца
    dateCreate.setAttribute('value',strBeginDate);
}

// блокируем ввод в строку ИНН любых символов кроме цифр
function inputOnlyInteget() {
    $("#search_erpn_iNN").keyup(function () {
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
            $('form#search_erpn').submit();
            e.preventDefault();
        }
    });
}
//
$(document).ready(init());

addEventToThisDocument();

/**
 * Проверка данных формы перед отправкой на сервер
 */
function validDataForm() {
    var month  = $('#search_erpn_monthCreate');
    var year  = $('#search_erpn_yearCreate');
    var numDoc = $('#search_erpn_numDoc');
    var DateCreate = $('#search_erpn_dateCreateDoc');
    validINN();
}

