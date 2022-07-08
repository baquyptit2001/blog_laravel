<?php


test('can get post', function () {
    $response = $this->getJson('api/posts/qui-modi-natus-aut-rerum');
    expect($response->getStatusCode())->toBe(200);
    $post = $response->getData()->data;
    expect($post->slug)->toBe('qui-modi-natus-aut-rerum');
});
