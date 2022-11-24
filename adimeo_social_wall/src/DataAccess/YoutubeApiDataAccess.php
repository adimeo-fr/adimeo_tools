<?php

namespace Drupal\adimeo_social_wall\DataAccess;

use Google\Client;
use Google\Service\YouTube;
use Google\Service\YouTube\ChannelListResponse;
use Google\Service\YouTube\PlaylistItemListResponse;

class YoutubeApiDataAccess {

  protected Client $client;

  protected YouTube $youtubeService;

  public function __construct(string $apiKey) {
    $this->client = new Client();
    $this->client->setApplicationName('Adimeo Social Wall');
    $this->client->setScopes([
      'https://www.googleapis.com/auth/youtube.readonly'
    ]);
    $this->client->setDeveloperKey($apiKey);

    $this->youtubeService = new YouTube($this->client);
  }

  /**
   * Returns a collection of zero or more channel resources that match the
   * request criteria.
   *
   * @link https://developers.google.com/youtube/v3/docs/channels/list
   *
   * @param string $channelName
   *
   * @return \Google\Service\YouTube\ChannelListResponse
   */
  public function getOneChannelByName(string $channelName): ChannelListResponse {
    return $this->youtubeService->channels->listChannels('snippet,contentDetails', [
      'forUsername' => $channelName,
      'maxResults' => 1,
    ]);
  }

  /**
   * Returns a collection of playlist items that match the API request parameters.
   *
   * @link https://developers.google.com/youtube/v3/docs/playlistItems/list
   *
   * @param string $playlistId
   * @param int $maxResults
   *
   * @return \Google\Service\YouTube\PlaylistItemListResponse
   */
  public function getPlaylistItems(string $playlistId, int $maxResults): PlaylistItemListResponse {
    return $this->youtubeService->playlistItems->listPlaylistItems('contentDetails,snippet', [
      'playlistId' => $playlistId,
      'maxResults' => $maxResults,
    ]);
  }

}
