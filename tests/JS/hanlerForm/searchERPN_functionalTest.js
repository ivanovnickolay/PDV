/**
 * Фукциональные тесты формы поиcка в ЕРПН
 *
 * Работает ТОЛЬКО при
 * web_profiler:
        toolbar: false (!)
 * иначе выдает ошибку JS
 */

const assert  = require('assert');

const Browser = require('zombie');

// We're going to make requests to http://example.com/signup
// Which will be routed to our test server localhost:3000
//Browser.localhost('https://www.google.com.ua/');
Browser.site='http://pdv/';
describe("Проверки открытия страницы с формой поиска в ЕРПН ",function () {
    const browser = new Browser();
    before(function() {
        return browser.visit('/search');
        // ждеем пока откроется страница 10 секунд
        browser.wait({ duration: 10000 });
        done();
    });

    it("проверка заголовка формы поиска", function () {
        browser.assert.success();
        browser.assert.text('div h3','Поиск документов в ЕРПН за период');


    })
    it("проверка наличия формы c правильными реквизитами ", function () {
        browser.assert.attribute('form', 'name', 'search_erpn');
        browser.assert.attribute('form', 'method', 'post');
    })
    it('проверка наличия всех полей формы',function () {
        browser.assert.elements('form input', 3);
        browser.assert.elements('form select', 4);
        browser.assert.elements('form button', 2);
        browser.assert.element('#search_erpn_monthCreate');
        browser.assert.element('#search_erpn_yearCreate');
        browser.assert.element('#search_erpn_numDoc');
        browser.assert.element('#search_erpn_dateCreateDoc');
        browser.assert.element('#search_erpn_typeDoc');
        browser.assert.element('#search_erpn_iNN');
        browser.assert.element('#search_erpn_routeSearch');
        browser.assert.element('#search_erpn_Search');
        browser.assert.element('#search_erpn_Clear');

        //browser.assert.attribute('form select', 'name', 'search_erpn[monthCreate]');

    })
})

