<div class="he_soz_tlf flex">
        <div class="he_soz">
            <?php
                if ( !empty($data['telegram'])) {
                    print '<a href="tg://resolve?domain='.$data['telegram'].'" title="Telegram" class="he_soz-tg" target="_blank" rel="noopener"></a>';
                }
                if ( !empty($data['vk'])) {
                    print '<a href="https://vk.com/'.$data['vk'].'" title="Вконтакте" class="he_soz-vk" target="_blank" rel="noopener"></a>';
                }
            ?>
        </div>

        <div class="he_tlf">
            <?php
                if(!empty($data)) {
                    foreach ($data as $key => $tlf) {
                        if (strpos($key, 'tlf') !== false) {
                            print '<a href="tel:'.$tlf.'">'.$tlf.'</a>';
                        }
                    }
                }
            ?>
        </div>
    </div>

    <div class="he_adres">
        <?php
            if (!empty($data['adres'])) {
                if (!empty($data['map'])) {
                    print '<a class="he_adres_a" href="'.$data['map'].'">'.$data['adres'].'</a>';
                }
                else {
                    print '<span class="he_adres_a">'.$data['adres'].'</span>';
                }
                
            }
        ?>        
    </div>