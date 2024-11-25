<?php

declare(strict_types=1);

namespace HadiAghandeh\CarnegieTextService;

use HadiAghandeh\CarnegieTextService\CarnegieCredential;
use GuzzleHttp\Client as GClient;

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


        $client = new GClient();

        $url = self::URLS['grade-text'];

		$options = [
			'form_params' => $data
		];

        $res = $client->post( $url, $options);

        $body = $res->getBody()->getContents();

		return $this->decodeTheResponse($body);
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
			'requester' => $this->requester,
			'essayID' => $essayId,
			'question' => $question,
			'essay_1' => $essay1,
			'essay_2' => $essay2,
			'essay_3' => $essay3,
			'dateTimeStamp' => $dateTime,
			'hashToken' => $hash
		];

		$url = self::URLS['essay-add'];

		$options = [
			'form_params' => $data
		];
        $client = new GClient();

        $res = $client->post( $url, $options);

        $body = $res->getBody()->getContents();

		return $this->decodeTheResponse($body);
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
			'requester' => $this->requester,
			'essayID' => $essayId,
			'question' => $question,
			'response_1' => $essay1,
			'response_2' => $essay2,
			'response_3' => $essay3,
			'dateTimeStamp' => $dateTime,
			'hashToken' => $hash
		]);

        $url = self::URLS['essay-update'];
		$options = [
			'form_params' => $data
		];
        $client = new GClient();

        $res = $client->post( $url, $options);

        $body = $res->getBody()->getContents();

		return $this->decodeTheResponse($body);
	}

    public function suggestion(
        $essayId,
		$userId,
		$essay,
        $essayType = 'Opinion',
        $instructionalMaterial = 'Instructions',
        $supplementalMaterial = 'Supplemental Material',
		$requesterId = null,
		$requester = null,
    ) {
		[$dateTime, $hash] = $this->credential->GradeTextHash($this->password, $essay);

        $data = array_filter([
			'requester' => $requester ?? $this->requester,
			'requesterID' => $requesterId ?? $this->requesterID,
			'essayID' => $essayId,
			'userId' => $userId,
			'essay' => $essay,
			'essayType' => $essayType,
			'instructionalMaterial' => $instructionalMaterial,
            'supplementalMaterial' => $supplementalMaterial,
            'requesterId' => $requesterId,
			'dateTimeStamp' => $dateTime,
			'hashToken' => $hash
		]);

        $url = self::URLS['suggestion'];

		$options = [
			'form_params' => $data
		];
        
        $client = new GClient();

        $res = $client->post( $url, $options);

        $body = $res->getBody()->getContents();

		return $this->decodeTheResponse($body);

    }

    private function decodeTheResponse($responseBody) {
        if($$json = json_decode(trim($responseBody), true)) {
            return $json;
        } else {
            return trim($responseBody);
        }
    }

    private $staging = false;

    public function staging() {
        $this->staging = true;
        return $this;
    }

    private function getAPIBaseUrl() {
        if($this->customBaseURL) {
            return $this->customBaseURL;
        }

        return $this->staging 
        ? "http://textservices.carnegiespeech.com"
        : "http://staging-textservices.carnegiespeech.com";
    }

    private $customBaseURL = null;

    private function setCustomBaseURL($baseURL) {
        $this->customBaseURL = $baseURL;
        return $this;
    }


	const URLS = [
		'grade-text' => $this->getAPIBaseUrl() . '/gradeAPI.php',
		'essay-add' => $this->getAPIBaseUrl() . '/ts_add_essay.php',
		'essay-update' => $this->getAPIBaseUrl() . '/ts_update_essay.php',
        'suggestion' => $this->getAPIBaseUrl() . '/gradeAPI.php'
	];

}
