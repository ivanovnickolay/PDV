/**
 * Класс реализации запросов к РПН через Аякс
 */
class ajaxSearchReestrForm {
    /**
     *
     * @param dataSearchForm сериализованный данные формы для передачи в запрсо
     */
    constructor (dataSearchForm){
        this.dataForm = dataSearchForm;
    }

   workAlax() {
       $.ajax({
           url: '/searchReestr',
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
               $('#table_ajax').dataTable(  {
                   fixedHeader: {
                       header: true,

                   },
                   "language": {
                       "processing": "Подождите...",
                       "search": "Поиск:",
                       "lengthMenu": "Показать _MENU_ записей",
                       "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                       "infoEmpty": "Записи с 0 до 0 из 0 записей",
                       "infoFiltered": "(отфильтровано из _MAX_ записей)",
                       "infoPostFix": "",
                       "loadingRecords": "Загрузка записей...",
                       "zeroRecords": "Записи отсутствуют.",
                       "emptyTable": "В таблице отсутствуют данные",
                       "paginate": {
                           "first": "Первая",
                           "previous": "Предыдущая",
                           "next": "Следующая",
                           "last": "Последняя"
                       },
                       "aria": {
                           "sortAscending": ": активировать для сортировки столбца по возрастанию",
                           "sortDescending": ": активировать для сортировки столбца по убыванию"
                       }
                   }});
           }
       })
   }
}

module.exports = ajaxSearchReestrForm;