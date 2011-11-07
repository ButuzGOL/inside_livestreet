<?php /* Smarty version 2.6.19, created on 2010-09-10 19:47:39
         compiled from actions/ActionProfile/sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionProfile/sidebar.tpl', 5, false),array('modifier', 'escape', 'actions/ActionProfile/sidebar.tpl', 16, false),)), $this); ?>
<?php if ($this->_tpl_vars['oUserCurrent'] && $this->_tpl_vars['oUserCurrent']->getId() != $this->_tpl_vars['oUserProfile']->getId()): ?>
	<div class="block">				
		<ul>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'actions/ActionProfile/friend_item.tpl', 'smarty_include_vars' => array('oUserFriend' => $this->_tpl_vars['oUserProfile']->getUserFriend())));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<li><a href="<?php echo smarty_function_router(array('page' => 'talk'), $this);?>
add/?talk_users=<?php echo $this->_tpl_vars['oUserProfile']->getLogin(); ?>
"><?php echo $this->_tpl_vars['aLang']['user_write_prvmsg']; ?>
</a></li>						
		</ul>
	</div>
<?php endif; ?>


<div class="block">
	<?php if ($this->_tpl_vars['oUserProfile']->getProfileIcq()): ?>
		<h2><?php echo $this->_tpl_vars['aLang']['profile_social_contacts']; ?>
</h2>
		<ul>
			<?php if ($this->_tpl_vars['oUserProfile']->getProfileIcq()): ?>
				<li>ICQ: <a href="http://www.icq.com/people/about_me.php?uin=<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileIcq())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" target="_blank"><?php echo $this->_tpl_vars['oUserProfile']->getProfileIcq(); ?>
</a></li>
			<?php endif; ?>					
		</ul>
	<?php endif; ?>
	
	<br />
	
	<?php if ($this->_tpl_vars['oUserProfile']->getProfileFoto()): ?>
		<img src="<?php echo $this->_tpl_vars['oUserProfile']->getProfileFoto(); ?>
" alt="photo" />
	<?php endif; ?>
</div>