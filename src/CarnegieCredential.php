<?php

declare(strict_types=1);

namespace HadiAghandeh\CarnegieTextService;

use DateTime;

class CarnegieCredential
{
  public function GradeTextHash($password, $essay) {

	$dateTime = (new DateTime())->format('Y-m-d\TH:i:s.v');

	return [$dateTime, hash_hmac('sha256', $essay . '-' . $dateTime, $password)];
  }

  public function EssayCredential($password, $essayId) {

	$dateTime = (new DateTime())->format('Y-m-d\TH:i:s.v');

	return [$dateTime, hash_hmac('sha256', $essayId . '-' . $dateTime, $password)];
  }
}
