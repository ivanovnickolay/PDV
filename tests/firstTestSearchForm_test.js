/// C:\OSPanel\domains\PDV\tests>codeceptjs run --steps

Feature('My First Test');

Scenario('test something', (I) => {
    I.amOnPage('/search');
    I.see('Поиск документов в ЕРПН за период');
});
