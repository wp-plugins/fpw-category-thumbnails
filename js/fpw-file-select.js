function confirmUpdate(){jQuery('#buttonPressed').val('Update');jQuery("form[name='fpw_cat_thmb_form']").submit()}function confirmApply(){msg='This action will add thumbnails based on current settings to <strong>ALL</strong> existing posts / pages.<br />';msg=msg+'"<em>Do not overwrite if post/page has thumbnail assigned already</em>" option will be respected.<br /> <br />';msg=msg+'Are you sure you want to proceed?';jConfirm(msg,'Please Confirm',function(result){if(result){jQuery('#buttonPressed').val('Apply');jQuery("form[name='fpw_cat_thmb_form']").submit()}else{return false}})}function confirmRemove(){msg='This action will <strong>REMOVE</strong> thumbnails from <strong>ALL</strong> existing posts / pages.<br />';msg=msg+'"<em>Do not overwrite if post/page has thumbnail assigned already</em>" option will <strong>NOT</strong> be respected!<br /> <br />';msg=msg+'Are you sure you want to proceed?';jConfirm(msg,'Please Confirm',function(result){if(result){jQuery('#buttonPressed').val('Remove');jQuery("form[name='fpw_cat_thmb_form']").submit()}else{return false}})}jQuery(document).ready(function($){if($('.fpw-fs-button').length){$('.fpw-fs-button').click(function(){$('html').addClass('File');tb_show('Get Image ID','media-upload.php?fpw_fs_field='+$(this).siblings('input.fpw-fs-value').attr('id')+'&type=file&TB_iframe=true');return false});$('.fpw-fs-button:first').parents('form').submit(function(){$('.fpw-fs-remove:checked').each(function(){$(this).siblings('input.fpw-fs-value').val('')})})}if($("body").attr('id')=='media-upload'){var parent_doc,parent_src,parent_src_vars,current_tab;var select_button='<a href="#" class="fpw-fs-insert button-secondary">'+fpw_file_select.text_select_file+'</a>';parent_doc=parent.document;parent_src=parent_doc.getElementById('TB_iframeContent').src;parent_src_vars=fpw_fs_get_url_vars(parent_src);if('fpw_fs_field'in parent_src_vars){current_tab=$('ul#sidemenu a.current').parent('li').attr('id');$('ul#sidemenu li#tab-type_url').remove();$('p.ml-submit').remove();switch(current_tab){case'tab-type':{$('table.describe tbody tr:not(.submit)').remove();$('table.describe tr.submit td.savesend input').remove();$('table.describe tr.submit td.savesend').prepend(select_button);break}case'tab-library':{$('#media-items .media-item a.toggle').remove();$('#media-items .media-item').each(function(){$(this).prepend(select_button)});$('a.fpw-fs-insert').css({'display':'block','float':'right','margin':'7px 20px 0 0'});break}case'tab-nextgen':{$('#media-items .media-item a.toggle').remove();$('#media-items .media-item').each(function(){$(this).prepend(select_button)});$('a.fpw-fs-insert').css({'display':'block','float':'right','margin':'7px 20px 0 0'});break}}$('a.fpw-fs-insert').click(function(){var item_id;if($(this).parent().attr('class')=='savesend'){item_id=$(this).siblings('.del-attachment').attr('id');item_id=item_id.match(/del_attachment_([0-9]+)/);item_id=item_id[1]}else{item_id=$(this).parent().attr('id');item_id=item_id.match(/media\-item\-([0-9]+)/);item_id=item_id[1];if(current_tab=='tab-nextgen'){item_id='ngg-'+item_id}}parent.fpw_fs_select_item(item_id,parent_src_vars['fpw_fs_field']);return false})}}if($('.btn-for-clear').length){$('.btn-for-clear').click(function(){t=this;id=t.id;id=id.slice((id.search(/clear-for-id-/)+13),id.length);jConfirm('Are you sure you want to clear this ID?','Please confirm',function(r){if(r)fpw_fs_select_item(0,'val-for-id-'+id+'-field')});return false})}});function fpw_fs_get_url_vars(s){var vars={};var parts=s.replace(/[?&]+([^=&]+)=([^&]*)/gi,function(m,key,value){vars[key]=value});return vars}function fpw_fs_select_item(item_id,field_id){var field,preview_div,preview_size;field=jQuery('#'+field_id);preview_div=jQuery('#'+field_id+'_preview');preview_size=jQuery('#'+field_id+'_preview-size').val();preview_div.html('').load(fpw_file_select.ajaxurl,{id:item_id,size:preview_size,action:'fpw_fs_get_file'});field.val(item_id);tb_remove();jQuery('html').removeClass('File')}