-- --------------------------------------------------------------------------------
-- Routine DDL
-- Note: comments before and after the routine body will not be stored by the server
-- --------------------------------------------------------------------------------
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `abaptran_data_view`()
BEGIN
-- Generate data for view, view cluster, and update flag

-- Clear exist values
  update abapanalytics.abaptran set calledview = null, calledviewc = null, updateflag = null;

-- Fill new values
  update abapanalytics.abaptran set calledview = left(trim(value1), 30) where upper(trim(key1)) = 'VIEWNAME';
  update abapanalytics.abaptran set calledview = left(trim(value2), 30) where upper(trim(key2)) = 'VIEWNAME';

  update abapanalytics.abaptran set calledviewc = left(trim(value1), 30) where upper(trim(key1)) = 'VCLDIR-VCLNAME';
  update abapanalytics.abaptran set calledviewc = left(trim(value2), 30) where upper(trim(key2)) = 'VCLDIR-VCLNAME';

  update abapanalytics.abaptran set updateflag = left(trim(value1), 1) where upper(trim(key1)) = 'UPDATE';
  update abapanalytics.abaptran set updateflag = left(trim(value2), 1) where upper(trim(key2)) = 'UPDATE';

END