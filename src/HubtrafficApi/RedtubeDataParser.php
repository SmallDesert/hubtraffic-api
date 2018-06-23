<?php

namespace HubtrafficApi;

/**
 * Parse data from redtube api
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @license MIT
 * @version 1.1.1
 * @package HubtrafficApi
 */
class RedtubeDataParser implements IDataParser {

	/**
	 * @inheritdoc
	 */
	public function parseVideoData($source, $videoId, $data) {
		$video = new Video($source, $videoId);

		$video->setUrl($data->video->url);
		$video->setRating((double)$data->video->rating);
		$video->setRatingCount((int)$data->video->ratings);
		$video->setPublishDate(new \DateTime($data->video->publish_date));

		$video->setTitle($data->video->title);
		$video->setDuration($data->video->duration);

		foreach ($data->video->thumbs as $thumb) {
			$video->addThumb($thumb->src);
		}
		foreach ($data->video->tags as $tag) {
			$video->addTag($tag);
		}

		if (isset($data->video->stars)) {
			foreach ((array)$data->video->stars as $pornstar) {
				$video->addPornstar($pornstar);
			}
		}

		return $video;
	}

	/**
	 * @inheritdoc
	 */
	public function parseEmbedData($data) {
		$embed = base64_decode(($data->embed->code));
		return '<iframe src="'.$embed.'" frameborder="0" width="560" height="315" scrolling="no" allowfullscreen></iframe>';
	}

	/**
	 * @inheritdoc
	 */
	public function parseIsActive($data) {
		return isset($data->active) && (bool)$data->active->is_active;
	}

}