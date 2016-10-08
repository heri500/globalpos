<?php
$view = views_get_view('jurnal_petty_cash_by_date');
    $display_id = 'default';
    $args = array(1);
    $view->set_display($display_id);
    $item = $view->get_item($display_id, 'filter', 'created');
    $item['value'] = array('min'=>'2011-12-01 00:00','max'=>'2011-12-02 23:59'); 
    $view->set_item($display_id, 'filter', 'created', $item);
    $view->set_arguments($args);
    $view->pre_execute();
    $view->execute();
    dpm($view->result);
?>