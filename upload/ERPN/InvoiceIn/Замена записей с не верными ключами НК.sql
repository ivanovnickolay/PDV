UPDATE erpn_in eo
SET eo.key_field=CONCAT_WS('/',eo.num_invoice, eo.type_invoice_full, DATE_FORMAT(eo.date_create_invoice,"%d-%m-%Y"), eo.inn_client)
WHERE eo.key_field<>CONCAT_WS('/',eo.num_invoice, eo.type_invoice_full, DATE_FORMAT(eo.date_create_invoice,"%d-%m-%Y"), eo.inn_client);