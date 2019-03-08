/**
 * тестирование обработки ответа
 *
 * @type {"assert".internal | ((value: any, message?: string) => void)}
 */

/**
 * реализация чтения данных их JSON файлов
 * @param fileName
 * @returns {any}
 */
function loadJSONFromFile( fileName) {

// читаем данные из файла
    var fs = require('fs');
// проверяем есть ли файл с данными
    fs.access(fileName, fs.constants.F_OK, (err) => {
        console.log(`${fileName} ${err ? 'does not exist' : 'exists'}`);
    });
// читаем и распознам JSON из файла
//link http://qaru.site/questions/51675/how-to-read-an-external-local-json-file-in-javascript
    var dataFromFile = fs.readFileSync(fileName);
    var responseData=JSON.parse(dataFromFile);
    return responseData;
}

var assert = require('assert');
var chai = require('chai').assert;
var Erpn = require('../../../assets/js/Ajax/ajaxSearchErpnForm');

require('../../../public/JS/jquery-3.3.1.min');

//var testAlax = require('../../../assets/js/Ajax/ajaxSearchErpnForm');

var SearchDataIn = __dirname+'\\responseSearchDataIn.json';
var responseIn = loadJSONFromFile(SearchDataIn);

describe('тестирование ajaxSearchErpnForm с данными SearchDataIn',function () {
    // получаем сформированную таблицу
    var testAjax = new Erpn();
    //var htmlTable = testAlax.successAlax(responseIn);
    var htmlTable = testAjax.successAjax(responseIn);

    it('Проверка правильности заполнения шапки таблицы',function () {
        headerTable = testAjax.getHeaderTable();
        chai.exists(headerTable, htmlTable)
    })
    it('Проверка содержимого по первой строке ',function () {
        firstRow = " <td>1</td>    " +
                    "<td>3/23/2017</td>    " +
                    "<td>ПНЕ</td>    " +
                    "<td>345012604630</td>    " +
                    "<td>ТОВАРИСТВО З ОБМЕЖЕНОЮ                       ВІДПОВІДАЛЬНІСТЮ \"КОМПАНІЯ ПРОМІНСТРУМЕНТ\"</td>" +
                    "<td>140272.74</td>    " +
                    "<td>116893.95</td>                                " +
                    "<td>23378.79</td>    " +
                    "<td>ПУБЛІЧНЕ АКЦІОНЕРНЕ ТОВАРИСТВО \"УКРАЇНСЬКА ЗАЛІЗНИЦЯ\" РЕГІОНАЛЬНА ФІЛІЯ                                                  \"ДОНЕЦЬКА ЗАЛІЗНИЦЯ\" СТРУКТУРНИЙ ПІДРОЗДІЛ \"ДОНЕЦЬКИЙ ГОЛОВНИЙ МАТЕРІАЛЬНО-ТЕХНІЧНИЙ СКЛАД\"</td> " +
                    "<td>779</td> ";
        chai.exists(firstRow, htmlTable)
    })
})

var SearchDataOut = __dirname+'\\responseSearchDataOut.json';
var responseOut = loadJSONFromFile(SearchDataOut);

describe('тестирование ajaxSearchErpnForm с данными SearchDataOut',function () {
    // получаем сформированную таблицу
    var testAjax = new Erpn();
    var htmlTable = testAjax.successAjax(responseOut);

    it('Проверка правильности заполнения шапки таблицы',function () {
        headerTable = testAjax.getHeaderTable();
        chai.exists(headerTable, htmlTable)
    })
    it('Проверка содержимого по первой строке ',function () {
        firstRow = " <tr>\n" +
                        "<td>1//571</td>    " +
                        "<td>3/1/2017</td>    " +
                        "<td>ПНЕ</td>    " +
                        "<td>009558518131</td>    " +
                        "<td>ПРИВАТНЕ АКЦІОНЕРНЕ                   ТОВАРИСТВО \"БІЛОВОДСЬКИЙ КОМБІНАТ ХЛІБОПРОДУКТІВ\"</td>" +
                        "<td>2083.96</td>    " +
                        "<td>1736.63</td>    " +
                        "<td>347.33</td>         " +
                        "<td>/філія \"Південна залізниця\" ПАТ \"Укрзалізниця\"/ СП \"Харківський центр професійної освіти\"                            філії \"Південна        залізниця\" ПАТ \"Укрзалізниця\"</td>    " +
                        "<td>571</td>  " +
                    "</tr>";
        chai.exists(firstRow, htmlTable)
    })
})
