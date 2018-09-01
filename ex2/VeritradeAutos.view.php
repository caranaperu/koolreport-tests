
<?php
use \koolreport\pivot\widgets\PivotTable;
use \koolreport\pivot\widgets\PivotMatrix;
?>

<link rel="stylesheet" href="../../../../koolreport/clients/bootstrap/css/bootstrap.min.css" />

<style>
    .reppage-container {
        position:absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 21cm;
        height: 29.7cm;
        border-style:outset;
        overflow: auto;
    }

    .koolphp-table table  {
        font-size: 11px;
        max-height: 100%;
    }

    .koolphp-table table>tbody>tr>td {
        line-height: normal;
        border-top: 0px;
        padding: 2px;
    }


    .krPivotMatrix table {
        font-size: 11px;
    }

    .krPivotMatrix td {
        height: 20px;
    }

    .krpmField.krpmRowField.btn , .krpmField.krpmColumnField.btn , .krpmField.krpmDataField.btn
    {
        font-size: 12px;
    }


    .krPivotMatrix table>tbody>tr>td {
        line-height: normal;
        padding: 2px;
    }

</style>

<script>
    $(document).ready(function() {
        $('#ptable').DataTable({
            initComplete: function() {
                this.api().columns().every(function() {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo($(column.footer()).empty())
                        .on('change', function() {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            column
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                    column.data().unique().sort().each(function(d, j) {
                        select.append('<option value="' + d + '">' + d + '</option>')
                    });
                });
            }
        });
    });
</script>
<?php
/*
PivotTable::create(array(
    "dataStore"=>$this->dataStore('Veritrade'),
    'rowDimension'=>'row',
    'columnDimension'=>'column',
    'columnCollapseLevels' => array(0),
    'rowCollapseLevels' => array(0,0),

    #'headerMap' => array(
    #    'CONTADOR - sum' => 'Total Unidades'
    #),
    'headerMap' => function($v, $f) {
        if ($v === 'CONTADOR - sum')
            $v = 'Total Unidades';
        if ($v === 1)
            $v = 'Enero';
        else if ($v === 2)
            $v = 'Febrero';
        else if ($v === 3)
            $v = 'Marzo';
        else if ($v === 4)
            $v = 'Abril';
        else if ($v === 5)
            $v = 'Mayo';
        else if ($v === 6)
            $v = 'Junio';
        else if ($v === 7)
            $v = 'Julio';
        else if ($v === 8)
            $v = 'Agosto';
        else if ($v === 9)
            $v = 'Setiembre';
        else if ($v === 10)
            $v = 'Octubre';
        else if ($v === 11)
            $v = 'Noviembre';
        else if ($v === 12)
            $v = 'Diciembre';
        return $v;
    },
    'dataMap' => function($v, $f) { return $v;},
));
*/
?>

<div class="reppage-container">

<?php
PivotMatrix::create(array(
     'id'=> 'ptable',
    "dataStore"=>$this->dataStore('Veritrade'),
    'rowDimension'=>'row',
    'columnDimension'=>'column',
    'columnCollapseLevels' => array(0),
    'rowCollapseLevels' => array(0,1),
    #'measures'=>array(
    #    'CONTADOR - sum'
    #),
    'paging' => array(
        'size' => 66,
        'maxDisplayedPages' => 5
    ),

    #'headerMap' => array(
    #    'CONTADOR - sum' => 'Total Unidades'
    #),
    'headerMap' => function($v, $f) {
        if ($v === 'CONTADOR - sum')
            $v = 'Total Unidades';
        else if($f == 'MES_REPORTE') {
                $map = array(
                    '1' => 'Ene',
                    '2' => 'Feb',
                    '3' => 'Mar',
                    '4' => 'Abr',
                    '5' => 'May',
                    '6' => 'Jun',
                    '7' => 'Jul',
                    '8' => 'Ago',
                    '9' => 'Set',
                    '10' => 'Oct',
                    '11' => 'Nov',
                    '12' => 'Dic',
                );

                return $map[$v];
        }
        return $v;
    },
    'dataMap' => function($v, $f) { return $v;},
));
?>
</div>