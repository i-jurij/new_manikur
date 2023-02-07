<?php
if (!empty($data['res'])) {
    print ' <div class="content">
                <p>';
    print           $data['res'];
    print '     </p>
            </div>';
} else {
    ?>
<div class="content">
    <p>
        Изменение или внесение контактных данных:<br />
        название: адрес, email, vk, telegram, watsapp, tlf, viber or other:<br />
        значение: значение контакта для перехода к вызову или чату.<br />
        Заполняйте только необходимые поля.
    </p>
</div>
<form action="<?php echo URLROOT.'/contacts/go'; ?>" method="post" class="form-recall" id="contacts">
    <div class="form-recall-main">
        <div class="margin_bottom_1rem">
            <p  class="back shad rad pad margin_bottom_1rem text_center">Изменение контактов</p>
            <div class="">
            <?php
                foreach ($data['cont'] as $value) { 
                    if ($value['contacts_type'] === 'tlf') {
                        print ' <div class="back shad rad pad margin_bottom_1rem display_inline_block">'.$value['contacts_type'].'<br />
                                    <input type="hidden" name="id[]" value="'.$value['contacts_type'].'plusplus'.$value['id'].'" />
                                    <input type="tel" name="contacts[]" class="number" placeholder="'.$value['contacts_data'].'" pattern="(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?" />
                                </div>';
                    } else {
                        print ' <div class="back shad rad pad margin_bottom_1rem display_inline_block">'.$value['contacts_type'].'<br />
                                    <input type="hidden" name="id[]" value="'.$value['contacts_type'].'plusplus'.$value['id'].'" />
                                    <input type="text" name="contacts[]" placeholder="'.$value['contacts_data'].'" maxlength="50" />
                                </div>';
                    }
                }
            ?>
            </div>
               
            <p  class="back shad rad pad margin_bottom_1rem text_center">Добавить контакт</p>
            <div class="">
                <input type="text" placeholder="Название контакта" name="contacts_name" maxlength="100" />
                <input type="text" placeholder="Значение контакта" name="contacts_value" maxlength="100" />
                <div id="error"><small></small></div>
            </div>
        </div>
        <div class="">
            <button class="buttons form-recall-submit" type="submit">Отправить</button>
            <button class="buttons form-recall-reset" type="reset" onclick="Reset()">Очистить</button>
        </div>
        <div class="clear"></div>
    </div>
</form>
<?php
}

?>
