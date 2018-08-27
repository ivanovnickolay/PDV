/**
 * модульный тест вспомогательных фукций валидации форм
 * @type {ok}
 */

var assert = require('assert');

var INN =  require('../../../public/JS/handlerForm/validationForm.js');

describe('Тестирование проверки длины строки для проверки ввода ИНН', function () {
    it('длина строки меньше 10 символов', function(){
        var isValid = INN.isValid("12345678")
        assert.equal(isValid, false);
    });
    it('длина строки больше 12 символов', function(){
        var isValid = INN.isValid("1234567890123")
        assert.equal(isValid, false);
    });
    it('длина строки равна 10 символам', function(){
        var isValid = INN.isValid("1234567890")
        assert.equal(isValid, true);
    });
    it('длина строки равна 12 символам', function(){
        var isValid = INN.isValid("123456789012")
        assert.equal(isValid, true);
    });
    it('пустая строка', function(){
        var isValid = INN.isValid("")
        assert.equal(isValid, true);
    });
});
