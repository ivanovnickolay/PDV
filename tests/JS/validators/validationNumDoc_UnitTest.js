/**
 * Тестирование класса validationNumDoc
 *
 * @type {module:assert.internal | ((value: any, message?: string) => void)}
 */

var assert = require('assert');
var numDoc = require('./../../../assets/js/validators/validationNumDoc');

describe('Тестирование значения номера документа', function () {

    it('Пустая строка', function(){
        var obj = new numDoc("");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, true);
    });

    it('Значение 555g', function(){
        var obj = new numDoc("555g");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });

    it('Значение 555/45', function(){
        var obj = new numDoc("555/45");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });

    it('Значение 555///45', function(){
        var obj = new numDoc("555///45");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });

    it('Значение 545//45', function(){
        var obj = new numDoc("555//45");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, true);
    });
    it('Значение 545//045', function(){
        var obj = new numDoc("555//045");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, true);
    });
    it('Значение 545//', function(){
        var obj = new numDoc("555//");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });
    it('Значение 545/', function(){
        var obj = new numDoc("555//");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });
    it('Значение 545///', function(){
        var obj = new numDoc("555//");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });
    it('Значение 545//d', function(){
        var obj = new numDoc("555//d");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });
    it('Значение 5d45//d', function(){
        var obj = new numDoc("5d55//d");
        var isValid = obj.validNumDoc();
        assert.equal(isValid, false);
    });

})