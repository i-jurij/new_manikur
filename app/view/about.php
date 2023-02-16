<?php
if (!empty($data['res'])) {
        foreach ($data['res'] as $value) {
            echo '<article class="main_section_article ">
                <div class="main_section_article_imgdiv">
                    <img src="'.$value['article_image'].'" alt="Фото '.$value['article_title'].'" class="main_section_article_imgdiv_img" />
                </div>
                    <div class="main_section_article_content">
                    <h3>'.$value['article_title'].'</h3>
                    <span>'.$value['article_content'].'</span>
                    </div>
                </article>';
        }
} else {
   print '...';
}
if (!empty($data['masters'])) {
    foreach ($data['masters'] as $master) {

        $img = get_master_photo($master['id']);

        echo '  <article class="main_section_article ">
                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                    <img src="' . $img . '" alt="Фото ' . $master['master_fam'] . '" class="main_section_article_imgdiv_img" />
                    </div>

                    <div class="main_section_article_content">
                        <h3>' . $master['master_name'] . ' ' . $master['master_fam'] . '</h3>
                        <span>
                        ' . $master['spec'] . '
                        </span>
                    </div>
                </article>';
    }
} else {
print '...';
}

include_once APPROOT.DS."view".DS."back_home.html";
?>
