<?php /* Smarty version 2.6.19, created on 2010-09-10 14:43:16
         compiled from block.blogs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'router', 'block.blogs.tpl', 25, false),)), $this); ?>
<div class="block blogs">
	<h2><?php echo $this->_tpl_vars['aLang']['block_blogs']; ?>
</h2>
	
	<ul class="switcher">
		<li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;"><?php echo $this->_tpl_vars['aLang']['block_blogs_top']; ?>
</a></li>
		<?php if ($this->_tpl_vars['oUserCurrent']): ?>
			<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;"><?php echo $this->_tpl_vars['aLang']['block_blogs_join']; ?>
</a></li>
			<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;"><?php echo $this->_tpl_vars['aLang']['block_blogs_self']; ?>
</a></li>
		<?php endif; ?>
	</ul>
	
	<div class="block-content">
		<?php echo '
			<script language="JavaScript" type="text/javascript">
			var lsBlockBlogs;
			window.addEvent(\'domready\', function() {       
				lsBlockBlogs=new lsBlockLoaderClass();
			});
			</script>
		'; ?>

		<?php echo $this->_tpl_vars['sBlogsTop']; ?>

	</div>

	<div class="bottom">
		<a href="<?php echo smarty_function_router(array('page' => 'blogs'), $this);?>
"><?php echo $this->_tpl_vars['aLang']['block_blogs_all']; ?>
</a>
	</div>
</div>