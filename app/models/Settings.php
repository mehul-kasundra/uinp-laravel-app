<?php

class Settings extends \Eloquent {
	protected $table = 'settings';
	protected $guarded = array('_token');
}