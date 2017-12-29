<?php

namespace App\Http\Forms;

use App\Exceptions\ThrottleException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', new \App\Reply);
    }

   protected function failedAuthorization()
   {
        throw new ThrottleException("You are replying too frequently!");
   }

    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }
}
