<?php

/**
 * Class searchErpnPageCest
 *
 * Тестирование отображения страницы поиска данных в ЕРПН
 *  - контроль полноты отображения элементов
 *  - контроль установки значений при смене даты
 */
class searchErpnPageCest
{
    public function _before(AcceptanceTester $I)
    {
        $I->amOnPage('/searchERPN');

    }

    // контроль полноты отображения элементов
    public function controlViewPage(AcceptanceTester $I)
    {
        $I->wantToTest('Проверяем правильность отображения страницы и значения всех полей');

        $I->see("Поиск документов в ЕРПН за период");
        $I->see("Месяц создания");
        $I->see("Год создания");
        $I->see("Номер документа");
        $I->see("Дата создания документа");
        $I->see("Тип документа");
        $I->see("ИНН клиента");
        $I->see("Направление поиска");
        $I->see("Результат поиска данных");
        $I->see("Показать");
        $I->see("Поиск:");

        $I->seeElement('input',[
            'type'=>'number',
            'id'=>'search_erpn_iNN',
            'name'=>'search_erpn[iNN]']);
        $I->seeElement('select',[
            'id'=>'search_erpn_monthCreate',
            'name'=>'search_erpn[monthCreate]']);
        $I->seeElement('select',[
            'id'=>'search_erpn_yearCreate',
            'name'=>'search_erpn[yearCreate]',
            'value'=>'2019']);
        $I->seeElement('input',[
            'type'=>'date',
            'id'=>'search_erpn_dateCreateDoc',
            'name'=>'search_erpn[dateCreateDoc]']);
        $I->seeElement('select',[
            'id'=>'search_erpn_typeDoc',
            'name'=>'search_erpn[typeDoc]' ]);
        $I->see('Искать информацию ','#search_erpn_Search');
        $I->seeElement('button',[
            'type'=>'submit',
            'id'=>'search_erpn_Search',
            'name'=>'search_erpn[Search]',
            ]);
        $I->see('Сбросить  ','#search_erpn_Clear');
        $I->seeElement('button',[
            'type'=>'reset',
            'id'=>'search_erpn_Clear',
            'name'=>'search_erpn[Clear]']);

        // значения, которые передают эти поля важно проверять буквально, так как эти значения идут в бекэнд
                $I->seeInSource('<select id="search_erpn_routeSearch" name="search_erpn[routeSearch]"><option value="Обязательства" selected="selected">Обязательства</option><option value="Кредит">Кредит</option></select>');
                $I->seeInSource('<select id="search_erpn_typeDoc" name="search_erpn[typeDoc]"><option value="ПНЕ" selected="selected">Налоговая накладная</option><option value="РКЕ">Расчет корректировки</option></select>');
        // Контроль таблицы отображение результатов поиска
                $I->see('Номер документа','thead tr th');
                $I->see('Дата создания документа','thead tr th');
                $I->see('Тип документа','thead tr th');
                $I->see('ИНН клиента ','thead tr th');
                $I->see('Наименование клиента','thead tr th');
                $I->see('Сумма с ПДВ, грн','thead tr th');
                $I->see('База, грн','thead tr th');
                $I->see('ПДВ, грн','thead tr th');
                $I->see('Наименование поставщика','thead tr th');
                $I->see('Номер филиала поставщика','thead tr th');
    }

    // контроль установки значений при смене даты
    public function controlValigateDateCreateDoc(AcceptanceTester $I){
        $I->wantToTest("Проверяем правильность установки границ даты создания документов при вводе периода поиска документов");

        $I->selectOption('#search_erpn_monthCreate','март');
        $I->selectOption('#search_erpn_yearCreate','2018');
        $I->clickWithLeftButton('#search_erpn_dateCreateDoc');
        $I->seeElement('#search_erpn_dateCreateDoc',[
           'min'=>'2018-03-01',
           'max'=>'2018-03-31']);

        $I->selectOption('#search_erpn_monthCreate','январь');
        $I->selectOption('#search_erpn_yearCreate','2019');
        $I->clickWithLeftButton('#search_erpn_dateCreateDoc');
        $I->seeElement('#search_erpn_dateCreateDoc',[
            'min'=>'2019-01-01',
            'max'=>'2019-01-31']);

        $I->selectOption('#search_erpn_monthCreate','февраль');
        $I->selectOption('#search_erpn_yearCreate','2019');
        $I->clickWithLeftButton('#search_erpn_dateCreateDoc');
        $I->seeElement('#search_erpn_dateCreateDoc',[
            'min'=>'2019-02-01',
            'max'=>'2019-02-28']);
    }

    //todo тесты на ввод не верных значений номера документа и ИНН и контролем ошибок

    public function controlValidateNumDoc(AcceptanceTester $I){
        $I->wantToTest("Проверяем ввод не верных значений номера документа и вывод уведомления с ошибкой ");

        $I->submitForm('#search_erpn',
            [
                'search_erpn[numDoc]'=>'gdlfjld'
            ],
            "search_erpn[Search]");
        $I->see('Не верный номер документа. Номер документа может содержать только цифры и //',"#error_num_doc");
        $I->executeJS(' $("#error_num_doc").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[numDoc]'=>'1111d'
            ],
            "search_erpn[Search]");
        $I->see('Не верный номер документа. Номер документа может содержать только цифры и //',"#error_num_doc");
        $I->executeJS(' $("#error_num_doc").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[numDoc]'=>'111/45'
            ],
            "search_erpn[Search]");
        $I->see('Не верный номер документа. Номер документа может содержать только цифры и //',"#error_num_doc");
        $I->executeJS(' $("#error_num_doc").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[numDoc]'=>'111//hg'
            ],
            "search_erpn[Search]");
        $I->see('Не верный номер документа. Номер документа может содержать только цифры и //',"#error_num_doc");
        $I->executeJS(' $("#error_num_doc").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[numDoc]'=>'gf//111'
            ],
            "search_erpn[Search]");
        $I->see('Не верный номер документа. Номер документа может содержать только цифры и //',"#error_num_doc");
        $I->executeJS(' $("#error_num_doc").remove();');

    }

    public function controlValidateINN(AcceptanceTester $I){
        $I->wantToTest('Проверяем ввод не верных значений ИНН и вывод уведомления с ошибкой ');

        //Полее ввода настроено на ввод только цифр, другие введенные символы не должны отображатся
        $I->fillField('search_erpn[iNN]','gdlfjld');
        $I->dontSee('gdlfjld','search_erpn[iNN]');
        $I->executeJS(' $("#error_inn").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[iNN]'=>'123456789'
            ],
            "search_erpn[Search]");
        $I->see(' Длина ИНН не верная. ИНН может быть или 10 или 12 символов! ',"#error_inn");
        $I->executeJS(' $("#error_inn").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[iNN]'=>'12345678901'
            ],
            "search_erpn[Search]");
        $I->see(' Длина ИНН не верная. ИНН может быть или 10 или 12 символов! ',"#error_inn");
        $I->executeJS(' $("#error_inn").remove();');

        $I->submitForm('#search_erpn',
            [
                'search_erpn[iNN]'=>'1234567890123'
            ],
            "search_erpn[Search]");
        $I->see(' Длина ИНН не верная. ИНН может быть или 10 или 12 символов! ',"#error_inn");
        $I->executeJS(' $("#error_inn").remove();');

    }

}
