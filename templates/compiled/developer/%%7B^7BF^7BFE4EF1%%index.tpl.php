<?php /* Smarty version 2.6.19, created on 2010-09-08 16:59:10
         compiled from actions/ActionLogin/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionLogin/index.tpl', 10, false),array('function', 'hook', 'actions/ActionLogin/index.tpl', 13, false),)), $this); ?>
<?php $this->assign('noSidebar', true); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div class="center">
	<?php if ($this->_tpl_vars['bLoginError']): ?>
		<p class="system-messages-error"><?php echo $this->_tpl_vars['aLang']['user_login_bad']; ?>
</p>
	<?php endif; ?>

	<form action="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
" method="POST">
		<h2><?php echo $this->_tpl_vars['aLang']['user_authorization']; ?>
</h2>

		<?php echo smarty_function_hook(array('run' => 'form_login_begin'), $this);?>


		<p><a href="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['user_registration']; ?>
</a><br />
		<a href="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
reminder/"><?php echo $this->_tpl_vars['aLang']['user_password_reminder']; ?>
</a></p>

		<p><label><?php echo $this->_tpl_vars['aLang']['user_login']; ?>
<br /><input type="text" name="login" class="input-200" /></label></p>
		<p><label><?php echo $this->_tpl_vars['aLang']['user_password']; ?>
<br /><input type="password" name="password" class="input-200" /></label></p>
		<p><label><input type="checkbox" name="remember" checked class="checkbox" /><?php echo $this->_tpl_vars['aLang']['user_login_remember']; ?>
</label></p>

		<?php echo smarty_function_hook(array('run' => 'form_login_end'), $this);?>


		<input type="submit" name="submit_login" value="<?php echo $this->_tpl_vars['aLang']['user_login_submit']; ?>
" />
	</form>


	<?php if ($this->_tpl_vars['oConfig']->GetValue('general.reg.invite')): ?>
		<br /><br />
		<form action="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
invite/" method="POST">
			<h2><?php echo $this->_tpl_vars['aLang']['registration_invite']; ?>
</h2>

			<p><label><?php echo $this->_tpl_vars['aLang']['registration_invite_code']; ?>
<br />
			<input type="text" name="invite_code" /></label></p>
			<input type="submit" name="submit_invite" value="<?php echo $this->_tpl_vars['aLang']['registration_invite_check']; ?>
" />
		</form>
	<?php endif; ?>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>