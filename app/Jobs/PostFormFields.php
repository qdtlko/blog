<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Post;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;

//class PostFormFields extends Job implements ShouldQueue
//{
//    use InteractsWithQueue, SerializesModels;
//
//    /**
//     * Create a new job instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        //
//    }
//
//    /**
//     * Execute the job.
//     *
//     * @return void
//     */
//    public function handle()
//    {
//        //
//    }
//}


class PostFormFields extends Job implements SelfHandling
{
    /**
     * The id (if any) of the Post row
     *
     * @var integer
     */
    protected $id;

    /**
     * List of fields and default value for each field
     *
     * @var array
     */
    protected $fieldList = [
        'title' => '',
        'subtitle' => '',
        'page_image' => '',
        'content' => '',
        'meta_description' => '',
        'is_draft' => "0",
        'publish_date' => '',
        'publish_time' => '',
        'layout' => 'blog.layouts.post',
        'tags' => [],
    ];

    /**
     * Create a new command instance.
     *
     * @param integer $id
     */
    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * Execute the command.
     *
     * @return array of fieldnames => values
     */
    public function handle()
    {
        $fields = $this->fieldList;

        if ($this->id) {
            $fields = $this->fieldsFromModel($this->id, $fields);
        } else {
            $when = Carbon::now()->addHour();
            $fields['publish_date'] = $when->format('M-j-Y');
            $fields['publish_time'] = $when->format('g:i A');
        }

        foreach ($fields as $fieldName => $fieldValue) {
            $fields[$fieldName] = old($fieldName, $fieldValue);
        }

        return array_merge(
            $fields,
            ['allTags' => Tag::lists('tag')->all()]
        );
    }

    /**
     * Return the field values from the model
     *
     * @param integer $id
     * @param array $fields
     * @return array
     */
    protected function fieldsFromModel($id, array $fields)
    {
        $post = Post::findOrFail($id);
//        dd($post);

        $fieldNames = array_keys(array_except($fields, ['tags']));

        $fields = ['id' => $id];
//        dd($post);
        foreach ($fieldNames as $field) {
            $fields[$field] = $post->{$field};
        }

        $fields['tags'] = $post->tags()->lists('tag')->all();

        return $fields;
    }
}
