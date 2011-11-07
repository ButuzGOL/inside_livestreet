<?php /* Smarty version 2.6.19, created on 2010-09-10 18:42:41
         compiled from actions/ActionTop/comment.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'actions/ActionTop/comment.tpl', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array('menu' => 'blog')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<h2><?php echo $this->_tpl_vars['aLang']['top_comments']; ?>
</h2>	
			
<ul class="switcher">
	<li <?php if ($this->_tpl_vars['aParams'][0] && $this->_tpl_vars['aParams'][0] == '24h'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'top'), $this);?>
comment/24h/"><?php echo $this->_tpl_vars['aLang']['blog_menu_top_period_24h']; ?>
</a></li>
	<li <?php if ($this->_tpl_vars['aParams'][0] && $this->_tpl_vars['aParams'][0] == '7d'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'top'), $this);?>
comment/7d/"><?php echo $this->_tpl_vars['aLang']['blog_menu_top_period_7d']; ?>
</a></li>
	<li <?php if ($this->_tpl_vars['aParams'][0] && $this->_tpl_vars['aParams'][0] == '30d'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'top'), $this);?>
comment/30d/"><?php echo $this->_tpl_vars['aLang']['blog_menu_top_period_30d']; ?>
</a></li>
	<li <?php if ($this->_tpl_vars['aParams'][0] && $this->_tpl_vars['aParams'][0] == 'all'): ?>class="active"<?php endif; ?>><a href="<?php echo smarty_function_router(array('page' => 'top'), $this);?>
comment/all/"><?php echo $this->_tpl_vars['aLang']['blog_menu_top_period_all']; ?>
</a></li>
</ul>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'comment_list.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>