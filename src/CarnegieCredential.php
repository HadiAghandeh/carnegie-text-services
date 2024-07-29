<?php

declare(strict_types=1);

namespace HadiAghandeh\CarnegieTextService;

use DateTime;

class CarnegieCredential
{
  public function GradeTextHash($password, $essay) {

    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.v', microtime(true));

	return [$dateTime, hash_hmac('sha256', $essay . '-' . $dateTime, $password)];
  }

  public function EssayCredential($password, $essayId) {

    $dateTime = DateTime::createFromFormat('Y-m-d\TH:i:s.v', microtime(true));

	return [$dateTime, hash_hmac('sha256', $essayId . '-' . $dateTime, $password)];
  }
}
