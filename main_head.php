
<title>MMTC Equipment</title>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<!-- Bootstrap Core CSS -->
<link href="css/bootstrap.min.css" type="text/css" rel="stylesheet" >
<!-- <link href="css/bootstrap-3.3.7/bootstrap.min.css" rel="stylesheet" type="text/css"/> -->

<!-- สำหรับautocomplete ของ add.php -->
<link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css"/>

<!-- datatables -->
<!-- <link href="css/datatables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/> -->

<!-- ทำให้tableใช้ export/print ได้ 
<link href="css/datatables/extensions/Buttons/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/> -->

<!-- ทำให้tableใช้ live search ได้ -->
<link href="datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>

<link href="datatables/datatables.min.css" rel="stylesheet" type="text/css"/>


<link href="favicon.ico" rel="icon">
<link href="css/style.css" rel="stylesheet" type="text/css">     

<!-- Chosen -->
<style>
select.form-control + .chosen-container.chosen-container-single .chosen-single {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,0.075);
    -webkit-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    background-image:none;
}

select.form-control + .chosen-container.chosen-container-single .chosen-single div {
    top:4px;
    color:#000;
}

select.form-control + .chosen-container .chosen-drop {
    background-color: #FFF;
    border: 1px solid #CCC;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 4px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
    margin: 2px 0 0;

}

select.form-control + .chosen-container .chosen-search input[type=text] {
    display: block;
    width: 100%;
    height: 34px;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    background-image:none;
}

select.form-control + .chosen-container .chosen-results {
    margin: 2px 0 0;
    padding: 5px 0;
    font-size: 14px;
    list-style: none;
    background-color: #fff;
    margin-bottom: 5px;
}

select.form-control + .chosen-container .chosen-results li , 
select.form-control + .chosen-container .chosen-results li.active-result {
    display: block;
    padding: 3px 20px;
    clear: both;
    font-weight: normal;
    line-height: 1.428571429;
    color: #333;
    white-space: nowrap;
    background-image:none;
}
select.form-control + .chosen-container .chosen-results li:hover, 
select.form-control + .chosen-container .chosen-results li.active-result:hover,
select.form-control + .chosen-container .chosen-results li.highlighted
{
    color: #FFF;
    text-decoration: none;
    background-color: #428BCA;
    background-image:none;
}

select.form-control + .chosen-container-multi .chosen-choices {
    display: block;
    width: 100%;
    min-height: 34px;
    padding: 6px;
    font-size: 14px;
    line-height: 1.428571429;
    color: #555;
    vertical-align: middle;
    background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
    -webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
    background-image:none;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
    height:auto;
    padding:5px 0;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-choice {

    background-image: none;
    padding: 3px 24px 3px 5px;
    margin: 0 6px 0 0;
    font-size: 14px;
    font-weight: normal;
    line-height: 1.428571429;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid #ccc;
    border-radius: 4px;
    color: #333;
    background-color: #FFF;
    border-color: #CCC;
}

select.form-control + .chosen-container-multi .chosen-choices li.search-choice .search-choice-close {
    top:8px;
    right:6px;
}

select.form-control + .chosen-container-multi.chosen-container-active .chosen-choices,
select.form-control + .chosen-container.chosen-container-single.chosen-container-active .chosen-single,
select.form-control + .chosen-container .chosen-search input[type=text]:focus{
    border-color: #66AFE9;
    outline: 0;
    -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px rgba(102, 175, 233, 0.6);
    box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075),0 0 8px rgba(102, 175, 233, 0.6);
}

select.form-control + .chosen-container-multi .chosen-results li.result-selected{
    display: list-item;
    color: #ccc;
    cursor: default;
    background-color: white;
}
</style>