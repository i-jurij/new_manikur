<?php

$time = array('09:00', '11:00', '13:00', '15:00', '17:00') ;
$lunch = "12:00";

function date_month($date = null) //$date - YYYY-mm-dd eg 2022-10-26
{
  if ($date === null)
  {
    $startDate = new DateTime();
  }
  else
  {
    $startDate = new DateTime($date);
  }
  $num_month = $startDate->format('m');
  $year = $startDate->format('Y');
  //$startDate->setTimezone(new DateTimeZone('Europe/Moscow'));
  $month = $startDate->format('F');
  $ru_month = en_month_to_rus($month);
  $number = cal_days_in_month(CAL_GREGORIAN, $num_month, $year);
  $list=array();
  for($d=1; $d<=$number; $d++)
  {
    $date = $year.'-'.$num_month.'-'.$d;
    $startDate = new DateTime($date);
    $list[]=en_dayweek_to_rus($startDate->format('D')).'<br />'.$startDate->format('Y-m-d');
  }
  $data = [$year, $num_month, $ru_month, $list];
  return $data;
}
$data = date_month($date);
$cal = array_pop($data);
$back_year = $year - 1;
$back_month = $num_month - 1;
$next_year = $year + 1;
$next_month = $num_month + 1;

if ( $back_month < 1 )
{
  $ryear = $year;
  $lyear = $ryear - 1;
  $back_month = '12';
}
elseif ($next_month > 12)
{
  $lyear = $year;
  $ryear = $lyear + 1;
  $next_month = '01';
}
else
{
  $lyear = $year; $ryear = $year;
}

echo '<p>
        <a href="?id='.$idd.'&md='.$md.'&num_month='.$back_month.'&year='.$lyear.'" class="shad rad pad_tb05_rl1 display_inline_block"> < </a>
        <span class="shad rad pad_tb05_rl1 display_inline_block" style="width:10rem;">'.$data[2].' '.$data[0]. '</span>
        <a href="?id='.$idd.'&md='.$md.'&num_month='.$next_month.'&year='.$ryear.'" class="shad rad pad_tb05_rl1 display_inline_block"> > </a>
      </p>';
echo '<form action="'.URLROOT.'/grafiki/graf/" method="post" id="grafik" name="grafik" class="grafiki_table_wrapper">
        <table class="grafik_table" id="">
          <tr>
          <th></th>';
//output date
foreach ($cal as $value)
{
  $datet = explode('<br />', $value);
  $numday = explode('-', $datet[1]);
  //обозначим классом выходные, которые уже есть в базе
  $dvyh = '';
  if (!empty($vyh)) {
    foreach ($vyh as $va)
    {
        if ($va['den'] == $datet[1] and ($va['vremia'] === '' or empty($va['vremia'])) )
        {
        $dvyh = 'grafik_ch_div';
        }
    }
  }
  echo '<th class="headdate" id="'.$datet[1].'"><div class="'.$dvyh.'">'.$datet[0].'<br />'.$numday[2].'</div></th>';
}
echo "</tr>";
//output times

foreach ($time as $tim)
{
  echo '<tr>
          <th class="headdate">
            <div>'.$tim.'</div>
          </th>
        ';
  foreach ($cal as $val) //output cell tables
  {
    $datett = explode('<br />', $val);
    $tdid = $datett[1].'_'.str_replace(':', '-', $tim);
    //mark rest time
    $tvyh = '';
    if (!empty($vyh)) {
        foreach ($vyh as $va){
            if ($va['den'] == $datett[1] and ($va['vremia'] === '' or empty($va['vremia'])) ) {
                $tvyh = 'grafik_ch_div';
            } elseif ($va['den'] == $datett[1] and $va['vremia'] == $tim) {
                $tvyh = 'grafik_ch_div';
            }
        }
    }
    echo '<td class="headtime" id="'.$tdid.'"><div class="'.$tvyh.'">&emsp;</div></td>';
  }
  echo '</tr>';
}

echo '</table>
    </form>
    <div class="pad">
      <button type="reset" name="reset" class="buttons" form="grafik" />Сбросить</button>
      <button type="submit" name="submit" class="buttons" form="grafik" />Готово</button>
    </div>
    ';
?>

<script type="text/javascript">
$(function() {
  /*
    $('.headdate').on('change', function(){
      let dt = $(this).val();
      if ($(this).prop('checked', true)) {
        $('input[name="daytime['+dt+'][]"]').prop('checked', true);
      }
      else {
        $('input[name="daytime['+dt+'][]"]').prop('checked', false);
      }
    });
  */
///////////////////////////////////////////////
// add del inputs for date
    $('.headdate').on('click', function(){
      let dt = this.id;
      //$(this).children('div').toggleClass('grafik_ch_div');
      //$('[id^='+dt+']').toggleClass('grafik_ch_div');
      let inp = $('#grafik #in'+dt);
      let delinp = $('#grafik #deld'+dt);
      if ($(this).children('div').hasClass('grafik_ch_div'))
      {
        if ( inp.val() )
        {
          inp.remove();
          $(this).children('div').removeClass('grafik_ch_div');
          $('[id^='+dt+']').children('div').removeClass('grafik_ch_div');
        }
        else
        {
          $('#grafik').append('<input type="hidden" name="deldate[]" value="'+dt+'" id="deld'+dt+'" />');
          $(this).children('div').removeClass('grafik_ch_div');
          $('[id^='+dt+']').children('div').removeClass('grafik_ch_div');
        }
      }
      else
      {
        if (delinp.length)
        {
          delinp.remove();
          $(this).children('div').addClass('grafik_ch_div');
          $('[id^='+dt+']').children('div').addClass('grafik_ch_div');
        }
        else
        {
          let dnow = new Date('<?php echo date('Y-m-d'); ?>');
          let rdate = new Date(dt);
          //alert(dnow+'-'+rdate);
          if (dnow.getTime() <= rdate.getTime() )
          {
            $('#grafik').append('<input type="hidden" name="date[]" value="'+dt+'" id="in'+dt+'" />');
            $(this).children('div').addClass('grafik_ch_div');
            $('[id^='+dt+']').children('div').addClass('grafik_ch_div');
          }
          else
          {
            alert('Этот день уже в прошлом.');
          }
        }
      }
    });
    ///////////////////////////////////////////////
    // add del inputs for daytime
        $('.headtime').on('click', function(){
          let tt = this.id;
          let inp = $('#grafik #dt'+tt);
          let delinp = $('#grafik #deldt'+tt);
          if ($(this).children('div').hasClass('grafik_ch_div'))
          {
              if (inp.val())
              {
                inp.remove();
                $(this).children('div').removeClass('grafik_ch_div');
              }
              else
              {
                $('#grafik').append('<input type="text" name="deltime[]" value="'+tt+'" id="deldt'+tt+'" />');
                $(this).children('div').removeClass('grafik_ch_div');
              }
          }
          else
          {
            if ( delinp.length )
            {
              delinp.remove();
              $(this).children('div').addClass('grafik_ch_div');
            }
            else
            {
              let dd = new Date('<?php $dtt = new DateTime(); echo $dtt->format('Y-m-d').'T'.$dtt->format('H:m'); ?>');
              let rr = tt.split('_');
              //let hh = rr[0].split('-');
              let zz = rr[1].replace(/-/g, ':');
              //let rdate = new Date(hh+' '+zz);
              let rdate = new Date(rr[0]+'T'+zz);
              //alert(dd+' '+rdate);
              if (dd.getTime() <= rdate.getTime())
              {
                $('#grafik').append('<input type="hidden" name="daytime[]" value="'+tt+'" id="dt'+tt+'" />');
                $(this).children('div').addClass('grafik_ch_div');
              }
              else
              {
                alert('Это время уже в прошлом.');
              }
            }
          }
        });
////////////////////////////////////
//button reset
  $('button[type="reset"]').on('click',function(){
    $('form#grafik').trigger("reset");
    $('form#grafik > input').remove();
    $('.grafik_table *').removeClass('grafik_ch_div');
    $('button[name="submit"]').prop('disabled',true);
  });
/////////////////////////////////
// button submit
$('button[type="submit"][name="submit"]').prop('disabled', true);
$('.grafik_table').on('click',function() {
  if ($('input').length > 0) {
    //alert($('input').length);
    $('button[type="submit"][name="submit"]').prop('disabled', false);
  }
  else {
    $('button[type="submit"][name="submit"]').prop('disabled', true);
  }
});
//////////////////////////////////

//post form send
  $("form#grafik").submit(function(e){
    $('#grafik').append('<input type="hidden" name="id" value="<?php echo $idd; ?>" />');
    $('#grafik').append('<input type="hidden" name="master_name" value="<?php echo $md; ?>" />');
    /*
    e.preventDefault(e);
    var formdata = $("#grafik :input").serializeArray();
    //formdata = JSON.stringify(formdata);
    //console.log(formdata) ;
    $.ajax({
      type: "POST",
      url: "grafiki",
      data: formdata,
      success: function(response) {
        $('#result').html(response);
          //console.log(response);
      },
      error: function(errResponse) {
          console.log(errResponse);
      }
    });
    */
  });

});
</script>
