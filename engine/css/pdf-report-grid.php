@page { margin-top:0.75cm; margin-bottom:0.75cm; margin-right:0.5cm; margin-left:0.5cm;} body { font-family:calibri, arial; margin:3pt 1pt; padding:0.1cm; }#dynamic thead{text-align:left;}#dynamic thead th{font-size:8.5pt; padding:3pt 1pt;}#dynamic tr{font-size:8pt;padding:3pt 1pt;}#dynamic tr.even{background-color:#fcfcfc;}.clear{clear:both;}


div#letter-footer {
  position: fixed;
  text-align:center;
  width: 100%;
  border: 0px solid #888;
  overflow: hidden;
  padding: 0.1cm;
  bottom: 0cm;
  left: 0cm;
  border-top-width: 1px;
  height: 1cm;
  color:#aaa;
}

body{
	background:url(<?php $pr = get_project_data(); echo $pr["domain_name"]; ?>images/watermark.png) center center no-repeat;
}

#watermark-none #report-authentication-id,
#watermark #report-authentication-id{
	position:absolute;
	top:0;
}

#report-authentication-id{
	color:#BAFDC5;
	font-size:10pt;
	text-align:left;
	line-height:8pt;
	font-style:italic;
}
@media print{
	.no-print{
		display:none;
	}
}

/*Total Row*/
.total-row{
	font-size:0.9em;
	font-weight:bold;
	font-style:italic;
}
/* System Generated Report Grid */
#dynamic table thead th{
	background-color:#f7f7f7;
	border: 1px solid #000;
}
#dynamic table td {
	padding: 1px 2px;
	border: 1px solid #555;
}
#dynamic table tr.even {
	background-color: #f7f7f7;
}

.numeric-data{
	display:block;
	text-align:right;
}
/*Projects Weekly Reports*/
/*PRINT FROM GAS HELIX*/

table.scroll-table td,
table.scroll-table thead th
{
	/*width:120px !important;*/
	min-width:40px !important;
	max-width:70px !important;
}
table.scroll-table tbody tr:first-child td:nth-child(3),
table.scroll-table thead tr:first-child th:nth-child(3)
{
	min-width:180px !important;
}
table.display-no-scroll td,
table.display-no-scroll thead th
{
	/*width:120px !important;*/
	width:90px !important;
}

table.display thead tr:first-child th:nth-child(2),
.dataTables_scrollBody table tbody tr:first-child td:nth-child(2){
	width:45px !important;
	/*max-width:4px !important;*/
}
table.display-no-scroll thead th:nth-child(2),
.dataTables_scrollBody table.display-no-scroll tbody td:nth-child(2){
	width:45px !important;
	max-width:45px !important;
}

table.display td:nth-child(1){
	width:31px !important;
	max-width:35px !important;
}

table.display tr.even.line-item-exists-in-budget-no td a,
table.display tr.odd.line-item-exists-in-budget-no td a{
    color: #d00;
	text-shadow:none;
}
table.display tr.even.line-item-exists-in-budget-no td,
table.display tr.odd.line-item-exists-in-budget-no td {
	color: #c00;
	text-shadow:none;
}
 
table.display tr.line-items-total-row td{
	font-weight: 600;
	text-transform:uppercase;
}

table.display tr.line-items-space-row td{
	color: transparent !important;
	text-shadow: none !important;
}

.hide-custom-view-select-classes{
		display:none !important;
	}
	
	
	table.main-details-table{
		margin-bottom:25px;
		float:left;
		max-width:44%;
		margin-right:5%;
	}
	table.main-details-table td,
	.report-table-preview table th,
	.report-table-preview table td,
	.report-table-preview-exploration table td,
	.report-table-preview-exploration table th{
		padding:5px 10px;
	}
	
table.main-details-table td.details-section-container-label,
.report-table-preview-exploration table,
.report-table-preview table tr th,
.report-table-preview table{
	font-size:11px;
}
.report-table-preview-exploration table tr th,
.report-table-preview table tr th{
	text-align:center;
	vertical-align:middle;
}
table.main-details-table td.details-section-container-label,
.report-table-preview table tr.total-row td,
.report-table-preview table tr td.company,
.report-table-preview table tr td.operator,
.report-table-preview table tr td.fdollar-column,
.report-table-preview table tr th{
	background-color:#ffc600;
	font-weight:bold;
}
.report-table-preview-exploration table tr td.dollar-column,
.report-table-preview-exploration table tr td.fdollar-column,
.report-table-preview-exploration table tr td.naira-column,
.report-table-preview-exploration table tr td.currency,
.report-table-preview table tr td.currency,
.report-table-preview table tr td.dollar-column,
.report-table-preview table tr td.fdollar-column,
.report-table-preview table tr td.naira-column{
	text-align:right;
}
table.main-details-table td,
.report-table-preview-exploration table tr,
.report-table-preview-exploration table tr th,
.report-table-preview-exploration table tr td,
.report-table-preview table tr,
.report-table-preview table tr th,
.report-table-preview table tr td{
	border:1px solid #000;
}
.report-table-preview-exploration table tr.total-row td,
.report-table-preview table tr.total-row td{
	font-weight:bold;
}

.report-table-preview-exploration table tr.total-row td,
.report-table-preview-exploration table tr td.company,
.report-table-preview-exploration table tr td.operator,
.report-table-preview-exploration table tr td.fdollar-column,
.report-table-preview-exploration table tr th{
	background-color:#eFaaFF;
	font-weight:bold;
}


.report-table-preview table tr.total-row td,
.report-table-preview table tr td.alternate.company,
.report-table-preview table tr td.alternate.operator,
.report-table-preview table tr td.alternate.fdollar-column,
.report-table-preview table tr th.alternate{
	background-color:#C9FB93;
}

.report-table-preview table tr td.standard,
.report-table-preview table tr th.standard{
	background-color:#DBF9BB;
	font-weight:bold;
}

.report-table-preview table tr td.company-1{
	background-color:#FBDE7A;
	font-weight:bold;
}