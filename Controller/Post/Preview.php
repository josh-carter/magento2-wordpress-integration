<?php
/*
 *
 */
namespace FishPig\WordPress\Controller\Post;

/* Parent Class */
use FishPig\WordPress\Controller\Post\View;

class Preview extends View
{
	/*
	 * Load and return a Post model
	 *
	 * @return \FishPig\WordPress\Model\Post|false 
	 */
  protected function _getEntity()
  {
    $post = $this->factory->create('Post')->load(
    	$this->getRequest()->getParam('preview_id')
    );
    
    if ($revision = $post->getLatestRevision()) {
	    return $revision;
    }

		return $post->getId() ? $post : false;
  }

	/*
	 * @return false
	 */
	protected function _getForward()
	{
		return false;
	}
	
	/*
	 * @return false
	 */
	protected function _canPreview()
	{
		return false;
	}
}
