<div class="content"><p style="margin:0; padding:1rem;" id="p_pro">Показать/скрыть справку</p>
    <p class="margin_rlb1 text_left display_none" id="pro"> 
        Здесь можно добавить, удалить пользователя, изменить его логин, пароль или статус.<br />
        К разделу администрирования имеют доступ все пользователи, на которых в таблице "users" базы данных
        есть записи: username, password, status (поля для email и email_status пока не используются).<br />
        Username и статус - длина поля 25 символов (буквы или цифры), password - 120 любых символов.<br />
        Status имеет три категории: admin, moder, user.<br />
        По умолчанию между ними нет различий, их нужно установить в файле app/view/adm.php путем разделения
        массива страниц на части, доступных для разных категорий пользователей. Пример в самом файле.
        Статусы, соответственно, можно еще добавить.
    </p>
</div>

<div class="content margintb1 ">
    <div>
        <?php
            if ( !isset($data['res']) && !isset($data['users_del']) && !isset($data['users_change']) ) 
            {
                print ' <div class="margintb1 ">
                            <a href="'.URLROOT.'/change_pass/add/" class="buttons display_inline_block mar add">Добавить</a>
                            <a href="'.URLROOT.'/change_pass/delete/" class="buttons display_inline_block mar del">Удалить</a>
                            <a href="'.URLROOT.'/change_pass/change/" class="buttons display_inline_block mar change">Изменить</a>
                        </div>';
            }
            //we change the data for the form depending on the received data array
            if (!empty($data['users_del'])) {
                $users = $data['users_del'];
                $check = 'delete[]'; $button = 'Delete'; $form = 'form_'.$button;
            } elseif (!empty($data['users_change'])) {
                $users = $data['users_change'];
                $check = 'change[]'; $button = 'Change'; $form = 'form_'.$button;
            } elseif (!empty($data['users_del']) && !empty($data['users_change'])) {
                $data['res'] = "Error!";
            }
            //output the form
            if ( !empty($users) && is_array($users) && !empty($check) && is_string($check))
            {
                print ' <form action="" method="post" id="'.$form.'" class="pad form_del_ch">
                            <!-- <div style="visibility:hidden; color:red; " id="chk_option_error">Please select at least one user.</div> -->
                            <div class="form-element mar">';
                foreach ($users as $name) {
                    /*
                    if (array_key_exists('username', $name)) {
                        print '     <label class="checkbox-btn">
                                        <input type="checkbox" id ="user_'.$name['username'].'" value="'.$name['username'].'" name="'.$check.'" />
                                        <span>'.$name['username'].'</span>
                                    </label>
                                    ';
                    }
                    */
                    print '     <label class="checkbox-btn">
                                    <input type="checkbox" id ="user_'.$name.'" value="'.$name.'" name="'.$check.'" />
                                    <span>'.$name.'</span>
                                </label>
                                ';
                }
                print '     </div>
                            <div class="form-element mar">
                                <button type="submit" form="'.$form.'" class="buttons" id="del_ch">'.$button.'</button>
                                <button type="reset" form="'.$form.'" class="buttons">Reset</button>
                            </div>
                        </form>';
            }
            $pad = '';
            if (!empty($data['res'])) 
            {
                if (!empty($data['users'])) {
                    $udat = json_encode($data['users']);
                    echo "<script> var data = ". $udat . ";\n</script>";
                }
                $pad = 'pad';
                //for change form after choose users
                if (is_array($data['res'])) {
                    print ' <form action="" method="post" id="db_change_users_data" class="">
                                <div class="mar"><p id="info">Изменяйте данные только в тех полях, которые хотите изменить.</p>';
                    foreach ($data['res'] as $key => $value) {
                        print '     <div class="shad rad display_inline_block">
                                        <div class="table_body">
                                            <label class="table_row ">
                                                <span class="table_cell text_left">Username</span>
                                                <span class="table_cell">
                                                    <input type="text" class="user_name" id ="username'.$key.'" placeholder="'.$key.'" name="change_name['.$key.']" minlength="3" maxlength="25" pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$" />
                                                </span>
                                            </label>
                                            <label class="table_row">
                                                <span class="table_cell text_left">Status</span>
                                                <span class="table_cell">
                                                    <input type="text" class="user_status" id ="userstatus'.$key.'" placeholder="'.$value.'" name="change_status['.$key.']" minlength="3" maxlength="25" pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$" />
                                                </span>
                                            </label>
                                            <label class="table_row">
                                                <span class="table_cell text_left">Password</span>
                                                <span class="table_cell">
                                                    <input type="password" class="user_password" id ="userpass'.$key.'" placeholder="****" name="change_pass['.$key.']" minlength="4" maxlength="120" />
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                ';
                    }
                    print '     </div>
                                <div class="margin_bottom_1rem">
                                    <button type="submit" form="db_change_users_data" class="buttons" id="db_ch_us_data">Save</button>
                                    <button type="reset" form="db_change_users_data" class="buttons">Reset</button>
                                </div>
                            </form>';
                } else {
                    print '<div class="'.$pad.'">'.$data['res'].'</div>';
                }
            }
        ?>
    </div>
</div>
