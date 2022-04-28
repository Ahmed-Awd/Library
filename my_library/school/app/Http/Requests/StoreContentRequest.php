<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
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
            'title'        => 'required|string|min:3|max:100',
            'description'  => 'required|string',
            'topic_id'     => 'exists:topics,id',
            'course_id '   => 'exists:courses,id',
            'image'        => 'required|image|mimes:jpg,jpeg,png|max:3000',
            'content_type' => 'required|in:file,video,audio,url,video_url,iframe,meeting_url,image_file',
            'file_path'    => 'required',
            'multi_branch' => 'required|boolean',
            'is_published' => 'required|boolean',
        ];
    }
}
