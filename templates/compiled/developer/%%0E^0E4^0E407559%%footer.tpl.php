<?php /* Smarty version 2.6.19, created on 2010-09-08 16:59:10
         compiled from footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'hook', 'footer.tpl', 1, false),)), $this); ?>
		<?php echo smarty_function_hook(array('run' => 'content_end'), $this);?>

		</div><!-- /content -->

		<?php if (! $this->_tpl_vars['noSidebar']): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'sidebar.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<?php endif; ?>
	</div><!-- /wrapper -->

	<div id="footer">
		<div class="right">Powered by <a href="http://livestreetcms.com">LiveStreet</a></div>
		Автор шаблона &mdash; <a href="http://deniart.ru">deniart</a>
	</div>

</div><!-- /container -->

<?php echo smarty_function_hook(array('run' => 'body_end'), $this);?>


</body>
</html>