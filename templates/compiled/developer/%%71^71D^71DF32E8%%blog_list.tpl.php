<?php /* Smarty version 2.6.19, created on 2010-09-10 18:40:46
         compiled from blog_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'blog_list.tpl', 16, false),array('function', 'cfg', 'blog_list.tpl', 17, false),array('modifier', 'escape', 'blog_list.tpl', 16, false),)), $this); ?>
<table class="table">
	<thead>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['blogs_title']; ?>
</td>
			<?php if ($this->_tpl_vars['oUserCurrent']): ?><td align="center"><?php echo $this->_tpl_vars['aLang']['clean_join_leave']; ?>
</td><?php endif; ?>
			<td align="center"><?php echo $this->_tpl_vars['aLang']['blogs_readers']; ?>
</td>														
			<td align="center"><?php echo $this->_tpl_vars['aLang']['blogs_rating']; ?>
</td>
		</tr>
	</thead>
	
	<tbody>
		<?php $_from = $this->_tpl_vars['aBlogs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oBlog']):
?>
			<?php $this->assign('oUserOwner', $this->_tpl_vars['oBlog']->getOwner()); ?>
			<tr>
				<td>
					<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
					<?php if ($this->_tpl_vars['oBlog']->getType() == 'close'): ?><img src="<?php echo smarty_function_cfg(array('name' => 'path.static.skin'), $this);?>
/images/lock.png" alt="[x]" title="<?php echo $this->_tpl_vars['aLang']['clean_blog_closed']; ?>
" /><?php endif; ?>
				</td>
				<?php if ($this->_tpl_vars['oUserCurrent']): ?>
					<td align="center">
						<?php if ($this->_tpl_vars['oUserCurrent']->getId() != $this->_tpl_vars['oBlog']->getOwnerId() && $this->_tpl_vars['oBlog']->getType() == 'open'): ?>
							<a href="#" onclick="ajaxJoinLeaveBlog(this,<?php echo $this->_tpl_vars['oBlog']->getId(); ?>
); return false;">
								<?php if ($this->_tpl_vars['oBlog']->getUserIsJoin()): ?><?php echo $this->_tpl_vars['aLang']['clean_leave']; ?>
<?php else: ?><?php echo $this->_tpl_vars['aLang']['clean_join']; ?>
<?php endif; ?>
							</a>
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<td align="center" id="blog_user_count_<?php echo $this->_tpl_vars['oBlog']->getId(); ?>
"><?php echo $this->_tpl_vars['oBlog']->getCountUser(); ?>
</td>													
				<td align="center"><strong><?php echo $this->_tpl_vars['oBlog']->getRating(); ?>
</strong></td>
			</tr>
		<?php endforeach; endif; unset($_from); ?>
	</tbody>
</table>