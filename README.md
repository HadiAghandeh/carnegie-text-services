# CarnegieTextService PHP Package

## Overview

The `CarnegieTextService` PHP package provides a set of functionalities to interact with the Carnegie Text Service API. This package allows users to grade texts and manage essays using the provided API endpoints. 

## Installation

To install the package, use Composer:

```bash
composer require hadiaghandeh/carnegie-text-service
```

# Usage

```php
use HadiAghandeh\CarnegieTextService\CarnegieTextService;

use HadiAghandeh\CarnegieTextService\CarnegieTextService;

$password = 'your_password';
$requester = 'your_requester';
requesterID = 'your_requesterID';

$service = new CarnegieTextService($password, $requester, $requesterID);

// Grading an essay
$response = $service->gradeText('essay123', 'user456', 'This is the content of the essay.');
print_r($response);
/*
[
  "overall" => 3.25
  "lexical" => 3
  "task" => 3
  "grammar" => 5
  "cohesion" => 2
]
*/

// Adding a new essay
$response = $service->addEssay(
    'essay123', // essay ID
    'What is the meaning of life? Why are we here?', // question
    'The meaning of life is to seek knowledge and understanding, to continuously grow and evolve, and to contribute to the well-being of others. We are here to experience, learn, and find our own purpose through the connections we make and the actions we take. ', // example response 1
    'The meaning of life is to achieve spiritual enlightenment and fulfillment. We are here to learn important life lessons, to develop compassion and love, and to realize our interconnectedness with all living beings and the universe.', // example response 2
    'The meaning of life is a product of evolution and the natural processes that led to our existence. We are here as a result of the biological imperative to survive, reproduce, and pass on our genes, while also having the capacity to explore, understand, and shape our environment.' // example response 3
);
print_r($response);

/*
[
  "status" => "success"
  "data" => array:1 [
    "essay" => array:1 [
      "essay_id" => "essay123"
    ]
  ]
  "message" => "New essay added successfully."
]
 */

// Updating an existing essay
$response = $service->updateEssay('essay123', 'Updated question?', 'Updated essay 1 content', 'Updated essay 2 content', 'Updated essay 3 content');
print_r($response);

```

# License
This package is open-sourced software licensed under the MIT license.