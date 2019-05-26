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
       /**
        * Заполнение таблицы данных новыми значениями
         * @param arrayData
        */
        function getDataToDataTable(arrayData){
                $('.ajaxResult').empty();
                /*
                    заполним таблицы данных данными вызвав статистический метод
                    $.fn.dataTable.tables() и передав массив значений row
                */
                $.fn.dataTable
                    .tables( { visible: true, api: true } ).rows.add(arrayData).draw();
            }

       $("#no_data").remove();
        // очистим уведомления об ошибках перед запросом
       $("#error_inn").remove();
       $("#error_num_doc").remove();
       $("#error_dateCreateDoc").remove();

       $.ajax({
           url: '/searchERPN',
           method: 'post',
           dataType: 'json',
           data: this.dataForm,
           success: function (data) {
           // var tableResult;
           //     tableResult = "";
               var counter = 0;
               var row = [];
               let dt = data.searchData;
               /**
                * Проверим не вернулся ли пустой массив
                */
               if (dt!=undefined){
                   // если не пустой то отображаем значения
                   dt.forEach(function(dtSearch, i, dt) {
                       // let html = "";
                       // html = "<tr>\n" +
                       //     "    <td>"+dtSearch.NumInvoice+"</td>" +
                       //     "    <td>"+new Date(dtSearch.DateCreateInvoice.date).toLocaleDateString('ru-Latn')+"</td>" +
                       //     "    <td>"+dtSearch.TypeInvoiceFull+"</td>" +
                       //     "    <td>"+dtSearch.InnClient+"</td>" +
                       //     "    <td>"+dtSearch.NameClient+"</td>" +
                       //     "    <td>"+dtSearch.SumaInvoice+"</td>" +
                       //     "    <td>"+dtSearch.BazaInvoice+"</td>" +
                       //     "    <td>"+dtSearch.Pdvinvoice+"</td>" +
                       //     "    <td>"+dtSearch.NameVendor+"</td>" +
                       //     "    <td>"+dtSearch.NumBranchVendor+"</td>" +
                       //     "  </tr>";
                       // tableResult = tableResult+html;
                       row[counter] = [];
                       row[counter]["NumInvoice"]=dtSearch.NumInvoice;
                       row[counter]["DateCreateInvoice"]=new Date(dtSearch.DateCreateInvoice.date).toLocaleDateString('ru-Latin');
                       row[counter]["TypeInvoiceFull"]=dtSearch.TypeInvoiceFull;
                       row[counter]["InnClient"]=dtSearch.InnClient;
                       row[counter]["NameClient"]=dtSearch.NameClient;
                       row[counter]["SumaInvoice"]=dtSearch.SumaInvoice;
                       row[counter]["BazaInvoice"]=dtSearch.BazaInvoice;
                       row[counter]["Pdvinvoice"]=dtSearch.Pdvinvoice;
                       row[counter]["NameVendor"]=dtSearch.NameVendor;
                       row[counter]["NumBranchVendor"]=dtSearch.NumBranchVendor;
                       counter++;
                   });
                  getDataToDataTable(row);
               } else{
                   row[counter] = [];
                   row[counter]["NumInvoice"]="";
                   row[counter]["DateCreateInvoice"]="";
                   row[counter]["TypeInvoiceFull"]="";
                   row[counter]["InnClient"]="";
                   row[counter]["NameClient"]="";
                   row[counter]["SumaInvoice"]="";
                   row[counter]["BazaInvoice"]="";
                   row[counter]["Pdvinvoice"]="";
                   row[counter]["NameVendor"]="";
                   row[counter]["NumBranchVendor"]="";
                    getDataToDataTable(row);
                    $(".no_data").after("<p id='no_data' style='color: red'> Данных нет ! </p>");
               }

           },
           error: function (data) {
               // сработает если будет передан ответ с кодом ответа 400
               // в принципе "отсеятся" не верные данные должны на этапе валидации перед отправкой запроса -
               // но если будет подмена запроса то это должно сработать
               let dt = data.error;
                    if(dt.INN !=undefined){
                        $('#search_erpn_iNN').after("<p id='error_inn' style='color: red'> dt.INN </p>");
                    }
                    if(dt.dateCreateDoc!=undefined){
                        $('#search_erpn_dateCreateDoc').after("<p id='error_dateCreateDoc' style='color: red'> dt.dateCreateDoc </p>");
                    }
                       if(dt.numDoc!=undefined){
                           $('#search_erpn_numDoc').after("<p id='error_num_doc' style='color: red'>dt.numDoc </p>");
                       }
           }
       })
   }
}

module.exports = ajaxSearchErpnForm;