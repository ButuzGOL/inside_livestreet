<?php /* Smarty version 2.6.19, created on 2010-09-10 19:47:39
         compiled from actions/ActionProfile/whois.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'actions/ActionProfile/whois.tpl', 22, false),array('function', 'date_format', 'actions/ActionProfile/whois.tpl', 46, false),array('function', 'router', 'actions/ActionProfile/whois.tpl', 55, false),array('function', 'hook', 'actions/ActionProfile/whois.tpl', 85, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'header.tpl', 'smarty_include_vars' => array('menu' => 'profile')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('oSession', $this->_tpl_vars['oUserProfile']->getSession()); ?>
<?php $this->assign('oVote', $this->_tpl_vars['oUserProfile']->getVote()); ?>
			
<div class="user-profile">
	<p class="strength">
		<?php echo $this->_tpl_vars['aLang']['user_skill']; ?>
: <strong class="total" id="user_skill_<?php echo $this->_tpl_vars['oUserProfile']->getId(); ?>
"><?php echo $this->_tpl_vars['oUserProfile']->getSkill(); ?>
</strong>
	</p>


	<div class="voting <?php if ($this->_tpl_vars['oUserProfile']->getRating() >= 0): ?>positive<?php else: ?>negative<?php endif; ?> <?php if (! $this->_tpl_vars['oUserCurrent'] || $this->_tpl_vars['oUserProfile']->getId() == $this->_tpl_vars['oUserCurrent']->getId()): ?>guest<?php endif; ?> <?php if ($this->_tpl_vars['oVote']): ?> voted <?php if ($this->_tpl_vars['oVote']->getDirection() > 0): ?>plus<?php elseif ($this->_tpl_vars['oVote']->getDirection() < 0): ?>minus<?php endif; ?><?php endif; ?>">
		<a href="#" class="plus" onclick="lsVote.vote(<?php echo $this->_tpl_vars['oUserProfile']->getId(); ?>
,this,1,'user'); return false;"></a>
		<div class="total" title="<?php echo $this->_tpl_vars['aLang']['user_vote_count']; ?>
: <?php echo $this->_tpl_vars['oUserProfile']->getCountVote(); ?>
"><?php if ($this->_tpl_vars['oUserProfile']->getRating() > 0): ?>+<?php endif; ?><?php echo $this->_tpl_vars['oUserProfile']->getRating(); ?>
</div>
		<a href="#" class="minus" onclick="lsVote.vote(<?php echo $this->_tpl_vars['oUserProfile']->getId(); ?>
,this,-1,'user'); return false;"></a>
	</div>


	<img src="<?php echo $this->_tpl_vars['oUserProfile']->getProfileAvatarPath(100); ?>
" alt="avatar" class="avatar" />
	<h3><?php echo $this->_tpl_vars['oUserProfile']->getLogin(); ?>
</h3>
	<?php if ($this->_tpl_vars['oUserProfile']->getProfileName()): ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileName())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
					
	<?php endif; ?>										
</div>


<?php if ($this->_tpl_vars['oUserProfile']->getProfileSex() != 'other' || $this->_tpl_vars['oUserProfile']->getProfileBirthday() || ( $this->_tpl_vars['oUserProfile']->getProfileCountry() || $this->_tpl_vars['oUserProfile']->getProfileRegion() || $this->_tpl_vars['oUserProfile']->getProfileCity() ) || $this->_tpl_vars['oUserProfile']->getProfileAbout() || $this->_tpl_vars['oUserProfile']->getProfileSite()): ?>
	<h2><?php echo $this->_tpl_vars['aLang']['profile_privat']; ?>
</h2>
	<table class="table">		
		<?php if ($this->_tpl_vars['oUserProfile']->getProfileSex() != 'other'): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['aLang']['profile_sex']; ?>
:</td>
				<td>
					<?php if ($this->_tpl_vars['oUserProfile']->getProfileSex() == 'man'): ?>
						<?php echo $this->_tpl_vars['aLang']['profile_sex_man']; ?>

					<?php else: ?>
						<?php echo $this->_tpl_vars['aLang']['profile_sex_woman']; ?>

					<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
			
		<?php if ($this->_tpl_vars['oUserProfile']->getProfileBirthday()): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['aLang']['profile_birthday']; ?>
:</td>
				<td><?php echo smarty_function_date_format(array('date' => $this->_tpl_vars['oUserProfile']->getProfileBirthday(),'format' => 'j F Y'), $this);?>
</td>
			</tr>
		<?php endif; ?>
		
		<?php if (( $this->_tpl_vars['oUserProfile']->getProfileCountry() || $this->_tpl_vars['oUserProfile']->getProfileRegion() || $this->_tpl_vars['oUserProfile']->getProfileCity() )): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['aLang']['profile_place']; ?>
:</td>
				<td>
				<?php if ($this->_tpl_vars['oUserProfile']->getProfileCountry()): ?>
					<a href="<?php echo smarty_function_router(array('page' => 'people'), $this);?>
country/<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileCountry())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileCountry())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><?php if ($this->_tpl_vars['oUserProfile']->getProfileCity()): ?>,<?php endif; ?>
				<?php endif; ?>						
				<?php if ($this->_tpl_vars['oUserProfile']->getProfileCity()): ?>
					<a href="<?php echo smarty_function_router(array('page' => 'people'), $this);?>
city/<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileCity())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileCity())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
				<?php endif; ?>
				</td>
			</tr>
		<?php endif; ?>
							
		<?php if ($this->_tpl_vars['oUserProfile']->getProfileAbout()): ?>					
			<tr>
				<td><?php echo $this->_tpl_vars['aLang']['profile_about']; ?>
:</td>
				<td><?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileAbout())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
			</tr>	
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['oUserProfile']->getProfileSite()): ?>
			<tr>
				<td><?php echo $this->_tpl_vars['aLang']['profile_site']; ?>
:</td>
				<td>
					<a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileSite(true))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" rel="nofollow">
						<?php if ($this->_tpl_vars['oUserProfile']->getProfileSiteName()): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileSiteName())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php else: ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['oUserProfile']->getProfileSite())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php endif; ?>
					</a>
				</td>
			</tr>
		<?php endif; ?>
		<?php echo smarty_function_hook(array('run' => 'profile_whois_privat_item','oUserProfile' => $this->_tpl_vars['oUserProfile']), $this);?>

	</table>
<?php endif; ?>

<?php echo smarty_function_hook(array('run' => 'profile_whois_item','oUserProfile' => $this->_tpl_vars['oUserProfile']), $this);?>


<h2><?php echo $this->_tpl_vars['aLang']['profile_activity']; ?>
</h2>
<table class="table">
	<?php if ($this->_tpl_vars['aUsersFriend']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_friends']; ?>
:</td>
			<td>
				<?php $_from = $this->_tpl_vars['aUsersFriend']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oUser']):
?>        						
					<a href="<?php echo $this->_tpl_vars['oUser']->getUserWebPath(); ?>
"><?php echo $this->_tpl_vars['oUser']->getLogin(); ?>
</a>
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['oConfig']->GetValue('general.reg.invite') && $this->_tpl_vars['oUserInviteFrom']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_invite_from']; ?>
:</td>
			<td>							       						
				<a href="<?php echo $this->_tpl_vars['oUserInviteFrom']->getUserWebPath(); ?>
"><?php echo $this->_tpl_vars['oUserInviteFrom']->getLogin(); ?>
</a>&nbsp;         					
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['oConfig']->GetValue('general.reg.invite') && $this->_tpl_vars['aUsersInvite']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_invite_to']; ?>
:</td>
			<td>
				<?php $_from = $this->_tpl_vars['aUsersInvite']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['oUserInvite']):
?>        						
					<a href="<?php echo $this->_tpl_vars['oUserInvite']->getUserWebPath(); ?>
"><?php echo $this->_tpl_vars['oUserInvite']->getLogin(); ?>
</a>&nbsp; 
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['aBlogsOwner']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_blogs_self']; ?>
:</td>
			<td>							
				<?php $_from = $this->_tpl_vars['aBlogsOwner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blog_owner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blog_owner']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oBlog']):
        $this->_foreach['blog_owner']['iteration']++;
?>
					<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><?php if (! ($this->_foreach['blog_owner']['iteration'] == $this->_foreach['blog_owner']['total'])): ?>, <?php endif; ?>								      		
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['aBlogAdministrators']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_blogs_administration']; ?>
:</td>
			<td>
				<?php $_from = $this->_tpl_vars['aBlogAdministrators']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blog_user'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blog_user']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oBlogUser']):
        $this->_foreach['blog_user']['iteration']++;
?>
					<?php $this->assign('oBlog', $this->_tpl_vars['oBlogUser']->getBlog()); ?>
					<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><?php if (! ($this->_foreach['blog_user']['iteration'] == $this->_foreach['blog_user']['total'])): ?>, <?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['aBlogModerators']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_blogs_moderation']; ?>
:</td>
			<td>
				<?php $_from = $this->_tpl_vars['aBlogModerators']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blog_user'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blog_user']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oBlogUser']):
        $this->_foreach['blog_user']['iteration']++;
?>
					<?php $this->assign('oBlog', $this->_tpl_vars['oBlogUser']->getBlog()); ?>
					<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><?php if (! ($this->_foreach['blog_user']['iteration'] == $this->_foreach['blog_user']['total'])): ?>, <?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['aBlogUsers']): ?>
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_blogs_join']; ?>
:</td>
			<td>
				<?php $_from = $this->_tpl_vars['aBlogUsers']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['blog_user'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['blog_user']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['oBlogUser']):
        $this->_foreach['blog_user']['iteration']++;
?>
					<?php $this->assign('oBlog', $this->_tpl_vars['oBlogUser']->getBlog()); ?>
					<a href="<?php echo smarty_function_router(array('page' => 'blog'), $this);?>
<?php echo $this->_tpl_vars['oBlog']->getUrl(); ?>
/"><?php echo ((is_array($_tmp=$this->_tpl_vars['oBlog']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a><?php if (! ($this->_foreach['blog_user']['iteration'] == $this->_foreach['blog_user']['total'])): ?>, <?php endif; ?>
				<?php endforeach; endif; unset($_from); ?>
			</td>
		</tr>
	<?php endif; ?>

	<?php echo smarty_function_hook(array('run' => 'profile_whois_activity_item','oUserProfile' => $this->_tpl_vars['oUserProfile']), $this);?>

	
	<tr>
		<td><?php echo $this->_tpl_vars['aLang']['profile_date_registration']; ?>
:</td>
		<td><?php echo smarty_function_date_format(array('date' => $this->_tpl_vars['oUserProfile']->getDateRegister()), $this);?>
</td>
	</tr>	
	
	<?php if ($this->_tpl_vars['oSession']): ?>				
		<tr>
			<td><?php echo $this->_tpl_vars['aLang']['profile_date_last']; ?>
:</td>
			<td><?php echo smarty_function_date_format(array('date' => $this->_tpl_vars['oSession']->getDateLast()), $this);?>
</td>
		</tr>
	<?php endif; ?>
</table>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'footer.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>