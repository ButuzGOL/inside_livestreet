<?php /* Smarty version 2.6.19, created on 2010-09-10 18:48:17
         compiled from comment_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'comment_list.tpl', 11, false),array('function', 'date_format', 'comment_list.tpl', 20, false),)), $this); ?>
<div class="comments comment-list">
	<?php $_from = $this->_tpl_vars['aComments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oComment']):
?>
		<?php $this->assign('oUser', $this->_tpl_vars['oComment']->getUser()); ?>
		<?php $this->assign('oTopic', $this->_tpl_vars['oComment']->getTarget()); ?>
		<?php $this->assign('oBlog', $this->_tpl_vars['oTopic']->getBlog()); ?>
		
		
		<div class="comment">
			<div class="comment-inner">
				<div class="path">
					<a href="<?php echo $this->_tpl_vars['oBlog']->getUrlFull(); ?>
" class="blog-name"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a> &rarr;
					<a href="<?php echo $this->_tpl_vars['oTopic']->getUrl(); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['oTopic']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
					<a href="<?php echo $this->_tpl_vars['oTopic']->getUrl(); ?>
#comments"><?php echo $this->_tpl_vars['oTopic']->getCountComment(); ?>
</a>
				</div>
			
			
				<ul class="info">
					<li class="avatar"><a href="<?php echo $this->_tpl_vars['oUser']->getUserWebPath(); ?>
"><img src="<?php echo $this->_tpl_vars['oUser']->getProfileAvatarPath(24); ?>
" alt="avatar" /></a></li>
					<li class="username"><a href="<?php echo $this->_tpl_vars['oUser']->getUserWebPath(); ?>
"><?php echo $this->_tpl_vars['oUser']->getLogin(); ?>
</a></li>
					<li class="date"><?php echo smarty_function_date_format(array('date' => $this->_tpl_vars['oComment']->getDate()), $this);?>
</li>
					<li><a href="<?php echo $this->_tpl_vars['oTopic']->getUrl(); ?>
#comment<?php echo $this->_tpl_vars['oComment']->getId(); ?>
">#</a></li> 				
					<li class="voting <?php if ($this->_tpl_vars['oComment']->getRating() > 0): ?>positive<?php elseif ($this->_tpl_vars['oComment']->getRating() < 0): ?>negative<?php endif; ?> <?php if (! $this->_tpl_vars['oUserCurrent'] || $this->_tpl_vars['oComment']->getUserId() == $this->_tpl_vars['oUserCurrent']->getId() || strtotime ( $this->_tpl_vars['oComment']->getDate() ) < time()-$this->_tpl_vars['oConfig']->GetValue('acl.vote.comment.limit_time')): ?>guest<?php endif; ?>   <?php if ($this->_tpl_vars['oVote']): ?> voted <?php if ($this->_tpl_vars['oVote']->getDirection() > 0): ?>plus<?php else: ?>minus<?php endif; ?><?php endif; ?>  ">
						<span class="total"><?php if ($this->_tpl_vars['oComment']->getRating() > 0): ?>+<?php endif; ?><?php echo $this->_tpl_vars['oComment']->getRating(); ?>
</span>
					</li>
				</ul>		
						
						
				<div class="content">						
					<?php if ($this->_tpl_vars['oComment']->isBad()): ?>
						<div style="color: #aaa;"><?php echo $this->_tpl_vars['oComment']->getText(); ?>
</div>						
					<?php else: ?>
						<?php echo $this->_tpl_vars['oComment']->getText(); ?>

					<?php endif; ?>		
				</div>
			</div>
		</div>
	<?php endforeach; endif; unset($_from); ?>	
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'paging.tpl', 'smarty_include_vars' => array('aPaging' => ($this->_tpl_vars['aPaging']))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>