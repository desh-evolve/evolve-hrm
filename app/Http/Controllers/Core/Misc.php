<?php

namespace App\Http\Controllers\Core;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CommonModel;

class Misc extends Controller
{
    private $common = null;
    
    public function __construct()
    {
        $this->common = new CommonModel();
    }

    //Adds sort prefixes to an array maintaining the original order. Primarily used because Flex likes to reorded arrays with string keys.
	static function addSortPrefix( $arr, $begin_counter = 1 ) {
		$i=$begin_counter;
		foreach( $arr as $key => $value ) {
			$sort_prefix = NULL;
			if ( substr($key, 0, 1 ) != '-' ) {
				$sort_prefix = '-'.str_pad($i, 4, 0, STR_PAD_LEFT).'-';
			}
			$retarr[$sort_prefix.$key] = $value;
			$i++;
		}

		if ( isset($retarr) ) {
			return $retarr;
		}

		return FALSE;
	}

	//Removes sort prefixes from an array.
	static function trimSortPrefix( $value, $trim_arr_value = FALSE ) {
		if ( is_array($value) AND count($value) > 0 ) {
			foreach( $value as $key => $val ) {
				if ( $trim_arr_value == TRUE ) {
					$retval[$key] = preg_replace('/^-[0-9]{3,4}-/i', '', $val);
				} else {
					$retval[preg_replace('/^-[0-9]{3,4}-/i', '', $key)] = $val;
				}
			}
		} else {
			$retval = preg_replace('/^-[0-9]{3,4}-/i', '', $value );
		}

		if ( isset($retval) ) {
			return $retval;
		}

		return $value;
	}
}