<?php /* Smarty version 2.6.19, created on 2010-09-08 16:59:10
         compiled from header_nav.tpl */ ?>
<?php if ($this->_tpl_vars['menu']): ?>
	<div id="nav">
		<?php if (in_array ( $this->_tpl_vars['menu'] , $this->_tpl_vars['aMenuContainers'] )): ?><?php echo $this->_tpl_vars['aMenuFetch'][$this->_tpl_vars['menu']]; ?>
<?php else: ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "menu.".($this->_tpl_vars['menu']).".tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php endif; ?>
	</div>
<?php endif; ?>