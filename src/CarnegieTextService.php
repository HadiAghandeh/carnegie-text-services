<?php

declare(strict_types=1);

namespace HadiAghandeh\CarnegieTextService;

use HadiAghandeh\CarnegieTextService\CarnegieCredential;
use GuzzleHttp\Client;

class CarnegieTextService
{
    private $password;
    private $requester;
    private $requesterID;

    private $credential;

    public function __construct(
        $password,
        $requester,
        $requesterID
    ) {
        $this->password = $password;
        $this->requester = $requester;
        $this->requesterID = $requesterID;

        $this->credential = new CarnegieCredential;
    }


    function gradeText(
		$essayId,
		$userId,
		$essay,
		$requesterId = null,
		$requester = null,
	)
	{
		[$dateTime, $hash] = $this->credential->GradeTextHash($this->password, $essay);


        $data = [
			'requester' => $requester ?? $this->requester,
			'requesterID' => $requesterId ?? $this->requesterID,
			'userID' => $userId,
			'essayID' => $essayId,
			'essay' => $essay,
			'dateTimeStamp' => $dateTime,
			'hashToken' => $hash
		];

        $client = new GuzzleHttp\Client();

        $url = self::URLS['grade-text'];
        $res = $client->request('POST', $url, $data);

        $body = $res->getBody();

		return json_decode($body, true);
	}

	public function addEssay(
		$essayId,
		$question,
		$essay1,
		$essay2,
		$essay3
	){
		[$dateTime, $hash] = $this->credential->EssayCredential($this->password, $essayId);

        $data = [
			'Requester' => $this->requester,
			'EssayID' => $essayId,
			'Question' => $question,
			'Essay_1' => $essay1,
			'Essay_2' => $essay2,
			'Essay_3' => $essay3,
			'DateTimeStamp' => $dateTime,
			'HashToken' => $hash
		];

		$url = self::URLS['essay-add'];

        $client = new GuzzleHttp\Client();

        $url = self::URLS['grade-text'];
        $res = $client->request('POST', $url, $data);

        $body = $res->getBody();

		return json_decode($body);
	}

	public function updateEssay(
		$essayId,
		$question,
		$essay1,
		$essay2 = null,
		$essay3 = null
	){
		[$dateTime, $hash] = $this->credential->EssayCredential($this->password, $essayId);


        $data = array_filter([
			'Requester' => $this->requester,
			'EssayID' => $essayId,
			'Question' => $question,
			'Response_1' => $essay1,
			'Response_2' => $essay2,
			'Response_3' => $essay3,
			'DateTimeStamp' => $dateTime,
			'HashToken' => $hash
		]);

        $client = new GuzzleHttp\Client();

        $url = self::URLS['essay-update'];
		$res = $client->request('POST', $url, $data);

        $body = $res->getBody();

		return json_decode($body);
	}


	const URLS = [
		'grade-text' => 'http://textservices.carnegiespeech.com/gradeAPI.php',
		'essay-add' => 'https://textservices.carnegiespeech.com/ts_add_essay.php',
		'essay-update' => 'https://textservices.carnegiespeech.com/tsf_update_essay.php',
	];

}
