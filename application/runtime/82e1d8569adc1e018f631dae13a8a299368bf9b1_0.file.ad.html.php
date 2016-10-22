<?php
/* Smarty version 3.1.29, created on 2016-08-13 17:30:32
  from "C:\xampp\htdocs\php2\0813\cms\application\views\admin\ad.html" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57aee8b8a02de0_95602781',
  'file_dependency' => 
  array (
    '82e1d8569adc1e018f631dae13a8a299368bf9b1' => 
    array (
      0 => 'C:\\xampp\\htdocs\\php2\\0813\\cms\\application\\views\\admin\\ad.html',
      1 => 1471080629,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57aee8b8a02de0_95602781 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
<link href='public/styles/bootstrap.css' rel="stylesheet">
</head>
<body>
<?php if ($_smarty_tpl->tpl_vars['add']->value) {?>
<!-- 加载编辑器的容器 -->
    
<table class='table table-bordered table-striped table-hover'>
   <tr>
		<td class="text-right">广告类型</td>
		<td>
			<input type='radio' value="1">banner
			<input type='radio' value="2">fullColumn
			<input type='radio' value="3">sideBar
			<input type='radio' value="4">slider
		</td>
	</tr>
	<tr>
		<td class="text-right">广告title</td><td><input type='text' class="form-control"></td>
	</tr>
	<tr>
		<td class="text-right">广告link</td><td><input type='text' class="form-control"></td>
	</tr>
	<tr>
		<td class="text-right">广告thumbnail</td>
		<td><input type='file' class="form-control"></td>
	</tr>
	<tr>
		<td class="text-right">广告描述</td>
		<td width=80<?php echo '%>';?>
	<?php echo '<script'; ?>
 id="container" name="content" type="text/plain">
        
    <?php echo '</script'; ?>
>
    <!-- 配置文件 -->
    <?php echo '<script'; ?>
 type="text/javascript" src="public/ueditor/ueditor.config.js"><?php echo '</script'; ?>
>
    <!-- 编辑器源码文件 -->
    <?php echo '<script'; ?>
 type="text/javascript" src="public/ueditor/ueditor.all.js"><?php echo '</script'; ?>
>
    <!-- 实例化编辑器 -->
    <?php echo '<script'; ?>
 type="text/javascript">
        var ue = UE.getEditor('container');
    <?php echo '</script'; ?>
>
		</td>
	</tr>
</table>
<?php }?>
</body>
</html><?php }
}
