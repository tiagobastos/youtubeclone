<?php

namespace App\Http\Requests;

use Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ChannelUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $channelId = Auth::user()->channel->first()->id;

        return [
            'name' => [ 
                'required', 
                'max:255', 
                Rule::unique('channels', 'name')
                    ->ignore($channelId, 'id') 
            ],
            'slug' => [ 
                'required', 
                'max:255', 
                'alpha_num', 
                Rule::unique('channels', 'slug')
                    ->ignore($channelId, 'id') 
            ],
            'description' => 'max:1000',
        ];
    }
}
