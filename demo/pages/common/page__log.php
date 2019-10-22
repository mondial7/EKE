<?php

(new EKELog())->save('pageview', [
	'account'	=> Session::get('id','Not Logged'),
	'page'		=> $currentPage,
]);
