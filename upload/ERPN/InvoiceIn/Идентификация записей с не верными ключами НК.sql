SELECT 
eo.num_invoice, 
eo.date_create_invoice, 
eo.type_invoice_full,  
eo.inn_client, eo.key_field, 
CONCAT_WS('/',eo.num_invoice, eo.type_invoice_full, DATE_FORMAT(eo.date_create_invoice,"%d-%m-%Y"), eo.inn_client) AS contr 
FROM AnalizPDV.Erpn_in eo WHERE eo.key_field<>CONCAT_WS('/',eo.num_invoice, eo.type_invoice_full, DATE_FORMAT(eo.date_create_invoice,"%d-%m-%Y"), eo.inn_client);
