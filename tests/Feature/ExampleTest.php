<?php

test('returns a successful response for home', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
});

test('returns a successful response for about', function () {
    $response = $this->get(route('about'));

    $response->assertOk();
});

test('returns a successful response for contact', function () {
    $response = $this->get(route('contact'));

    $response->assertOk();
});