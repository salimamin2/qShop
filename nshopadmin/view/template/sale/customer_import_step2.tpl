
<?php if (isset($error_warning)) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if (isset($success)) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
  <h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title_step2?></h1>
  <div class="buttons">
    <a onclick="submitScheduleImport()" class="button"><span><?php echo $button_schedule_import; ?></span></a>
    <a onclick ="$('#form').submit()" class="button"><span><?php echo $button_import; ?></span></a>
    <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
  </div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" id="form">
    <table border="1" align="center">
    <tr>
      <th><?php echo $csv_table_column ?> 
          <br/>
          <span id="mapping_loader" class="hidden ajax-loader"></span>
          <select name="sel_mapping" id="mapping" onchange="getmapping(this.value)">
              <option value="0">Select saved mapping</option>
               <?php foreach($arMapping as $file):?>
                 <option value="<?php echo $file?>" <?php echo isset($mapping_name)&& $mapping_name==$file ?'selected':'' ?>><?php echo $file?></option>
              <?php endforeach; ?>
          </select> OR
          <input type="button" value="Auto-map" class="auto-map" onclick="automap()"/></th>
      <th><?php echo $csv_header ?></th>
      <th><?php echo $csv_example ?></th>
    </tr>
    <?php $k=0; foreach($arHeaders as $i=>$header) :?>
    <tr>
      <td><?php echo sprintf( $fields_select, strtolower(htmlspecialchars( $header )), $k )?></td>
      <td class="header" num="<?=$k++?>"><?php echo htmlspecialchars( strtolower( trim( $header )))?></td>
      <td><i><?php echo htmlspecialchars( $arExamples[$i] )?></i></td>
    </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="3">
            Mapping name: <span class="red hidden">*Requied</span><input type="text" id="mapping_name" name="mapping_name" value=""/>
            <input type="button" value="Save" class="save-map" onclick="savemap()"/>
        </td>
    </tr>
  </table>
  <br/>
    </form>
  </div>
</div>

<script type="text/javascript">
function automap() {
    $('td.header').each( function() {
        var num = $(this).attr('num');
        $('#select_' + num).val( $(this).html().toLowerCase() );
    } );
}
function savemap()
{
    if($('#mapping_name').val()==""){
       $('.hidden').css('display','inline');
       return false;
    }
    if($('#select_0').val()==undefined){
        return false;
    }
    $('#form').attr('action','sale/customer_import/save_mapping');
    $('#form').submit();
}
var priorMappingName;
function getmapping(value)
{
    if(value==0){
        $('#mapping').val(priorMappingName);
        return false;
    }
    priorMappingName=value;
    $.ajax(
        {
            type: 'post',
            url: 'sale/customer_import/callback_getmapping',
            dataType: 'json',
            data: 'mapping_name='+value,
            beforeSend: function(request){
                $('#mapping_loader').css('display','inline');
            },
            complete: function(request){
                $('#mapping_loader').css('display','none');
            },
            success: function (result) {
                var mapping = result;//eval('('+result+')');
                $('td.header').each(function(){
                    var num = $(this).attr('num');
                    col = $(this).html();
                    if(mapping[col]!=undefined){
                        $('#select_' + num).val(mapping[col]);
                    }
                })
            }
        });
}
function submitScheduleImport(){
    if($('#mapping').val()==0){
       showAlert('Cannot Proceed: Without saved mapping, import can\'t be scheduled.');
       return false;
    }
    $('#form').attr('action','sale/customer_import/schedule');
    $('#form').submit();
}
</script>