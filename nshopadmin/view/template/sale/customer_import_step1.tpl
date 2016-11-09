
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
  <h1 style="background-image: url('view/image/customer.png');"><?php echo $heading_title_step1?></h1>
  <div class="buttons"><a onclick="return validate();" class="button"><span><?php echo $button_import; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table border="0" align="center">
        <tr>
          <td><?php echo $entry_file_source?></td>
          <td rowspan="30" width="10px">&nbsp;</td>
          <td><input type="file" name="file_source" id="file_source" class="edt" value="<?=$file_source?>"></td>
        </tr>
        <tr>
          <td><?php echo $entry_csv_header?></td>
          <td><input type="checkbox" name="use_csv_header" id="use_csv_header" <?=($use_csv_header? 'checked="checked"' : '' )?>/></td>
        </tr>
        <tr>
          <td><?php echo $entry_seperate_char?></td>
          <td><input type="text" name="field_separate_char" id="field_separate_char" class="edt_30"  maxlength="2" value="<?=(""!=$field_separate_char ? htmlspecialchars($field_separate_char) : "")?>"/> <small>(leave empty for auto-detect)</small></td>
        </tr>
        <tr>
          <td><?php echo $entry_enclose_char?></td>
          <td><input type="text" name="field_enclose_char" id="field_enclose_char" class="edt_30"  maxlength="1" value="<?=(""!=$field_enclose_char ? htmlspecialchars($field_enclose_char) : htmlspecialchars("\""))?>"/></td>
        </tr>
        <tr>
          <td><?php echo $entry_escape_char?></td>
          <td><input type="text" name="field_escape_char" id="field_escape_char" class="edt_30"  maxlength="1" value="<?=(""!=$field_escape_char ? htmlspecialchars($field_escape_char) : "\\")?>"/></td>
        </tr>
<?php /*        <tr>
          <td><?php echo $entry_export_table?></td>
          <td>
            <select name="table" id="table" class="edt">
              <option value="0"> -- </option>
            <?
              if(!empty($arTables))
                foreach($arTables as $table):
               ?>
              <option value="<?=$table?>"<?=($table == $selectedTable ? 'selected="selected"' : '')?>><?=$table?></option>
            <? endforeach;?>
            </select>
          </td>
        </tr> */ ?>
        <tr>
          <td><?php echo $entry_encoding?></td>
          <td>
            <select name="encoding" id="encoding" class="edt">
            <?
              if(!empty($arEncodings))
                foreach($arEncodings as $charset=>$description):
            ?>
              <option value="<?=$charset?>"<?=($charset == $encoding ? 'selected="selected"' : '')?>><?=$description?></option>
            <? endforeach;?>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
      </table>
    </form>
  </div>
</div>

<script type="text/javascript">
    function validate(){
        var s = document.getElementById('file_source');
        if(null != s && '' == s.value) {
            alert('Define file name'); 
            s.focus();
            return false;
        }
        var s = document.getElementById('table');
        if(null != s && 0 == s.selectedIndex) {
            alert('Define table name'); 
            s.focus();
            return false;
        }
        $('#form').submit();
    }

</script>