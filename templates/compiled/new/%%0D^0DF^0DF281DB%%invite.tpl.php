<?php /* Smarty version 2.6.19, created on 2010-09-08 16:23:08
         compiled from actions/ActionRegistration/invite.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionRegistration/invite.tpl', 4, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.light.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

		<div class="lite-center">
			<form action="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
invite/" method="POST">
				<h3><?php echo $this->_tpl_vars['aLang']['registration_invite']; ?>
</h3>
				<div class="lite-note"><label for="invite_code"><?php echo $this->_tpl_vars['aLang']['registration_invite_code']; ?>
:</label></div>
				<p><input type="text" class="input-text" name="invite_code" id="invite_code"/></p>				
				<input type="submit" name="submit_invite" value="<?php echo $this->_tpl_vars['aLang']['registration_invite_check']; ?>
">
			</form>
		</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.light.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>