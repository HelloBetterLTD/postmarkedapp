<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/24/15
 * Time: 6:32 PM
 * To change this template use File | Settings | File Templates.
 */

class PostmarkAttachments extends Controller {

	private static $allowed_actions = array(
		'a'
	);

	public function a(){
		$id = $this->request->param('ID');
		$attachment = Attachment::get()->filterAny(array(
			'ID'			=> $id,
			'ContentID'		=> $id
		))->first();
		if($attachment){
			return $attachment->returnToBrowser();
		}
		return $this->httpError(404);

	}

} 