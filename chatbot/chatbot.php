<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $query = $data['query'] ?? '';

    // Load the FAQ JSON file
    $faqData = json_decode(file_get_contents('faq.json'), true);
    $response = findMatchingAnswer($query, $faqData);

    // Return the response as JSON
    echo json_encode(['response' => $response]);
    exit();
}

// Function to find the best matching answer from the FAQ
function findMatchingAnswer($query, $faqData) {
    $bestMatch = null;
    $maxScore = 0;
    
    // Simple keyword matching
    foreach ($faqData['questions'] as $faq) {
        $score = calculateMatchScore($query, $faq['question']);
        
        // If the match score is higher than the previous best match, update
        if ($score > $maxScore) {
            $bestMatch = $faq['answer'];
            $maxScore = $score;
        }
    }

    // If no good match found, return a default response
    if ($bestMatch === null) {
        return "Sorry, I couldn't find an answer to your question. Please try again.";
    }

    return $bestMatch;
}

// Function to calculate match score based on simple keyword matching
function calculateMatchScore($query, $faqQuestion) {
    $queryWords = explode(" ", strtolower($query));
    $faqWords = explode(" ", strtolower($faqQuestion));

    $commonWords = array_intersect($queryWords, $faqWords);
    $score = count($commonWords);

    return $score;
}
?>
