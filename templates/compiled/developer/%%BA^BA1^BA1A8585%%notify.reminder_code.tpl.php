<?php /* Smarty version 2.6.19, created on 2010-09-09 19:38:07
         compiled from notify/russian/notify.reminder_code.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cfg', 'notify/russian/notify.reminder_code.tpl', 1, false),array('function', 'router', 'notify/russian/notify.reminder_code.tpl', 2, false),)), $this); ?>
Если вы хотите сменить себе пароль на сайте <a href="<?php echo smarty_function_cfg(array('name' => 'path.root.web'), $this);?>
"><?php echo smarty_function_cfg(array('name' => 'view.name'), $this);?>
</a>, то перейдите по ссылке ниже: 
<a href="<?php echo smarty_function_router(array('page' => 'login'), $this);?>
reminder/<?php echo $this->_tpl_vars['oReminder']->getCode(); ?>
/"><?php echo smarty_function_router(array('page' => 'login'), $this);?>
reminder/<?php echo $this->_tpl_vars['oReminder']->getCode(); ?>
/</a>

<br><br>
С уважением, администрация сайта <a href="<?php echo smarty_function_cfg(array('name' => 'path.root.web'), $this);?>
"><?php echo smarty_function_cfg(array('name' => 'view.name'), $this);?>
</a>