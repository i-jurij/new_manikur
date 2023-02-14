<?php
if (!empty($data['res'])) {
print $data['res'];
include_once APPROOT.DS."view".DS."js_back.html";
} else {
    echo '<article class="main_section_article">
            <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
            <h3>Расценки</h3>
            </div>
            <div class="main_section_article_content"><br />
                <h3>'.$data['page_db_data'][0]['page_title'].'</h3>';
                if (!empty($data['min_price'])) {
                    foreach ($data['min_price'] as $k => $v) {
                        echo '<span>'.$k.' - от ' . $v . ' руб.</span><br />' . PHP_EOL;
                    }
                }
    echo '      <br />
                <a href="'.URLROOT.'/price#'.$data['page_db_data'][0]['page_title'].'" style="text-decoration: underline;">Прайс</a>
            </div>
          </article>';
    if (!empty($data['cat'])) {
        foreach ($data['cat'] as $cat) {
            $img = URLROOT.DS.'public'.DS.'imgs'.DS.$cat['category_img'];
            print ' <article class="main_section_article ">
                        <div class="main_section_article_imgdiv">
                            <img src="'.$img.'" alt="Фото '.$cat['category_name'].'" class="main_section_article_imgdiv_img" />
                        </div>
                        <div class="main_section_article_content">
                            <h3>'.$cat['category_name'].'</h3>';
                            if (!empty($data['serv'])) {
                                foreach ($data['serv'] as $serv) {
                                    if ($serv['category_id'] == $cat['id']) {
                                        print '<span>'.$serv['service_name'].' от '.$serv['price'].' руб.</span><br />';
                                    }
                                }
                            }
            print '     </div>
                    </article>';
        }
    }
        // services articles
        if (!empty($data['serv'])) {
            foreach ($data['serv'] as $serv) {
                $img = URLROOT.DS.'public'.DS.'imgs'.DS.$serv['service_img'];
                if (empty($serv['category_id']) || $serv['category_id'] === '') {
                    print ' <article class="main_section_article ">
                                <div class="main_section_article_imgdiv">
                                    <img src="'.$img.'" alt="Фото '.$serv['service_name'].'" class="main_section_article_imgdiv_img" />
                                </div>
                                <div class="main_section_article_content">
                                    <h3>'.$serv['service_name'].'</h3>
                                    <span>'.$serv['service_descr'].'</span><br />
                                    <span>от '.$serv['price'].' руб.</span>
                                </div>
                            </article>';
                }
            }
        }
include_once APPROOT.DS."view".DS."back_home.html";
}?>
