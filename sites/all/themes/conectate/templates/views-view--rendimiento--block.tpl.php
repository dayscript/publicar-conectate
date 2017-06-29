<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28/06/17
 * Time: 11:52 AM
 */

$operaciones = 'Acumulación por operaciones';
$humana = 'Acumulación por gestión humana';
$ventas = 'Acumulación por ventas';
$grupal = 'Cumplimiento Grupal';
$array = array();
$total = 0;
foreach ( $view->result as $key => $item) {
    switch( $item->field_field_indicator[0]['rendered']['#markup'] ){
        case $operaciones:
            $array['operaciones'][] = $item;
            break;
        case $humana:
            $array['humana'][] = $item;
            break;
        case $ventas:
            $array['ventas'][] = $item;
            break;
        case $grupal:
            $array['grupal'][] = $item;
            break;
    }

    $total = $total+$item->field_field_percentage[0]['raw']['value'];
}

?>

<table class="rendimiento" width="100%">
    <thead>
    <tr><th>indicador</th><th width="15%">meta</th><th width="15%">cumplimiento</th><th width="15%">porcentaje</th></tr>
    </thead>
    <tbody>
    <?php if(isset($array['ventas'])):?>
        <tr><td colspan="4" class="group">1. Acumulación por ventas</td></tr>
        <?php foreach($array['ventas'] as $key => $item):?>
            <tr>
                <td> <?php echo $item->node_title ?> </td>
                <td align=""> <?php echo $item->field_field_meta[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_complete[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_percentage[0]['rendered']['#markup'] ?> </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(isset($array['operaciones'])):?>
        <tr><td colspan="4" class="group">2. Acumulación por operaciones</td></tr>
        <?php foreach($array['operaciones'] as $key => $item):?>
            <tr>
                <td> <?php echo $item->node_title ?> </td>
                <td align=""> <?php echo $item->field_field_meta[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_complete[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_percentage[0]['rendered']['#markup'] ?> </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(isset($array['humana'])):?>
        <tr><td colspan="4" class="group">3. Acumulación por gestión humana</td></tr>
        <?php foreach($array['humana'] as $key => $item):?>
            <tr>
                <td> <?php echo $item->node_title ?> </td>
                <td align=""> <?php echo $item->field_field_meta[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_complete[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_percentage[0]['rendered']['#markup'] ?> </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(isset($array['grupal'])):?>
        <tr><td colspan="4" class="group">4. Cumplimiento Grupal</td></tr>
        <?php foreach($array['grupal'] as $key => $item):?>
            <tr>
                <td> <?php echo $item->node_title ?> </td>
                <td align=""> <?php echo $item->field_field_meta[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_complete[0]['rendered']['#markup'] ?> </td>
                <td align=""> <?php echo $item->field_field_percentage[0]['rendered']['#markup'] ?> </td>
            </tr>
        <?php endforeach;?>
    <?php endif;?>
    <tr ><td class="total"> Total </td><td colspan='4' align="" class="total percent"><?php echo number_format($total/count($view->result),2) ?></td></tr>
    </tbody>
</table>