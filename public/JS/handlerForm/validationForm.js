/**
 * Валидация значений формы
 */


/**
 * Проверка длины строки
 * todo тестировать !!
 * @param INN string значение поля
 * @returns {boolean}
 * true = ошибок нет
 * false = есть ошибки
 *
 */

function isValidLenghtINN(valueINN) {

    resultValidation = true;

    lenghtINN = valueINN.length;
    if(lenghtINN==0){ return true}
    if (valueINN!="") {
        if ((lenghtINN==10) || (lenghtINN==12)){
            resultValidation = true
        } else {resultValidation = false}
    } else resultValidation =  false;
    return resultValidation;
}

/**
 * Вывод ошибки если валидация не пройдена
 * @param INN ссылка на поле
 * @returns {boolean}
 */
function validationINN(valueINN) {
    if (isValidLenghtINN(valueINN.val())==false){
        valueINN.after("<p id='error_inn' style='color: red'> Длина ИНН не верная. ИНН может быть или 10 или 12 символов! </p>");
        //alert("Длина ИНН не может быть более 12 цифр");
        valueINN.focus();
        return false;
   } else {return true}
}
/**
 * Проверка правильности заполнения ИНН
 */
function validINN() {
    var INN = $('#search_erpn_iNN');
    $('#error_inn').remove();
    validationINN(INN);
}

//module.exports.isValid = isValidLenghtINN;
//module.exports.validation = validationINN;