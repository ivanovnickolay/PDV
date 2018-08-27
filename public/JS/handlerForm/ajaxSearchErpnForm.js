function workAlax(dataSearchForm) {
    $.ajax({
        url:'pdv/search',
        method: 'post',
        dataType : 'json',
        data: dataSearchForm,
        success: function (data) {
            successAlax(data)

        }

    })
}

function successAlax(data) {
    var tableResult;
    tableResult = getHeaderTable();
    dt = data.searchData;
    //$.each(data.searchData,function(key, dtSearch) {
    dt.forEach(function(dtSearch, i, dt) {
        row = "";
        row = "<tr>\n" +
                "    <td>"+dtSearch.NumInvoice+"</td>" +
                "    <td>"+new Date(dtSearch.DateCreateInvoice.date).toLocaleDateString('en-US')+"</td>" +
                "    <td>"+dtSearch.TypeInvoiceFull+"</td>" +
                "    <td>"+dtSearch.InnClient+"</td>" +
                "    <td>"+dtSearch.NameClient+"</td>" +
                "    <td>"+dtSearch.SumaInvoice+"</td>" +
                "    <td>"+dtSearch.BazaInvoice+"</td>" +
                "    <td>"+dtSearch.Pdvinvoice+"</td>" +
                "    <td>"+dtSearch.NameVendor+"</td>" +
                "    <td>"+dtSearch.NumBranchVendor+"</td>" +
             "  </tr>";
        tableResult = tableResult+row;
    })
   return tableResult;
}

function getHeaderTable() {
    table  = " <table border=\"1\">" +
        "   <caption>Результат поиска данных </caption>" +
        "   <tr>" +
        "    <th>Номер документа</th>" +
        "    <th>Дата создания документа</th>" +
        "    <th>Тип документа</th>" +
        "    <th>ИНН клиента </th>" +
        "    <th>Наименование клиента</th>" +
        "    <th>Сумма с ПДВ, грн</th>" +
        "    <th>База, грн</th>" +
        "    <th>ПДВ, грн</th>" +
        "    <th>Наименование поставщика</th>" +
        "    <th>Номер филиала поставщика</th>" +
        "   </tr>";
    return table;
}


module.exports.successAlax = successAlax;
module.exports.getHeaderTable = getHeaderTable;