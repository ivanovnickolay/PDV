/**
 * Класс который проверяет правильность заполнения поля ИНН
 * правила валидации
 *  - длина поля от 10 до 12 символов (если заполнено)
 *  - содержит только цифры внутри себя
 */
class validationINN {
    constructor (value){
        this.valueINN = value;
    }

    /**
     * Проверка длины поля
     * @returns {boolean}
     */
    isValidLenghtINN() {
        let resultValidation = false;
            let lenghtINN = this.valueINN.length;
        if (lenghtINN===0) {
            return true;
        }
            if ((10===lenghtINN) || (12===lenghtINN)){
                resultValidation = true
            } else {
                resultValidation = false
            }
    return resultValidation;
    }

    /**
     * Проверка содержимого - должны быть только цифры
     * return (boolean)
     */
    isValidContentINN(){
        let result = false;
        if (this.valueINN.length===0) {
            return true;
        }
        if(this.valueINN.match(/^\d+/))
        {
           result = true;
        }
            return result;
    }

    validINN(){
        if (!this.isValidLenghtINN()){return false}
        if (!this.isValidContentINN()){return false}
        return true;
    }
}
module.exports = validationINN;
//export default validationINN;