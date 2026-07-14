<?php

test('returns a successful response for home', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('returns a successful response for about', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
});

test('returns a successful response for achievements', function () {
    $response = $this->get(route('achievements'));

    $response->assertOk();
});
