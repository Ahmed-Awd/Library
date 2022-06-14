<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use App\Scopes\DeleteScope;
use DateTime;
class EmailTemplate extends Model
{
    protected $table = 'emailtemplates';
 

    public static function getRecordWithSlug($slug)
    {
        return EmailTemplate::where('slug', '=', $slug)->first();
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new DeleteScope);
    }

    /**
     * Common email function to send emails
     * @param  [type] $template [key of the template]
     * @param  [type] $data     [data to be passed to view]
     * @return [type]           [description]
     */
    public function sendEmail($template, $data)
    {	
    	$template = EmailTemplate::where('title', '=', $template)->first();

        $template->content = strtr($template->content, $data);

    	$content = \Blade::compileString($this->getTemplate($template));
		$result = $this->render($content, $data);
		
		\Mail::send('emails.template', ['body' => $result], function ($message) use ($template, $data) 
        {
			
		    $message->from($template->from_email, $template->from_name);
		    $message->to($data['to_email'])->subject($template->subject);
		});

		if(count(\Mail::failures()) > 0)
			return False;
		return TRUE;
	}
	    /**
     * Common notification function to send notification
     * @param  [type] $template [key of the template]
     * @param  [type] $data     [data to be passed to view]
     * @return [type]           [description]
     */

	public function sendNotification($template, $data,$parent,$student)
    {	
		$title=$template;
    	$template = EmailTemplate::where('title', '=', $template)->first();

        $template->content = strtr($template->content, $data);

    	$content = \Blade::compileString($this->getTemplate($template));
		 

		
		$notifi = new \App\Notification();
		$notifi->title =$title;// getPhrase('Absence_status');
		$notifi->slug  = $notifi->makeSlug($notifi->title);
		$notifi->short_description = $template->subject;
		$notifi->description       = $content;
		$datetime = new DateTime('today');
		$notifi->valid_from =  $datetime->format('Y-m-d H:i:s');
		$datetime = new DateTime('tomorrow + 5day');
		$notifi->valid_to =  $datetime->format('Y-m-d H:i:s');
		//$notifi->user_stamp($request);
		$notifi->save();
		$parent_notifi = new \App\user_notifications();
		$parent_notifi->user_id = $parent->id;
		$parent_notifi->notification_id = $notifi->id;
		//$parent_notifi->user_stamp($request);
		$parent_notifi->save();
		return true;
	}


	/**
	 * Returns the template html code by forming header, body and footer
	 * @param  [type] $template [description]
	 * @return [type]           [description]
	 */
	public function getTemplate($template)
	{
		$header = EmailTemplate::where('title', '=', 'header')->first();
    	$footer = EmailTemplate::where('title', '=', 'footer')->first();
    	
    	$view = \View::make('emails.template', [
    											'header' => $header->content, 
    											'footer' => $footer->content,
    											'body' => $template->content,
    											]);

		return $view->render();
	}

	/**
	 * Prepares the view from string passed along with data
	 * @param  [type] $__php  [description]
	 * @param  [type] $__data [description]
	 * @return [type]         [description]
	 */
    public function render($__php, $__data)
	{

	    $obLevel = ob_get_level();
	    ob_start();
	    extract($__data, EXTR_SKIP);
	    try {
	        eval('?' . '>' . $__php);
	    } catch (Exception $e) {
	        while (ob_get_level() > $obLevel) ob_end_clean();
	        throw $e;
	    } catch (Throwable $e) {
	        while (ob_get_level() > $obLevel) ob_end_clean();
	        throw new FatalThrowableError($e);
	    }
	    return ob_get_clean();
	}

}
