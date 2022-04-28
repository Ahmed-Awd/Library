<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
        return [
            'title'        => 'string|min:3|max:100',
            'description'  => 'string',
            'topic_id'     => 'exists:topics,id',
            'course_id '   => 'exists:courses,id',
            'image'        => 'image|mimes:jpg,jpeg,png|max:3000',
            'content_type' => 'in:file,video,audio,url,video_url,iframe,meeting_url,image_file',
            'file_path'    => '',
            'multi_branch' => 'boolean',
            'is_published' => 'boolean',
        ];
    }
}
