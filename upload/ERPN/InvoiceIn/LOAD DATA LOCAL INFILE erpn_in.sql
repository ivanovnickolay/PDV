LOAD DATA LOCAL INFILE 'D:\\OpenServer525\\domains\\AnalizPDV\\web\\Doc\\InvoiceIn\\template_CSV_In_.csv' 
REPLACE INTO TABLE `AnalizPDV`.`Erpn_In` 
CHARACTER SET cp1251 FIELDS TERMINATED BY ';' 
OPTIONALLY ENCLOSED BY '"' ESCAPED BY '"' LINES TERMINATED BY '\r\n' 
IGNORE 1 
LINES (`num_invoice`, @date_create_invoice, @date_reg_invoice, `type_invoice_full`, 
`edrpou_client`, `inn_client`, `num_branch_client`, `name_client`, @suma_invoice, @pdvinvoice, 
@baza_invoice, `name_vendor`, `num_branch_vendor`, `num_reg_invoice`, `type_invoice`, `num_contract`, 
@date_contract, `type_contract`, `person_create_invoice`, `key_field`,`rke_info`)
SET suma_invoice=IF(@suma_invoice='',0,REPLACE(@suma_invoice,',','.')),
pdvinvoice= IF(@pdvinvoice='',0,REPLACE(@pdvinvoice,',','.')),
baza_invoice=IF(@baza_invoice='',0,REPLACE(@baza_invoice,',','.')),
date_contract=IF(@date_contract='',NULL,STR_TO_DATE(@date_contract, '%d.%m.%Y')),
date_create_invoice=IF(@date_create_invoice='',NULL,STR_TO_DATE(@date_create_invoice, '%d.%m.%Y')),
date_reg_invoice=IF(@date_reg_invoice='',NULL,STR_TO_DATE(@date_reg_invoice, '%d.%m.%Y'))
;
