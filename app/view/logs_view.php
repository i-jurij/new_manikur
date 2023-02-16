<?php
if (!empty($data['res'])) {
    print ' <div class="content">
                <div style="max-width:70rem;max-height:35rem;overflow:auto;" class="margin_bottom_1rem pad text_left" >'.$data['res'].'</div>
            </div>';
} else {
    if (!empty($data['logs'])) {
        print '<form action="'.URLROOT.'/logs_view/view/" method="post" id="logs_view">';
        foreach ($data['logs'] as $value) {
            $ar = explode('#', $value);
            print ' <label class="display_inline_block back shad rad pad margin_bottom_1rem">
                        <input type="radio" name="log" class="buttons" value="'.$ar[1].'" />
                        <span>'.$ar[0].'</span>
                    </label>';
        }
        print '     <p>
                        <button type="submit" class="buttons">Далее</button>
                    </p>
                </form>';
    } else {
        print '<div class="content"><p>Каталог не существует, к нему нет права доступа или пуст.</p></div>';
    }
}
?>
