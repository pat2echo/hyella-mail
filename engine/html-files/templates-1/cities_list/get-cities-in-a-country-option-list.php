<?php
    $key = 'cities';
    if( isset($data[$key]) && is_array( $data[$key] ) ){
        foreach( $data[$key] as $id => $val ){
        ?>
        <option value="<?php echo $id; ?>"><?php echo $val['city']; ?></option>
        <?php
        }
        $key = 'specify_option';
        if( isset($data[$key]) && $data[$key] ){
        ?>
        <option value="">--------------</option>
        <option value="specify">Others Please Specify</option>
        <?php
        }
    }else{
    ?>
        <option value="all">All Cities</option>
    <?php
    }
?>