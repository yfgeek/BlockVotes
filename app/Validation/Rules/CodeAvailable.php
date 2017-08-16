<?php

namespace App\Validation\Rules;

use App\Models\Code;
use Respect\Validation\Rules\AbstractRule;

class CodeAvailable extends AbstractRule

{

    public function validate($input)
    {
        $result = Code::where('code',$input)->first();
        if($result){
            if($result['used'] == 0 ){
                return true;
            }
        }else {
            return false;
        }
    }

}