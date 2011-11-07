<?php /* Smarty version 2.6.19, created on 2010-09-08 16:59:10
         compiled from actions/ActionRegistration/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionRegistration/index.tpl', 6, false),array('function', 'hook', 'actions/ActionRegistration/index.tpl', 9, false),array('function', 'cfg', 'actions/ActionRegistration/index.tpl', 27, false),)), $this); ?>
<?php $this->assign('noSidebar', true); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<div class="center">
	<form action="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
" method="POST">
		<h2><?php echo $this->_tpl_vars['aLang']['registration']; ?>
</h2>

		<?php echo smarty_function_hook(array('run' => 'form_registration_begin'), $this);?>


		<p><label><?php echo $this->_tpl_vars['aLang']['registration_login']; ?>
<br />
		<input type="text" name="login" value="<?php echo $this->_tpl_vars['_aRequest']['login']; ?>
" class="input-wide" /><br />
		<span class="note"><?php echo $this->_tpl_vars['aLang']['registration_login_notice']; ?>
</span></label></p>

		<p><label><?php echo $this->_tpl_vars['aLang']['registration_mail']; ?>
<br />
		<input type="text" name="mail" value="<?php echo $this->_tpl_vars['_aRequest']['mail']; ?>
" class="input-wide" /><br />
		<span class="note"><?php echo $this->_tpl_vars['aLang']['registration_mail_notice']; ?>
</span></label></p>

		<p><label><?php echo $this->_tpl_vars['aLang']['registration_password']; ?>
<br />
		<input type="password" name="password" value="" class="input-wide" /><br />
		<span class="note"><?php echo $this->_tpl_vars['aLang']['registration_password_notice']; ?>
</span></label></p>

		<p><label><?php echo $this->_tpl_vars['aLang']['registration_password_retry']; ?>
<br />
		<input type="password" value="" id="repass" name="password_confirm" class="input-wide" /></label></p>

		<?php echo $this->_tpl_vars['aLang']['registration_captcha']; ?>
<br />
		<img src="<?php echo smarty_function_cfg(array('name' => 'path.root.engine_lib'), $this);?>
/external/kcaptcha/index.php?<?php echo $this->_tpl_vars['_sPhpSessionName']; ?>
=<?php echo $this->_tpl_vars['_sPhpSessionId']; ?>
" onclick="this.src='<?php echo smarty_function_cfg(array('name' => 'path.root.engine_lib'), $this);?>
/external/kcaptcha/index.php?<?php echo $this->_tpl_vars['_sPhpSessionName']; ?>
=<?php echo $this->_tpl_vars['_sPhpSessionId']; ?>
&n='+Math.random();" />

		<p><input type="text" name="captcha" value="" maxlength="3" class="input-100" /></p>

		<?php echo smarty_function_hook(array('run' => 'form_registration_end'), $this);?>


		<input type="submit" name="submit_register" value="<?php echo $this->_tpl_vars['aLang']['registration_submit']; ?>
" />
	</form>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>