<?php
namespace App;

use Carbon\Carbon;

class DateFormatter
{
  public function formatDate($feeds)
  {
    foreach ($feeds as $feed) {
      $feed->created_at = Carbon::parse($feed->created_at)->diffForHumans(null, true);
    }
    return $feeds;
  }
}
?>
