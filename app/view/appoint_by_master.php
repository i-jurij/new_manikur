<?php
if (!empty($data['app'])) {
    print '<div class="content app">';
    foreach ($data['app'] as $master => $value) {
        print '<button type="button" class="buttons" id="'.sanitize(translit_ostslav_to_lat($master)).'" onclick="showZ(this.id);">'.$master.'</button>';
    }
    print '</div>';

    foreach ($data['app'] as $mast => $date_arr) {
        print '<div class="zapis_usluga display_none app" id="div_'.sanitize(translit_ostslav_to_lat($mast)).'">';
            print '<div class="back shad rad pad margin_rlb1" >';
                print '<span><b>'.$mast.'</b></span>';
            print '</div>';
            foreach ($date_arr as $date => $data_arr) {
                print ' <div class="back shad rad pad margin_rlb1">
                            <span class="display_inline_block zapis_usluga"><b>'.$date.'</b></span>';
                foreach ($data_arr as $time => $data) {
                    echo '  <div class="back shad rad pad mar display_inline_block">
                                <span>'.$time.'</span><br />
                                <span>'.$data.'</span>
                            </div>';
                }
                print ' </div>';
            }
        print '</div >';
    }
} else {
    print '<div class="content"><p>No data.</p></div>';
}
?>

<script>
function showZ(id){
  $('.app').hide();
  $('#div_'+id).toggle();
}
</script>
