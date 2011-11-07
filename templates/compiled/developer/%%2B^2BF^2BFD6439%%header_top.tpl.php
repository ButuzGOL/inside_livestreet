<?php /* Smarty version 2.6.19, created on 2010-09-08 16:59:10
         compiled from header_top.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'header_top.tpl', 5, false),array('function', 'hook', 'header_top.tpl', 8, false),array('function', 'cfg', 'header_top.tpl', 49, false),)), $this); ?>
<?php if (! $this->_tpl_vars['oUserCurrent']): ?>
	<div class="login-form">
		<a href="#" class="close" onclick="hideLoginForm(); return false;"></a>
		
		<form action="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
" method="POST">
			<h3><?php echo $this->_tpl_vars['aLang']['user_authorization']; ?>
</h3>

			<?php echo smarty_function_hook(array('run' => 'form_login_popup_begin'), $this);?>


			<p><label><?php echo $this->_tpl_vars['aLang']['user_login']; ?>
:<br />
			<input type="text" class="input-text" name="login" id="login-input"/></label></p>
			
			<p><label><?php echo $this->_tpl_vars['aLang']['user_password']; ?>
:<br />
			<input type="password" name="password" class="input-text" /></label></p>
			
			<p><label><input type="checkbox" name="remember" class="checkbox" checked /><?php echo $this->_tpl_vars['aLang']['user_login_remember']; ?>
</label></p>

			<?php echo smarty_function_hook(array('run' => 'form_login_popup_end'), $this);?>


			<input type="submit" name="submit_login" value="<?php echo $this->_tpl_vars['aLang']['user_login_submit']; ?>
" /><br /><br />
			
			<a href="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
reminder/"><?php echo $this->_tpl_vars['aLang']['user_password_reminder']; ?>
</a><br />
			<a href="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['user_registration']; ?>
</a>
		</form>
	</div>
<?php endif; ?>


<div id="header">
	<div class="profile">
		<?php if ($this->_tpl_vars['oUserCurrent']): ?>
			<a href="<?php echo $this->_tpl_vars['oUserCurrent']->getUserWebPath(); ?>
" class="username"><?php echo $this->_tpl_vars['oUserCurrent']->getLogin(); ?>
</a> |
			<a href="<?php echo smarty_function_router(array('page' => 'topic'), $this);?>
add/" class="create"><?php echo $this->_tpl_vars['aLang']['topic_create']; ?>
</a> |
			<?php if ($this->_tpl_vars['iUserCurrentCountTalkNew']): ?>
				<a href="<?php echo smarty_function_router(array('page' => 'talk'), $this);?>
" class="message-new" id="new_messages" title="<?php echo $this->_tpl_vars['aLang']['user_privat_messages_new']; ?>
"><?php echo $this->_tpl_vars['aLang']['user_privat_messages']; ?>
 (<?php echo $this->_tpl_vars['iUserCurrentCountTalkNew']; ?>
)</a>  |
			<?php else: ?>
				<a href="<?php echo smarty_function_router(array('page' => 'talk'), $this);?>
" id="new_messages"><?php echo $this->_tpl_vars['aLang']['user_privat_messages']; ?>
 (<?php echo $this->_tpl_vars['iUserCurrentCountTalkNew']; ?>
)</a> |
			<?php endif; ?>
			<a href="<?php echo smarty_function_router(array('page' => 'settings'), $this);?>
profile/"><?php echo $this->_tpl_vars['aLang']['user_settings']; ?>
</a> |
			<a href="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
exit/?security_ls_key=<?php echo $this->_tpl_vars['LIVESTREET_SECURITY_KEY']; ?>
"><?php echo $this->_tpl_vars['aLang']['exit']; ?>
</a>
			<?php echo smarty_function_hook(array('run' => 'userbar_item'), $this);?>

		<?php else: ?>
			<a href="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
" onclick="showLoginForm(); return false;"><?php echo $this->_tpl_vars['aLang']['user_login_submit']; ?>
</a> |
			<a href="<?php echo smarty_function_router(array('page' => 'registration'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['registration_submit']; ?>
</a>
		<?php endif; ?>
	</div>

	
	<h1><a href="<?php echo smarty_function_cfg(array('name' => 'path.root.web'), $this);?>
">LiveStreet</a></h1>
	
	
	<ul class="pages">
		<li <?php if ($this->_tpl_vars['sMenuHeadItemSelect'] == 'blog'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['clean_posts']; ?>
</a></li>
		<li <?php if ($this->_tpl_vars['sMenuHeadItemSelect'] == 'blogs'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'blogs'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['blogs']; ?>
</a></li>
		<li <?php if ($this->_tpl_vars['sMenuHeadItemSelect'] == 'people'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'people'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['people']; ?>
</a></li>
						
		<?php echo smarty_function_hook(array('run' => 'main_menu'), $this);?>

	</ul>
</div>