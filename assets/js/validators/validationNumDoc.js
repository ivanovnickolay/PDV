/**
 * Проверка правильности заполнения номера документа
 */

class validationNumDoc {

    constructor(value) {
        this.numDoc = value;
    }
    /**
     * Проверка значений
     * @returns {boolean}
     */
    validNumDoc() {
        // массив в идеале из двух элементов - номер документа и номер филиала
        var arraySplit;
            // номер первого элемента в строке который содержит "/"
            var firstSlach;
                if (this.numDoc.length === 0) {
                    return true;
                }
        // проверим есть ли в строке "/"
        if (-1 === this.numDoc.toString().indexOf("/")) {
            //если в строке нет - значит нет номера филиала в номере
            // проверяем что бы были только цифры в введенном значении
           if (!this.numDoc.toString().match(/^[0-9]+$/)) {
                  // если строка не содержит только цифры - номер не валидный
                return false;
            } else {
               return true;
           }
        } else {
            // если есть один "/"
             firstSlach = this.numDoc.toString().indexOf("/");

            if ("/" === this.numDoc.toString().charAt(firstSlach + 1)) {
                // если "//"
                if ("/" === this.numDoc.toString().charAt(firstSlach + 2)) {
                    // если "///" - номер не валидный
                    return false;
                }
                // делим строку на две части
                arraySplit = this.numDoc.toString().split("//");
                    // Проверим что бы элементы массива не содержали запрещенных символов
                    if ((!arraySplit[0].match(/^[0-9]+$/)) ||  (!arraySplit[1].match(/^[0-9]+$/))){
                        // если строка не содержит только цифры - номер не валидный
                        return false;
                    } else {
                        return true;
                    }
            }else{
                // в строке только один "/" - номер не валидный
                return false;
            }

            if (!this.numDoc.match(/^[0-9]+$/)) {
                return true;
            }
            return false;
        }
    }
}

module.exports = validationNumDoc;