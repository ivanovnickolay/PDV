/**
 * Класс реализации запросов к ЕРПН через Аякс
 */
class ajaxSearchErpnForm {
    /**
     *
     * @param dataSearchForm сериализованный данные формы для передачи в запрсо
     */
    constructor (dataSearchForm){
        this.dataForm = dataSearchForm;
    }

   workAlax() {
       $.ajax({
           url: '/search',
           method: 'post',
           dataType: 'json',
           data: this.dataForm,
           success: function (data) {
               var tableResult;
               tableResult = "";
               let dt = data.searchData;
               //$.each(data.searchData,function(key, dtSearch) {
               dt.forEach(function(dtSearch, i, dt) {
                   let row = "";
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
               });
               $('.ajaxResult').html('');
               $('.ajaxResult').append(tableResult);

           }
       })
   }
}

module.exports = ajaxSearchErpnForm;