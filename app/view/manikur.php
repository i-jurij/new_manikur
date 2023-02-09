<div class="content">
<?php
if (!empty($data['res'])) {
print $data['res'];
include_once APPROOT.DS."view".DS."js_back.html";
} else {
print 'start';
include_once APPROOT.DS."view".DS."back_home.html";
}?>
</div>
