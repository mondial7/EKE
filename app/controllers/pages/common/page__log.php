<?php

$log_data = [ 
			 "account" => Session::get('id','Not Logged'), 
			 "page" => $currentPage
			];

(new EKELog())->save( 'pageview', $log_data );