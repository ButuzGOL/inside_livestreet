<?php /* Smarty version 2.6.19, created on 2010-09-10 14:43:16
         compiled from block.blogs_top.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'block.blogs_top.tpl', 4, false),array('function', 'cfg', 'block.blogs_top.tpl', 5, false),array('modifier', 'escape', 'block.blogs_top.tpl', 4, false),)), $this); ?>
<ul class="list">
	<?php $_from = $this->_tpl_vars['aBlogs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oBlog']):
?>
		<li>
			<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a> 
			<?php if ($this->_tpl_vars['oBlog']->getType() == 'close'): ?><img src="<?php echo smarty_function_cfg(array('name' => 'path.static.skin'), $this);?>
/images/lock.png" alt="[x]" title="<?php echo $this->_tpl_vars['aLang']['clean_blog_closed']; ?>
" /><?php endif; ?>
			<span class="rating"><?php echo $this->_tpl_vars['oBlog']->getRating(); ?>
</span>
		</li>
	<?php endforeach; endif; unset($_from); ?>
</ul>				