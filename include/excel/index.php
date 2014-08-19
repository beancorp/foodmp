<?php
/**
 * ���û�����
 *
 */
//$path = 'WEB-INF';
//set_include_path(get_include_path() . PATH_SEPARATOR . $path);


/**
 * ��ɶ๤��� Excel �ļ������ṩ����
 *
 */
require 'Spreadsheet/Excel/Writer.php'; 
$workbook = new Spreadsheet_Excel_Writer(); // ��ʼ���� 
$workbook->send('multi-worksheet-demo.xls'); // ���� Excel �ļ������� 


$worksheet =& $workbook->addWorksheet('sheet-1'); // ����һ����� sheet-1 
$data = array(
    array('name', 'sex', 'age'),
    array('alex', 'M', '18'),
    array('joe', 'F', '16')
);
for ($row = 0; $row < count($data); $row ++) {
    for ($col = 0; $col < count($data[0]); $col ++) {
		$worksheet->writeString($row, $col, $data[$row][$col]); // �� sheet-1 ��д����� 
    }
}


$worksheet =& $workbook->addWorksheet('sheet-2'); // ����һ����� sheet-2 
$data = array(
    array('name', 'sex', 'age'),
    array('Smith', 'M', '28'),
    array('foo', 'F', '16')
);
for ($row = 0; $row < count($data); $row ++) {
    for ($col = 0; $col < count($data[0]); $col ++) {
        $worksheet->writeString($row, $col, $data[$row][$col]); // �� sheet-2 ��д����� 
    }
}


$workbook->close(); // �������
?>
