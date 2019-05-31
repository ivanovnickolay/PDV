﻿SELECT
  eo.num_invoice, 
  eo.date_create_invoice,
  eo.type_invoice_full, 
  eo.inn_client, 
  eo.name_client, 
  eo.suma_invoice, 
  eo.pdvinvoice, 
  eo.baza_invoice,
  eo.name_vendor, 
  eo.num_branch_vendor, 
  eo.key_field, 
  eo.month_create_invoice, 
  eo.year_create_invoice
FROM erpn_out eo
WHERE 
     (eo.month_create_invoice = :m
     AND eo.year_create_invoice = :y
     AND eo.type_invoice_full="ПНЕ")
  OR
    (eo.month_create_invoice = :m
    AND eo.year_create_invoice = :y
    AND eo.type_invoice_full="РКЕ"
    AND eo.date_reg_invoice BETWEEN CAST(:dateBeginRKE AS DATE)  AND CAST(:dateEndRKE AS DATE))
  OR
    (eo.month_create_invoice = :m_minusOne
    AND eo.year_create_invoice = :y_minusOne
    AND eo.type_invoice_full="РКЕ"
    AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusOne AS DATE)  AND CAST(:dataEnd_minusOne AS DATE))
  OR 
   (eo.date_create_invoice BETWEEN CAST(:dataBeginCreate_invoice AS DATE)  AND CAST(:dataEndCreate_invoice AS DATE)
		        AND eo.type_invoice_full='РКЕ'
		        AND eo.date_reg_invoice BETWEEN CAST(:dataBegin_minusTwo AS DATE)  AND CAST(:dataEnd_minusTwo AS DATE));


SELECT
  num_invoice, 
  date_create_invoice,
  type_invoice_full,
  inn_client, 
  name_client, 
  zag_summ,
  (baza_20+baza_7+baza_0+baza_zvil+baza_ne_obj+baza_za_mezhi_tovar+baza_za_mezhi_poslug) AS baza,
  (pdv_20+pdv_7) AS pdv,
  key_field
  FROM `reestrbranch_out`
  WHERE month = :m
  AND year = :y;


SELECT
  ei.num_invoice, 
  ei.date_create_invoice,
  ei.type_invoice_full, 
  ei.inn_client, 
  ei.name_client, 
  ei.suma_invoice, 
  ei.pdvinvoice, 
  ei.baza_invoice,
  ei.name_vendor, 
  ei.num_branch_vendor, 
  ei.key_field
FROM erpn_in ei
WHERE 
     (MONTH(ei.date_create_invoice) = :m
     AND YEAR(ei.date_create_invoice) = :y);

SELECT
  num_invoice, 
  date_create_invoice,
  type_invoice_full,
  inn_client, 
  name_client, 
  zag_summ,
  (baza_20+baza_7+baza_0+baza_zvil+baza_ne_gos+baza_za_mezhi) AS baza,
  (pdv_20+pdv_7+ pdv_0 + pdv_zvil + pdv_ne_gos + pdv_za_mezhi) AS pdv,
  key_field
  FROM reestrbranch_in
 WHERE month = :m
  AND year = :y;