//Feature('Visit search erpn form');

Scenario('check Welcome page on site', (I) => {
    I.amOnPage('/search');
    I.see('Поиск документов в ЕРПН за период');
})