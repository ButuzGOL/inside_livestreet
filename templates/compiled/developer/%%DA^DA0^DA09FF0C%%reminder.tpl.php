<?php /* Smarty version 2.6.19, created on 2010-09-09 19:11:54
         compiled from actions/ActionLogin/reminder.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionLogin/reminder.tpl', 6, false),)), $this); ?>
<?php $this->assign('noSidebar', true); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div class="center">
	<form action="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
reminder/" method="POST">
		<h2><?php echo $this->_tpl_vars['aLang']['password_reminder']; ?>
</h2>

		<p><label for="mail"><?php echo $this->_tpl_vars['aLang']['password_reminder_email']; ?>
<br />
		<input type="text" name="mail" id="name" class="input-200" /></label></p>	

		<input type="submit" name="submit_reminder" value="<?php echo $this->_tpl_vars['aLang']['password_reminder_submit']; ?>
" />
	</form>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>