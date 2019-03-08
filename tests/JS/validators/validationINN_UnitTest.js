/**
 * Тестирование класса validationINN
 *
 * @type {module:assert.internal | ((value: any, message?: string) => void)}
 */

var assert = require('assert');
var INN = require('./../../../assets/js/validators/validationINN');

describe('Тестирование проверки длины строки для проверки ввода ИНН', function () {

    it('длина строки меньше 10 символов', function(){
        var obj = new INN("12345678");
        var isValid = obj.isValidLenghtINN();
        assert.equal(isValid, false);
    });
    it('длина строки больше 12 символов', function(){
        var obj = new INN("1234567890123");
        var isValid = obj.isValidLenghtINN();
        assert.equal(isValid, false);
    });
    it('длина строки равна 10 символам', function(){
        var obj = new INN("1234567890");
        var isValid = obj.isValidLenghtINN("1234567890");
        assert.equal(isValid, true);
    });
    it('длина строки равна 12 символам', function(){
        var obj = new INN("123456789012");
        var isValid = obj.isValidLenghtINN();
        assert.equal(isValid, true);
    });
    it('пустая строка', function(){
        var obj = new INN("");
        var isValid = obj.isValidLenghtINN();
        assert.equal(isValid, true);
    });
});

describe('Тестирование проверки на содержимое строки ввода ИНН', function () {
    it('строка пустая ', function(){
        var obj = new INN("");
        var isValid = obj.isValidContentINN();
        assert.equal(isValid, true);
    });

    it('строка 0123458 ', function(){
        var obj = new INN("0123458");
        var isValid = obj.isValidContentINN();
        assert.equal(isValid, true);
    });
    it('строка o123458 ', function(){
        var obj = new INN("o123458");
        var isValid = obj.isValidContentINN();
        assert.equal(isValid, false);
    });

    it('строка hglfhg125 ', function(){
        var obj = new INN("hglfhg125");
        var isValid = obj.isValidContentINN();
        assert.equal(isValid, false);
    });

});

describe('Тестирование общей проверки ', function () {

    it('длина строки меньше 10 символов', function(){
        var obj = new INN("12345678");
        var isValid = obj.validINN();
        assert.equal(isValid, false);
    });
    it('длина строки больше 12 символов', function(){
        var obj = new INN("1234567890123");
        var isValid = obj.validINN();
        assert.equal(isValid, false);
    });
    it('длина строки равна 10 символам', function(){
        var obj = new INN("1234567890");
        var isValid = obj.validINN("1234567890");
        assert.equal(isValid, true);
    });
    it('длина строки равна 12 символам', function(){
        var obj = new INN("123456789012");
        var isValid = obj.validINN();
        assert.equal(isValid, true);
    });

    it('строка пустая ', function(){
        var obj = new INN("");
        var isValid = obj.validINN();
        assert.equal(isValid, true);
    });

    it('строка 0123458 ', function(){
        var obj = new INN("0123458");
        var isValid = obj.validINN();
        assert.equal(isValid, false);
    });
    it('строка o123458 ', function(){
        var obj = new INN("o123458");
        var isValid = obj.validINN();
        assert.equal(isValid, false);
    });

    it('строка hglfhg125 ', function(){
        var obj = new INN("hglfhg125");
        var isValid = obj.validINN();
        assert.equal(isValid, false);
    });


});
