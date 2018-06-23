<?php

namespace HubtrafficApi;

/**
 * Parse data from pornhub api
 * @author Pavel Plzák <pavelplzak@protonmail.com>
 * @license MIT
 * @version 1.1.1
 * @package HubtrafficApi
 */
class PornhubDataParser implements IDataParser {

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
			$video->addTag($tag->tag_name);
		}

		if (isset($data->video->pornstars)) {
			foreach ($data->video->pornstars as $pornstar) {
				$video->addPornstar($pornstar->pornstar_name);
			}
		}

		return $video;
	}

	/**
	 * @inheritdoc
	 */
	public function parseEmbedData($data) {
		return htmlspecialchars_decode($data->embed->code);
	}

	/**
	 * @inheritdoc
	 */
	public function parseIsActive($data) {
		return isset($data->active) && (bool)$data->active->is_active;
	}

}