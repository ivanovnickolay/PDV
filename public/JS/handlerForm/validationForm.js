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
class validationForm {
     constructor (value){
         this.valueINN = value;
     }

    /**
     * Проверка длины поля
     * @returns {boolean}
     */
    isValidLenghtINN() {
        let resultValidation = true;
        let lenghtINN = this.valueINN.length;
        if(0===this.valueINN){ return true}
        if (lenghtINN!=0) {
            if ((10===lenghtINN) || (12===lenghtINN)){
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
    // validationINN() {
    //     if (isValidLenghtINN(this.valueINN.val())==false){
    //         valueINN.after("<p id='error_inn' style='color: red'> Длина ИНН не верная. ИНН может быть или 10 или 12 символов! </p>");
    //         //alert("Длина ИНН не может быть более 12 цифр");
    //         valueINN.focus();
    //         return false;
    //     } else {return true}
    // }
   /**
    * Проверка правильности заполнения ИНН
    */

}

function validINN() {
    var INN = $('#search_erpn_iNN');
    $('#error_inn').remove();
    valOdj = new validationForm(INN.val())
    if (valOdj.isValidLenghtINN()==false){
        INN.after("<p id='error_inn' style='color: red'> Длина ИНН не верная. ИНН может быть или 10 или 12 символов! </p>");
        //alert("Длина ИНН не может быть более 12 цифр");
        INN.focus();
        return false;
    } else {return true}
}
